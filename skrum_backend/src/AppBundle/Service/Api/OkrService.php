<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TOkr;
use AppBundle\Utils\DBConstant;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DateUtility;
use AppBundle\Entity\TOkrActivity;

/**
 * OKRサービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrService extends BaseService
{
    /**
     * OKR新規登録
     *
     * @param string $ownerType OKRオーナー種別
     * @param array $data リクエストJSON連想配列
     * @param \AppBundle\Entity\TTimeframe $tTimeframe タイムフレームエンティティ
     * @param \AppBundle\Entity\MUser $mUser オーナーユーザエンティティ
     * @param \AppBundle\Entity\MGroup $mGroup オーナーグループエンティティ
     * @param integer $companyId オーナー会社ID
     * @param boolean $alignmentFlg 紐付け先OKR有りフラグ
     * @param \AppBundle\Entity\TOkr $parentOkrEntity 紐付け先OKRエンティティ
     * @return void
     */
    public function createOkr($ownerType, $data, $tTimeframe, $mUser, $mGroup, $companyId, $alignmentFlg, $parentOkrEntity)
    {
        if ($alignmentFlg) {
            // 紐付け先OKRが他のOKRに紐付けられていない場合、紐付け不可
            if (empty($parentOkrEntity->getParentOkr())) {
                throw new ApplicationException('紐付け先のないOKRには紐付けられません');
            }

            // 同一オーナーのOKRに紐付ける場合、OKR種別を比較し紐付け可能かチェック
            if ($ownerType == DBConstant::OKR_OWNER_TYPE_USER && $parentOkrEntity->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
                if ($mUser->getUserId() == $parentOkrEntity->getOwnerUser()->getUserId()) {
                    if (!($parentOkrEntity->getType() == DBConstant::OKR_TYPE_OBJECTIVE && $data['okrType'] == DBConstant::OKR_TYPE_KEY_RESULT)) {
                        throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
                    }
                } else {
                    if ($data['okrType'] == DBConstant::OKR_TYPE_KEY_RESULT) {
                        throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
                    }
                }
            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_GROUP && $parentOkrEntity->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                if ($mGroup->getGroupId() == $parentOkrEntity->getOwnerGroup()->getGroupId()) {
                    if (!($parentOkrEntity->getType() == DBConstant::OKR_TYPE_OBJECTIVE && $data['okrType'] == DBConstant::OKR_TYPE_KEY_RESULT)) {
                        throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
                    }
                } else {
                    if ($data['okrType'] == DBConstant::OKR_TYPE_KEY_RESULT) {
                        throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
                    }
                }
            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_COMPANY && $parentOkrEntity->getOwnerType() == DBConstant::OKR_OWNER_TYPE_COMPANY) {
                if (!($parentOkrEntity->getType() == DBConstant::OKR_TYPE_OBJECTIVE && $data['okrType'] == DBConstant::OKR_TYPE_KEY_RESULT)) {
                    throw new ApplicationException('同一オーナーのOKRに紐づける場合、目標に対してキーリザルトを紐づけるパターンしかありません');
                }
            } else {
                if ($data['okrType'] == DBConstant::OKR_TYPE_KEY_RESULT) {
                    throw new ApplicationException('異なるオーナーのOKRに紐づける場合、キーリザルトは紐付けできません');
                }
            }

            // 入れ子区間モデルの左値と右値を取得
            $tOkrRepos = $this->getTOkrRepository();
            $treeValues = $tOkrRepos->getLeftRightOfInsertionNode($parentOkrEntity->getOkrId());
        } else {
            // 会社のOBJECTIVEを登録する場合、ルートノードが存在するかチェック
            if ($ownerType == DBConstant::OKR_OWNER_TYPE_COMPANY && $data['okrType'] == DBConstant::OKR_TYPE_OBJECTIVE) {
                // ルートノードが存在するかチェック
                $tOkrRepos = $this->getTOkrRepository();
                $parentOkrEntity = $tOkrRepos->getRootNode($tTimeframe->getTimeframeId());
                if ($parentOkrEntity === null) {
                    // ルートノードが存在しない場合、ルートノードを新規追加
                    $okrRootNode = new TOkr();
                    $okrRootNode->setTimeframe($tTimeframe);
                    $okrRootNode->setType(DBConstant::OKR_TYPE_ROOT_NODE);
                    $okrRootNode->setName('ROOT_NODE');
                    $okrRootNode->setAchievementRate(0);
                    $okrRootNode->setTreeLeft(Constant::ROOT_NODE_LEFT_VALUE);
                    $okrRootNode->setTreeRight(Constant::ROOT_NODE_RIGHT_VALUE);
                    $okrRootNode->setOwnerType(DBConstant::OKR_OWNER_TYPE_ROOT);
                    $okrRootNode->setStatus(DBConstant::OKR_STATUS_OPEN);
                    $okrRootNode->setDisclosureType(DBConstant::OKR_DISCLOSURE_TYPE_OVERALL);
                    try {
                        $this->persist($okrRootNode);
                        $this->flush();
                    } catch(\Exception $e) {
                        throw new SystemException($e->getMessage());
                    }
                    $parentOkrEntity = $okrRootNode;
                }

                // 紐付けフラグを立てる
                $alignmentFlg = true;

                // 入れ子区間モデルの左値と右値を取得
                $tOkrRepos = $this->getTOkrRepository();
                $treeValues = $tOkrRepos->getLeftRightOfInsertionNode($parentOkrEntity->getOkrId(), $tTimeframe->getTimeframeId());
            }
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            // OKR登録
            $tOkr = new TOkr();
            $tOkr->setTimeframe($tTimeframe);
            if ($alignmentFlg) {
                $tOkr->setParentOkr($parentOkrEntity);
                $tOkr->setTreeLeft($treeValues['tree_left']);
                $tOkr->setTreeRight($treeValues['tree_right']);
            }
            $tOkr->setType($data['okrType']);
            $tOkr->setName($data['okrName']);
            $tOkr->setDetail($data['okrDetail']);
            $tOkr->setTargetValue($data['targetValue']);
            $tOkr->setAchievedValue(0);
            $tOkr->setAchievementRate(0);
            $tOkr->setUnit($data['unit']);
            $tOkr->setOwnerType($ownerType);
            if ($ownerType == DBConstant::OKR_OWNER_TYPE_USER) {
                $tOkr->setOwnerUser($mUser);
            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_GROUP) {
                $tOkr->setOwnerGroup($mGroup);
            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_COMPANY) {
                $tOkr->setOwnerCompanyId($companyId);
            }
            $tOkr->setStartDate(DateUtility::transIntoDatetime($data['startDate']));
            $tOkr->setEndDate(DateUtility::transIntoDatetime($data['endDate']));
            $tOkr->setStatus(DBConstant::OKR_STATUS_OPEN);
            $tOkr->setDisclosureType($data['disclosureType']);
            if ($data['okrType'] == DBConstant::OKR_TYPE_KEY_RESULT) $tOkr->setRatioLockedFlg(DBConstant::FLG_FALSE);
            $this->persist($tOkr);

            // OKRアクティビティ登録
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkr);
            $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_GENERATE);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
            $tOkrActivity->setTargetValue($data['targetValue']);
            $tOkrActivity->setAchievedValue(0);
            $tOkrActivity->setAchievementRate(0);
            $tOkrActivity->setChangedPercentage(0);
            $this->persist($tOkrActivity);

            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
