<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkrActivity;
use AppBundle\Entity\TOkr;

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
     * @param Auth $auth 認証情報
     * @param TOkr $tOkr 紐付け先変更対象OKRエンティティ
     * @param TOkr $newParentOkr 紐付け先OKRエンティティ
     * @return void
     */
    public function changeParent(Auth $auth, TOkr $tOkr, TOkr $newParentOkr)
    {
        // 紐付け先変更対象OKRがキーリザルトの場合、紐付け先変更不可
        if ($tOkr->getType() === DBConstant::OKR_TYPE_KEY_RESULT) {
            throw new ApplicationException('キーリザルトは紐付け先変更できません');
        }

        // タイムフレームIDの一致チェック
        if ($tOkr->getTimeframe()->getTimeframeId() != $newParentOkr->getTimeframe()->getTimeframeId()) {
            throw new ApplicationException('異なるタイムフレームのOKRには紐付けできません');
        }

        // 紐付け先チェック
        $userId = null;
        $groupId = null;
        if ($tOkr->getOwnerUser() !== null) $userId = $tOkr->getOwnerUser()->getUserId();
        if ($tOkr->getOwnerGroup() !== null) $groupId = $tOkr->getOwnerGroup()->getGroupId();
        $okrOperationLogic = $this->getOkrOperationLogic();
        $okrOperationLogic->checkAlignment($tOkr->getType(), $tOkr->getOwnerType(), $userId, $groupId, $newParentOkr);

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
                $okrAchievementRateLogic->recalculateFromParent($auth, $currentParentOkr, true);
            }

            // 新紐付け先の達成率を再計算
            $okrAchievementRateLogic->recalculate($auth, $tOkr, true);

            // 入れ子集合モデルの右値と左値を再計算
            $okrNestedIntervalsLogic = $this->getOkrNestedIntervalsLogic();
            $okrNestedIntervalsLogic->recalculate($tOkr, $auth->getCompanyId());

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
