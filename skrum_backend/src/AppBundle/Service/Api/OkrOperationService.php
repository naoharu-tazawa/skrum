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
use AppBundle\Api\ResponseDTO\OkrDetailsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO;

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
    public function changeParent(Auth $auth, TOkr $tOkr, TOkr $newParentOkr): OkrDetailsDTO
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

        // 会社名を取得
        $companyName = null;
        if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY || $newParentOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY) {
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($auth->getCompanyId());
            $companyName = $mCompany->getCompanyName();
        }

        // レスポンス用DTO生成
        $targetOkr = $this->repackDTOWithOkrEntity($tOkr, $companyName);
        $parentOkr = $this->repackDTOWithOkrEntity($newParentOkr, $companyName);
        $okrDetailsDTO = new OkrDetailsDTO();
        $okrDetailsDTO->setObjective($targetOkr);
        $okrDetailsDTO->setParentOkr($parentOkr);

        return $okrDetailsDTO;
    }

    /**
     * OKRエンティティをDTOに詰め替える
     *
     * @param TOkr $tOkr OKRエンティティ
     * @param string $companyName 会社名
     * @return BasicOkrDTO
     */
    private function repackDTOWithOkrEntity(TOkr $tOkr, string $companyName = null): BasicOkrDTO
    {
        $basicOkrDTO = new BasicOkrDTO();
        $basicOkrDTO->setOkrId($tOkr->getOkrId());
        $basicOkrDTO->setOkrType($tOkr->getType());
        $basicOkrDTO->setOkrName($tOkr->getName());
        $basicOkrDTO->setOkrDetail($tOkr->getDetail());
        $basicOkrDTO->setTargetValue($tOkr->getTargetValue());
        $basicOkrDTO->setAchievedValue($tOkr->getAchievedValue());
        $basicOkrDTO->setAchievementRate($tOkr->getAchievementRate());
        $basicOkrDTO->setUnit($tOkr->getUnit());
        $basicOkrDTO->setOwnerType($tOkr->getOwnerType());
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $basicOkrDTO->setOwnerUserId($tOkr->getOwnerUser()->getUserId());
            $basicOkrDTO->setOwnerUserName($tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName());
        } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
            $basicOkrDTO->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
            $basicOkrDTO->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
        } else {
            $basicOkrDTO->setOwnerCompanyId($tOkr->getOwnerCompanyId());
            $basicOkrDTO->setOwnerCompanyName($companyName);
        }
        $basicOkrDTO->setStartDate($tOkr->getStartDate());
        $basicOkrDTO->setEndDate($tOkr->getEndDate());
        $basicOkrDTO->setStatus($tOkr->getStatus());
        $basicOkrDTO->setDisclosureType($tOkr->getDisclosureType());
        $basicOkrDTO->setWeightedAverageRatio($tOkr->getWeightedAverageRatio());
        $basicOkrDTO->setRatioLockedFlg($tOkr->getRatioLockedFlg());

        return $basicOkrDTO;
    }
}
