<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
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
        // タイムフレームIDの一致チェック
        if ($tOkr->getTimeframe()->getTimeframeId() != $newParentOkr->getTimeframe()->getTimeframeId()) {
            throw new ApplicationException('異なるタイムフレームのOKRには紐付けできません');
        }

        // 紐付け先OKRが他のOKRに紐付けられていない場合、紐付け不可
        if (empty($newParentOkr->getParentOkr())) {
            throw new ApplicationException('紐付け先のないOKRには紐付けられません');
        }

        // 同一オーナーのOKRに紐付ける場合、OKR種別を比較し紐付け可能かチェック
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER && $newParentOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            if ($tOkr->getOwnerUser()->getUserId() == $newParentOkr->getOwnerUser()->getUserId()) {
                if (!($newParentOkr->getType() == DBConstant::OKR_TYPE_OBJECTIVE && $tOkr->getType() == DBConstant::OKR_TYPE_KEY_RESULT)) {
                    throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
                }
            } else {
                if ($tOkr->getType() == DBConstant::OKR_TYPE_KEY_RESULT) {
                    throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
                }
            }
        } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP && $newParentOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
            if ($tOkr->getOwnerGroup()->getGroupId() == $newParentOkr->getOwnerGroup()->getGroupId()) {
                if (!($newParentOkr->getType() == DBConstant::OKR_TYPE_OBJECTIVE && $tOkr->getType() == DBConstant::OKR_TYPE_KEY_RESULT)) {
                    throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
                }
            } else {
                if ($tOkr->getType() == DBConstant::OKR_TYPE_KEY_RESULT) {
                    throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
                }
            }
        } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_COMPANY && $newParentOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_COMPANY) {
            if (!($newParentOkr->getType() == DBConstant::OKR_TYPE_OBJECTIVE && $tOkr->getType() == DBConstant::OKR_TYPE_KEY_RESULT)) {
                throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
            }
        } else {
            if ($tOkr->getType() == DBConstant::OKR_TYPE_KEY_RESULT) {
                throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
            }
        }

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
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
