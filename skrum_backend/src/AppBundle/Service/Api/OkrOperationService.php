<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkrActivity;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TTimeframe;
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
     * @param TOkr $newParentOkr 紐付け先OKRエンティティ（紐付け先解除の場合はnull）
     * @return OkrDetailsDTO
     */
    public function changeParent(Auth $auth, TOkr $tOkr, TOkr $newParentOkr = null): OkrDetailsDTO
    {
        // 紐付け先変更対象OKRがキーリザルトの場合、紐付け先変更不可
        if ($tOkr->getType() === DBConstant::OKR_TYPE_KEY_RESULT) {
            throw new ApplicationException('キーリザルトは紐付け先変更できません');
        }

        // タイムフレームIDの一致チェック
        if ($newParentOkr !== null) {
            if ($tOkr->getTimeframe()->getTimeframeId() !== $newParentOkr->getTimeframe()->getTimeframeId()) {
                throw new ApplicationException('異なるタイムフレームのOKRには紐付けできません');
            }
        }

        // 紐付け先チェック
        $userId = null;
        $groupId = null;
        if ($tOkr->getOwnerUser() !== null) $userId = $tOkr->getOwnerUser()->getUserId();
        if ($tOkr->getOwnerGroup() !== null) $groupId = $tOkr->getOwnerGroup()->getGroupId();
        if ($newParentOkr !== null) {
            $okrOperationLogic = $this->getOkrOperationLogic();
            $okrOperationLogic->checkAlignment($tOkr->getType(), $tOkr->getOwnerType(), $userId, $groupId, $newParentOkr);
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

            if ($currentParentOkr === null) {
                /* 新規紐付け */

                // 紐付け先が無い状態で紐付け先解除をしようとした場合、更新処理を行わない
                if ($newParentOkr === null) {
                    $this->rollback();
                    return new OkrDetailsDTO();
                }

                $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ALIGN);
                $tOkrActivity->setNewParentOkrId($newParentOkr->getOkrId());
            } else {
                if ($newParentOkr !== null) {
                    /* 紐付け先変更 */

                    // 現在の紐付け先OKRと変更後紐付け先OKRが同一の場合、更新処理を行わない
                    if ($currentParentOkr->getOkrId() == $newParentOkr->getOkrId()) {
                        $this->rollback();
                        return new OkrDetailsDTO();
                    }

                    $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ALIGN_CHANGE);
                    $tOkrActivity->setPreviousParentOkrId($currentParentOkr->getOkrId());
                    $tOkrActivity->setNewParentOkrId($newParentOkr->getOkrId());
                } else {
                    /* 紐付け先解除 */

                    $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ALIGN_CHANGE);
                    $tOkrActivity->setPreviousParentOkrId($currentParentOkr->getOkrId());
                }
            }

            $this->persist($tOkrActivity);
            $this->flush();

            // 旧紐付け先の達成率を再計算
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            if ($currentParentOkr !== null) {
                $okrAchievementRateLogic->recalculateFromParent($auth, $currentParentOkr, true);
            }

            // 新紐付け先の達成率を再計算
            $okrAchievementRateLogic->recalculate($auth, $tOkr, true);

            // 入れ子集合モデルの右値と左値を再計算
            if ($newParentOkr !== null) {
                $okrNestedIntervalsLogic = $this->getOkrNestedIntervalsLogic();
                $okrNestedIntervalsLogic->recalculate($tOkr, $auth->getCompanyId());
            } else {
                // 紐付け先解除の場合は右値と左値にnullをセット
                $tOkrRepos = $this->getTOkrRepository();
                $tOkrRepos->resetAllLeftRightValues($tOkr->getTreeLeft(), $tOkr->getTreeRight(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // 会社名を取得
        $companyName = null;
        if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY || ($newParentOkr !== null && $newParentOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY)) {
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($auth->getCompanyId());
            $companyName = $mCompany->getCompanyName();
        }

        // レスポンス用DTO生成
        $targetOkr = $this->repackDTOWithOkrEntity($tOkr, $companyName);
        if ($newParentOkr !== null) $parentOkr = $this->repackDTOWithOkrEntity($newParentOkr, $companyName);
        $okrDetailsDTO = new OkrDetailsDTO();
        $okrDetailsDTO->setObjective($targetOkr);
        if ($newParentOkr !== null) $okrDetailsDTO->setParentOkr($parentOkr);

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

    /**
     * OKRコピー
     *
     * @param Auth $auth 認証情報
     * @param TOkr $tOkr コピー対象OKRエンティティ
     * @param TTimeframe $tTimeframe コピー先のタイムフレームエンティティ
     * @return void
     */
    public function cloneOkr(Auth $auth, TOkr $tOkr, TTimeframe $tTimeframe)
    {
        // コピー対象OKRがキーリザルトの場合、コピー不可
        if ($tOkr->getType() === DBConstant::OKR_TYPE_KEY_RESULT) {
            throw new ApplicationException('キーリザルトは直接コピーできません');
        }

        // コピー対象OKRのタイムフレームIDとコピー先のタイムフレームIDが異なることをチェック
        if ($tOkr->getTimeframe()->getTimeframeId() === $tTimeframe->getTimeframeId()) {
            throw new ApplicationException('同一のタイムフレームにはコピーできません');
        }



        // トランザクション開始
        $this->beginTransaction();

        try {
            // 会社のOBJECTIVEを登録する場合、ルートノードが存在するかチェック
            if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY &&
                    $tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE &&
                    $tOkr->getParentOkr()->getType() === DBConstant::OKR_TYPE_ROOT_NODE) {
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
                    $this->persist($okrRootNode);

                    $this->flush();

                    $parentOkrEntity = $okrRootNode;
                }
            }

            // コピー対象のOKRを全て取得
            $tOkrRepos = $this->getTOkrRepository();
            $cloneTargetOkrArray = array($tOkr->getOkrId() => $tOkr);
            $objectiveArray = array($tOkr);
            while (count($objectiveArray) !== 0) {
                $okrEntityArray = array();
                foreach ($objectiveArray as $objectiveEntity) {
                    $tOkrArray = $tOkrRepos->getObjectiveAndKeyResults($objectiveEntity->getOkrId(), $objectiveEntity->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
                    // $i=0は親OKRなので$i=1からカウント開始
                    $count = count($tOkrArray);
                    for ($i = 1; $i < $count; ++$i) {
                        if ($tOkrArray[$i] !== null) {
                            $okrEntityArray[] = $tOkrArray[$i];
                        }
                    }
                }

                $objectiveArray = array();
                foreach ($okrEntityArray as $okrEntity) {
                    $cloneTargetOkrArray[$okrEntity->getOkrId()] = $okrEntity;
                    if ($okrEntity->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
                        $objectiveArray[] = $okrEntity;
                    }
                }
            }

            // コピー対象OKRを別のタイムフレームにコピー
            $k = 0;
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            foreach ($cloneTargetOkrArray as $originalOkrId => $cloneTargetOkr) {
                // コピー元のOKRアクティビティを登録（コピー）
                $tOkrActivity = new TOkrActivity();
                $tOkrActivity->setOkr($cloneTargetOkr);
                $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_CLONE);
                $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
                $tOkrActivity->setPreviousTimeframeId($cloneTargetOkr->getTimeframe()->getTimeframeId());
                $tOkrActivity->setNewTimeframeId($tTimeframe->getTimeframeId());
                $this->persist($tOkrActivity);

                // OKRのコピーを生成
                $clonedOkr = new TOkr();
                $clonedOkr->setTimeframe($tTimeframe);
                if ($tOkr->getParentOkr()->getType() === DBConstant::OKR_TYPE_ROOT_NODE) {
                    if ($k === 0) {
                        $clonedOkr->setParentOkr($parentOkrEntity);
                    } else {
                        $clonedOkr->setParentOkr($cloneTargetOkrArray[$cloneTargetOkr->getParentOkr()->getOkrId()]);
                    }
                } else {
                    if ($k !== 0) {
                        $clonedOkr->setParentOkr($cloneTargetOkrArray[$cloneTargetOkr->getParentOkr()->getOkrId()]);
                    }
                }
                $clonedOkr->setType($cloneTargetOkr->getType());
                $clonedOkr->setName($cloneTargetOkr->getName());
                $clonedOkr->setDetail($cloneTargetOkr->getDetail());
                $clonedOkr->setTargetValue($cloneTargetOkr->getTargetValue());
                $clonedOkr->setAchievementRate(0); // 達成率はリセット
                if ($tOkr->getParentOkr()->getType() === DBConstant::OKR_TYPE_ROOT_NODE) {
                    // 入れ子区間モデルの左値と右値を取得
                    if ($k === 0) {
                        $treeValues = $this->getLeftRightValues($parentOkrEntity->getOkrId(), $tTimeframe->getTimeframeId());
                    } else {
                        $treeValues = $this->getLeftRightValues($cloneTargetOkrArray[$cloneTargetOkr->getParentOkr()->getOkrId()]->getOkrId(), $tTimeframe->getTimeframeId());
                    }
                    $clonedOkr->setTreeLeft($treeValues['tree_left']);
                    $clonedOkr->setTreeRight($treeValues['tree_right']);
                }
                $clonedOkr->setUnit($cloneTargetOkr->getUnit());
                $clonedOkr->setOwnerType($cloneTargetOkr->getOwnerType());
                if ($cloneTargetOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                    $clonedOkr->setOwnerUser($cloneTargetOkr->getOwnerUser());
                } elseif ($cloneTargetOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                    $clonedOkr->setOwnerGroup($cloneTargetOkr->getOwnerGroup());
                } else {
                    $clonedOkr->setOwnerCompanyId($cloneTargetOkr->getOwnerCompanyId());
                }
                $clonedOkr->setStartDate($tTimeframe->getStartDate());
                $clonedOkr->setEndDate($tTimeframe->getEndDate());
                $clonedOkr->setStatus($cloneTargetOkr->getStatus());
                $clonedOkr->setDisclosureType($cloneTargetOkr->getDisclosureType());
                if ($k !== 0) $clonedOkr->setWeightedAverageRatio($cloneTargetOkr->getWeightedAverageRatio());
                if ($k !== 0) $clonedOkr->setRatioLockedFlg($cloneTargetOkr->getRatioLockedFlg());

                $this->persist($clonedOkr);

                // OKRアクティビティ登録（作成）
                $tOkrActivity = new TOkrActivity();
                $tOkrActivity->setOkr($clonedOkr);
                $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_GENERATE);
                $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
                $tOkrActivity->setTargetValue($cloneTargetOkr->getTargetValue());
                $tOkrActivity->setAchievedValue(0);
                $tOkrActivity->setAchievementRate(0);
                $tOkrActivity->setChangedPercentage(0);
                $this->persist($tOkrActivity);

                $this->flush();

                // 加重平均比率を再計算
                if ($k !== 0) $okrAchievementRateLogic->recalculate($auth, $clonedOkr, true);

                $cloneTargetOkrArray[$originalOkrId] = $clonedOkr;
                ++$k;
            }

            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * ノードの左値・右値を取得する際に最左ノードまたは最右ノードをランダムに取得
     *
     * @param integer $parentOkrId 親OKRID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    private function getLeftRightValues(int $parentOkrId, int $timeframeId)
    {
        // 1または2をランダムに取得
        $rand = mt_rand(1, 2);
        $tOkrRepos = $this->getTOkrRepository();

        if ($rand === 1) {
            return $tOkrRepos->getLeftRightOfLeftestInsertionNode($parentOkrId, $timeframeId);
        } else {
            return $tOkrRepos->getLeftRightOfRightestInsertionNode($parentOkrId, $timeframeId);
        }
    }
}
