<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkrActivity;

/**
 * OKR操作サービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrOperationService extends BaseService
{
    /**
     * 紐付け先OKR変更
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param \AppBundle\Entity\TOkr $tOkr 紐付け先変更対象OKRエンティティ
     * @param \AppBundle\Entity\TOkr $newParentOkr 紐付け先OKRエンティティ
     * @return void
     */
    public function changeParent($auth, $tOkr, $newParentOkr)
    {
        // 現在の紐付け先OKRを取得
        $currentParentOkr = $tOkr->getParentOkr();

        // トランザクション開始
        $this->beginTransaction();

        try {
            // 紐付け先OKR更新
            $tOkr->setParentOkr($newParentOkr);

            // OKRアクティビティ登録（紐付け変更）
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkr);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());

            if ($currentParentOkr == null) {
                /* 新規紐付け */

                $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ALIGN);
                $tOkrActivity->setNewParentOkrId($newParentOkr->getOkrId());
            } else {
                /* 紐付け先変更 */

                // 現在の紐付け先OKRと変更後紐付け先OKRが同一の場合、更新処理を行わない
                if ($currentParentOkr->getOkrId() == $newParentOkr->getOkrId()) {
                    $this->rollback();
                    return;
                }

                $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ALIGN_CHANGE);
                $tOkrActivity->setPreviousParentOkrId($currentParentOkr->getOkrId());
                $tOkrActivity->setNewParentOkrId($newParentOkr->getOkrId());
            }

            $this->persist($tOkrActivity);
            $this->flush();

            // 旧紐付け先の達成率を再計算
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            if ($currentParentOkr != null) {
                $okrAchievementRateLogic->recalculateFromParent($currentParentOkr, $auth->getCompanyId(), true);
            }

            // 新紐付け先の達成率を再計算
            $okrAchievementRateLogic->recalculate($tOkr, $auth->getCompanyId(), true);

            // 入れ子集合モデルの右値と左値を再計算
            $okrNestedIntervalsLogic = $this->getOkrNestedIntervalsLogic();
            $okrNestedIntervalsLogic->recalculate($tOkr, $auth->getCompanyId());

            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
