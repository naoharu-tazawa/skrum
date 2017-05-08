<?php

namespace AppBundle\Logic;

use AppBundle\Exception\ApplicationException;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;

/**
 * OKR操作ロジッククラス
 *
 * @author naoharu.tazawa
 */
class OkrOperationLogic extends BaseLogic
{
    /**
     * 紐付け先チェック
     *
     * @param string $okrType 操作対象OKRのOKR種別
     * @param string $ownerType 操作対象OKRのオーナー種別
     * @param integer $userId 操作対象OKRのオーナーユーザのユーザID
     * @param integer $groupId 操作対象OKRのオーナーグループのグループID
     * @param TOkr $parentOkrEntity 紐付け先OKRエンティティ
     * @return void
     */
    public function checkAlignment(string $okrType, string $ownerType, int $userId = null, int $groupId = null, TOkr $parentOkrEntity)
    {
        // OKR種別を比較し紐付け可能かチェック
        if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER && $parentOkrEntity->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
            if ($userId === $parentOkrEntity->getOwnerUser()->getUserId()) {
                if (!($parentOkrEntity->getType() === DBConstant::OKR_TYPE_OBJECTIVE && $okrType === DBConstant::OKR_TYPE_KEY_RESULT)) {
                    throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
                }
            } else {
                if ($okrType === DBConstant::OKR_TYPE_KEY_RESULT) {
                    throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
                }
            }
        } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP && $parentOkrEntity->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
            if ($groupId === $parentOkrEntity->getOwnerGroup()->getGroupId()) {
                if (!($parentOkrEntity->getType() === DBConstant::OKR_TYPE_OBJECTIVE && $okrType === DBConstant::OKR_TYPE_KEY_RESULT)) {
                    throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
                }
            } else {
                if ($okrType === DBConstant::OKR_TYPE_KEY_RESULT) {
                    throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
                }
            }
        } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY && $parentOkrEntity->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY) {
            if (!($parentOkrEntity->getType() === DBConstant::OKR_TYPE_OBJECTIVE && $okrType === DBConstant::OKR_TYPE_KEY_RESULT)) {
                throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
            }
        } else {
            if ($okrType === DBConstant::OKR_TYPE_KEY_RESULT) {
                throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
            }
        }
    }

    /**
     * 指定OKRとそれに紐づくOKRを全て削除（ループ処理）
     *
     * @param TOkr $tOkr 操作対象OKRエンティティ
     * @return void
     */
    public function deleteOkrAndAllAlignmentOkrs(TOkr $tOkr)
    {
        $parentOkrIdArray = array($tOkr->getOkrId());
        $tOkrRepos = $this->getTOkrRepository();

        // 捜査対象OKRを削除
        $this->remove($tOkr);

        while (!empty($parentOkrIdArray)) {
            // 全ての子OKRを取得
            $childrenOkrArray = $tOkrRepos->getAllChildrenOkrsOfMultipleParentOkrs($parentOkrIdArray);

            // 子OKRのOKRIDを配列に格納し、子OKRは削除する
            $parentOkrIdArray = array();
            foreach ($childrenOkrArray as $childOkr) {
                $parentOkrIdArray[] = $childOkr->getOkrId();
                $this->remove($childOkr);
            }
        }

        $this->flush();
    }
}
