<?php

namespace AppBundle\Logic;

use AppBundle\Exception\ApplicationException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MCompany;
use AppBundle\Entity\TOkr;
use AppBundle\Api\ResponseDTO\OkrMapDTO;

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
     * OKRエンティティ配列を階層構造に変換
     *
     * @param Auth $auth 認証情報
     * @param array $tOkrArray 操作対象OKRエンティティ配列
     * @param MCompany $mCompany 会社エンティティ
     * @return OkrMapDTO
     */
    public function tree(Auth $auth, array $tOkrArray, MCompany $mCompany = null): OkrMapDTO
    {
        $disclosureLogic = $this->getDisclosureLogic();

        // 親OKRの閲覧権限をチェック
        if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkrArray[0])) {
            return new OkrMapDTO();
        }

        // 親OKRをDTOに詰め替える
        $okrMapDTO = $this->repackDTOWithOkrEntity($tOkrArray[0], $mCompany);

        // 子OKRを同じ親OKRIDで配列にまとめる
        $childrenOkrs = array();
        $count = count($tOkrArray);
        for ($i = 0; $i < $count; ++$i) {
            // 閲覧権限をチェック
            if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkrArray[$i])) {
                continue;
            }

            // DTOに詰め替える
            $childOkrMapDTO = $this->repackDTOWithOkrEntity($tOkrArray[$i], $mCompany);

            if (empty($tOkrArray[$i]->getParentOkr())) {
                $childrenOkrs[][] = $childOkrMapDTO;
            } else {
                $childrenOkrs[$tOkrArray[$i]->getParentOkr()->getOkrId()][] = $childOkrMapDTO;
            }
        }

        // ツリー生成
        $tree = $this->makeTree($childrenOkrs, array($okrMapDTO));

        return $tree[0];
    }

    /**
     * ツリー生成（再帰処理）
     *
     * @param array $childrenOkrs 認証情報
     * @param array $parentOkrMapDTO 親OKRマップDTO
     * @return OkrMapDTO
     */
    private function makeTree(array &$childrenOkrs, array $parentOkrMapDTOArray): array
    {
        $tree = array();
        foreach ($parentOkrMapDTOArray as $key => $value) {
            if (isset($childrenOkrs[$value->getOkrId()])) {
                $value->setChildren($this->makeTree($childrenOkrs, $childrenOkrs[$value->getOkrId()]));
            }
            $tree[] = $value;
        }

        return $tree;
    }

    /**
     * OKRエンティティをDTOに詰め替える
     *
     * @param TOkr $tOkr OKRエンティティ
     * @param MCompany $mCompany 会社エンティティ
     * @return OkrMapDTO
     */
    private function repackDTOWithOkrEntity(TOkr $tOkr, MCompany $mCompany = null): OkrMapDTO
    {
        $okrMapDTO = new OkrMapDTO();
        $okrMapDTO->setOkrId($tOkr->getOkrId());
        $okrMapDTO->setOkrName($tOkr->getName());
        $okrMapDTO->setTargetValue($tOkr->getTargetValue());
        $okrMapDTO->setAchievedValue($tOkr->getAchievedValue());
        $okrMapDTO->setAchievementRate($tOkr->getAchievementRate());
        $okrMapDTO->setUnit($tOkr->getUnit());
        $okrMapDTO->setOwnerType($tOkr->getOwnerType());
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $okrMapDTO->setOwnerUserId($tOkr->getOwnerUser()->getUserId());
            $okrMapDTO->setOwnerUserName($tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName());
            $okrMapDTO->setOwnerUserImageVersion($tOkr->getOwnerUser()->getImageVersion());
        } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
            $okrMapDTO->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
            $okrMapDTO->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
            $okrMapDTO->setOwnerGroupImageVersion($tOkr->getOwnerGroup()->getImageVersion());
        } else {
            $okrMapDTO->setOwnerCompanyId($tOkr->getOwnerCompanyId());
            $okrMapDTO->setOwnerCompanyName($mCompany->getCompanyName());
            $okrMapDTO->setOwnerCompanyImageVersion($mCompany->getImageVersion());
        }
        $okrMapDTO->setStatus($tOkr->getStatus());

        return $okrMapDTO;
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
        $tOkrActivityRepos = $this->getTOkrActivityRepository();

        // 操作対象OKRを削除
        $this->remove($tOkr);

        // 関連するOKRアクティビティ、投稿及びいいねを削除
        $tOkrActivityRepos->deleteRelatedOkrActivitiesAndPostsAndLikes($tOkr->getOkrId());

        while (!empty($parentOkrIdArray)) {
            // 全ての子OKRを取得
            $childrenOkrArray = $tOkrRepos->getAllChildrenOkrsOfMultipleParentOkrs($parentOkrIdArray);

            // 子OKRのOKRIDを配列に格納し、子OKRは削除する
            $parentOkrIdArray = array();
            foreach ($childrenOkrArray as $childOkr) {
                $parentOkrIdArray[] = $childOkr->getOkrId();
                $this->remove($childOkr);

                // 関連するOKRアクティビティ、投稿及びいいねを削除
                $tOkrActivityRepos->deleteRelatedOkrActivitiesAndPostsAndLikes($childOkr->getOkrId());
            }
        }

        $this->flush();
    }
}
