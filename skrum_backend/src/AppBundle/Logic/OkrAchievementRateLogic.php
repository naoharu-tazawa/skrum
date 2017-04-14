<?php

namespace AppBundle\Logic;

use AppBundle\Utils\DBConstant;

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
     * @param \AppBundle\Entity\TOkr $tOkr チェック対象OKRエンティティ
     * @parame integer $companyId 会社ID
     * @parame boolean $weightedAverageRatioFlg 加重平均比率再設定フラグ
     * @return void
     */
    public function recalculate($tOkr, $companyId, $weightedAverageRatioFlg = false)
    {
        while (!empty($tOkr)) {
            // 親OKR存在チェック
            if (empty($tOkr->getParentOkr())) {
                return;
            }

            // 親OKRが「OKR種別 ！＝ 1:目標」の場合、再計算対象外
            if ($tOkr->getParentOkr()->getType() != DBConstant::OKR_TYPE_OBJECTIVE) {
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
            for ($i = 1; $i < count($tOkrArray); $i++) {
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
            $tOkrArray[0]['parentOkr']->setTargetValue(0);
            $tOkrArray[0]['parentOkr']->setUnit('％');

            $this->flush();

            // 親OKRを$tOkrに代入
            $tOkr = $tOkrArray[0]['parentOkr'];
        }
    }
}
