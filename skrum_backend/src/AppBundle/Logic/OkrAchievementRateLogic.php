<?php

namespace AppBundle\Logic;

use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TOkrActivity;

/**
 * OKR達成率ロジッククラス
 *
 * @author naoharu.tazawa
 */
class OkrAchievementRateLogic extends BaseLogic
{
    /**
     * OKR達成率再計算
     *
     * @param Auth $auth 認証情報
     * @param TOkr $tOkr チェック対象OKRエンティティ
     * @param boolean $weightedAverageRatioFlg 加重平均比率再設定フラグ
     * @return void
     */
    public function recalculate(Auth $auth, TOkr $tOkr, bool $weightedAverageRatioFlg = false)
    {
        $tOkrRepos = $this->getTOkrRepository();
        $postLogic = $this->getPostLogic();

        while (!empty($tOkr)) {
            // 親OKR存在チェック
            if (empty($tOkr->getParentOkr())) {
                return;
            }

            // 親OKRが「OKR種別 ！＝ 1:目標」の場合、再計算対象外
            if ($tOkr->getParentOkr()->getType() !== DBConstant::OKR_TYPE_OBJECTIVE) {
                return;
            }

            // 親子OKRを取得
            $tOkrArray = $tOkrRepos->getChildrenOkrsForRecalc(
                    $tOkr->getParentOkr()->getOkrId(),
                    $tOkr->getTimeframe()->getTimeframeId(),
                    $auth->getCompanyId()
                    );

            $recalcItems = $tOkrRepos->getRecalcItems(
                    $tOkr->getParentOkr()->getOkrId(),
                    $tOkr->getTimeframe()->getTimeframeId(),
                    $auth->getCompanyId()
                    );

            $summedWeightedAverageRatio = $recalcItems[0]['summedWeightedAverageRatio']; // 子OKRの加重平均比率の合計値
            $lockedRatioCount = $recalcItems[0]['lockedRatioCount']; // 子OKRの持分比率ロックフラグが立っている数
            $childrenOkrsCount = count($tOkrArray) - 1; // 子OKR数
            if (($childrenOkrsCount - $lockedRatioCount) !== 0) {
                $averageRatio = floor(((100 - $summedWeightedAverageRatio) / ($childrenOkrsCount - $lockedRatioCount)) * 10 ) / 10; // ロックフラグが立っていないOKRに設定するの加重平均比率
            }

            // 子OKRの加重平均比率を再設定（ $i=0 は 'parentOkr' なので $i=1 から始める）
            $weightedAverageAchievementRate = array();
            $tOkrArrayCount = count($tOkrArray);
            for ($i = 1; $i < $tOkrArrayCount; ++$i) {
                if ($weightedAverageRatioFlg) {
                    if ($tOkrArray[$i]['childOkr']->getRatioLockedFlg() == DBConstant::FLG_FALSE) {
                        $tOkrArray[$i]['childOkr']->setWeightedAverageRatio($averageRatio);
                    }
                }

                $weightedAverageAchievementRate[] = $tOkrArray[$i]['childOkr']->getAchievementRate() * $tOkrArray[$i]['childOkr']->getWeightedAverageRatio() / 100;
            }

            // 親OKRの前回達成率を取得
            $previousAchievementRate = $tOkrArray[0]['parentOkr']->getAchievementRate();

            // 親OKRの達成率を再設定
            $achievementRate = array_sum($weightedAverageAchievementRate);
            $tOkrArray[0]['parentOkr']->setAchievementRate($achievementRate);

            // 達成値、目標値、単位をリセット
            $tOkrArray[0]['parentOkr']->setAchievedValue(0);
            $tOkrArray[0]['parentOkr']->setTargetValue(100);
            $tOkrArray[0]['parentOkr']->setUnit('％');

            // OKRアクティビティ登録
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkrArray[0]['parentOkr']);
            $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ACHIEVEMENT);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
            $tOkrActivity->setAchievementRate($achievementRate);
            $tOkrActivity->setChangedPercentage($achievementRate - $previousAchievementRate);
            $this->persist($tOkrActivity);

            $this->flush();

            // 自動投稿登録（◯%達成時）
            $postLogic->autoPostAboutAchievement($auth, $achievementRate, $previousAchievementRate, $tOkrArray[0]['parentOkr'], $tOkrActivity);

            // 親OKRを$tOkrに代入
            $tOkr = $tOkrArray[0]['parentOkr'];
        }
    }

    /**
     * OKR達成率再計算（再計算対象OKRの親OKRを指定）
     *
     * @param Auth $auth 認証情報
     * @param TOkr $tOkr チェック対象親OKRエンティティ
     * @param boolean $weightedAverageRatioFlg 加重平均比率再設定フラグ
     * @return void
     */
    public function recalculateFromParent(Auth $auth, TOkr $tOkr, bool $weightedAverageRatioFlg = false)
    {
        // OKRが「OKR種別 ！＝ 1:目標」の場合、再計算対象外
        if ($tOkr->getType() !== DBConstant::OKR_TYPE_OBJECTIVE) {
            return;
        }

        // 子OKRを取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getChildrenOkrsForRecalc(
                $tOkr->getOkrId(),
                $tOkr->getTimeframe()->getTimeframeId(),
                $auth->getCompanyId()
                );

        // 子OKRが存在しない場合、OKRの達成率、達成値、目標値、単位をリセット
        if (count($tOkrArray) <= 1) {
            $tOkr->setAchievementRate(0);
            $tOkr->setAchievedValue(0);
            $tOkr->setTargetValue(100);
            $tOkr->setUnit('％');
            $this->flush();
            return;
        }

        $recalcItems = $tOkrRepos->getRecalcItems(
                $tOkr->getOkrId(),
                $tOkr->getTimeframe()->getTimeframeId(),
                $auth->getCompanyId()
                );

        $summedWeightedAverageRatio = $recalcItems[0]['summedWeightedAverageRatio']; // 子OKRの加重平均比率の合計値
        $lockedRatioCount = $recalcItems[0]['lockedRatioCount']; // 子OKRの持分比率ロックフラグが立っている数
        $childrenOkrsCount = count($tOkrArray) - 1; // 子OKR数
        if (($childrenOkrsCount - $lockedRatioCount) !== 0) {
            $averageRatio = floor(((100 - $summedWeightedAverageRatio) / ($childrenOkrsCount - $lockedRatioCount)) * 10 ) / 10; // ロックフラグが立っていないOKRに設定するの加重平均比率
        }

        // 子OKRの加重平均比率を再設定（ $i=0 は 'parentOkr' なので $i=1 から始める）
        $tOkrArrayCount = count($tOkrArray);
        $weightedAverageAchievementRate = array();
        for ($i = 1; $i < $tOkrArrayCount; ++$i) {
            if ($weightedAverageRatioFlg) {
                if ($tOkrArray[$i]['childOkr']->getRatioLockedFlg() == DBConstant::FLG_FALSE) {
                    $tOkrArray[$i]['childOkr']->setWeightedAverageRatio($averageRatio);
                }
            }

            $weightedAverageAchievementRate[] = $tOkrArray[$i]['childOkr']->getAchievementRate() * $tOkrArray[$i]['childOkr']->getWeightedAverageRatio() / 100;
        }

        // 親OKRの前回達成率を取得
        $previousAchievementRate = $tOkrArray[0]['parentOkr']->getAchievementRate();

        // 親OKRの達成率を再設定
        $achievementRate = array_sum($weightedAverageAchievementRate);
        $tOkrArray[0]['parentOkr']->setAchievementRate($achievementRate);

        // 達成値、目標値、単位をリセット
        $tOkrArray[0]['parentOkr']->setAchievedValue(0);
        $tOkrArray[0]['parentOkr']->setTargetValue(100);
        $tOkrArray[0]['parentOkr']->setUnit('％');

        // OKRアクティビティ登録
        $tOkrActivity = new TOkrActivity();
        $tOkrActivity->setOkr($tOkrArray[0]['parentOkr']);
        $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ACHIEVEMENT);
        $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
        $tOkrActivity->setAchievementRate($achievementRate);
        $tOkrActivity->setChangedPercentage($achievementRate - $previousAchievementRate);
        $this->persist($tOkrActivity);

        $this->flush();

        // 自動投稿登録（◯%達成時）
        $postLogic = $this->getPostLogic();
        $postLogic->autoPostAboutAchievement($auth, $achievementRate, $previousAchievementRate, $tOkrArray[0]['parentOkr'], $tOkrActivity);

        $this->recalculate($auth, $tOkr, $auth->getCompanyId(), $weightedAverageRatioFlg);
    }
}
