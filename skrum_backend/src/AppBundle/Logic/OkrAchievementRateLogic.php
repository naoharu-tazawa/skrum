<?php

namespace AppBundle\Logic;

use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;

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
     * @param TOkr $tOkr チェック対象OKRエンティティ
     * @param integer $companyId 会社ID
     * @param boolean $weightedAverageRatioFlg 加重平均比率再設定フラグ
     * @return void
     */
    public function recalculate(TOkr $tOkr, int $companyId, bool $weightedAverageRatioFlg = false)
    {
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
            $tOkrRepos = $this->getTOkrRepository();
            $tOkrArray = $tOkrRepos->getChildrenOkrsForRecalc(
                    $tOkr->getParentOkr()->getOkrId(),
                    $tOkr->getTimeframe()->getTimeframeId(),
                    $companyId
                    );

            $recalcItems = $tOkrRepos->getRecalcItems(
                    $tOkr->getParentOkr()->getOkrId(),
                    $tOkr->getTimeframe()->getTimeframeId(),
                    $companyId
                    );

            $summedWeightedAverageRatio = $recalcItems[0]['summedWeightedAverageRatio']; // 子OKRの加重平均比率の合計値
            $lockedRatioCount = $recalcItems[0]['lockedRatioCount']; // 子OKRの持分比率ロックフラグが立っている数
            $childrenOkrsCount = count($tOkrArray) - 1; // 子OKR数
            $averageRatio = floor(((100 - $summedWeightedAverageRatio) / ($childrenOkrsCount - $lockedRatioCount)) * 10 ) / 10; // ロックフラグが立っていないOKRに設定するの加重平均比率

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

            // 親OKRの達成率を再設定
            $tOkrArray[0]['parentOkr']->setAchievementRate(array_sum($weightedAverageAchievementRate));

            // 達成値、目標値、単位をリセット
            $tOkrArray[0]['parentOkr']->setAchievedValue(0);
            $tOkrArray[0]['parentOkr']->setTargetValue(100);
            $tOkrArray[0]['parentOkr']->setUnit('％');

            $this->flush();

            // 親OKRを$tOkrに代入
            $tOkr = $tOkrArray[0]['parentOkr'];
        }
    }

    /**
     * OKR達成率再計算（再計算対象OKRの親OKRを指定）
     *
     * @param TOkr $tOkr チェック対象親OKRエンティティ
     * @param integer $companyId 会社ID
     * @param boolean $weightedAverageRatioFlg 加重平均比率再設定フラグ
     * @return void
     */
    public function recalculateFromParent(TOkr $tOkr, int $companyId, bool $weightedAverageRatioFlg = false)
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
                $companyId
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
                $companyId
                );

        $summedWeightedAverageRatio = $recalcItems[0]['summedWeightedAverageRatio']; // 子OKRの加重平均比率の合計値
        $lockedRatioCount = $recalcItems[0]['lockedRatioCount']; // 子OKRの持分比率ロックフラグが立っている数
        $childrenOkrsCount = count($tOkrArray) - 1; // 子OKR数
        $averageRatio = floor(((100 - $summedWeightedAverageRatio) / ($childrenOkrsCount - $lockedRatioCount)) * 10 ) / 10; // ロックフラグが立っていないOKRに設定するの加重平均比率

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

        // 親OKRの達成率を再設定
        $tOkrArray[0]['parentOkr']->setAchievementRate(array_sum($weightedAverageAchievementRate));

        // 達成値、目標値、単位をリセット
        $tOkrArray[0]['parentOkr']->setAchievedValue(0);
        $tOkrArray[0]['parentOkr']->setTargetValue(100);
        $tOkrArray[0]['parentOkr']->setUnit('％');

        $this->flush();

        $this->recalculate($tOkr, $companyId, $weightedAverageRatioFlg);
    }
}
