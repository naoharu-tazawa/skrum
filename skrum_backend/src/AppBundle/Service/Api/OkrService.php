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
use AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupAlignmentsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\AlignmentsInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\UserAlignmentsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\CompanyAlignmentsDTO;
use AppBundle\Entity\TPost;

/**
 * OKRサービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrService extends BaseService
{
    /**
     * 目標とキーリザルトの一覧を取得
     *
     * @param string $subjectType 主体種別
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param integer $userId 取得対象ユーザID
     * @param integer $groupId 取得対象グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getObjectivesAndKeyResults($subjectType, $auth, $userId, $groupId, $timeframeId, $companyId)
    {
        // 目標とキーリザルトを取得
        $tOkrRepos = $this->getTOkrRepository();
        if ($subjectType == Constant::SUBJECT_TYPE_USER) {
            $tOkrArray = $tOkrRepos->getUserObjectivesAndKeyResults($userId, $timeframeId, $companyId);
        } elseif ($subjectType == Constant::SUBJECT_TYPE_GROUP) {
            $tOkrArray = $tOkrRepos->getGroupObjectivesAndKeyResults($groupId, $timeframeId, $companyId);
        } else {
            $tOkrArray = $tOkrRepos->getCompanyObjectivesAndKeyResults($companyId, $timeframeId);

            // 会社エンティティを取得
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($companyId);
        }

        $okrDisclosureLogic = $this->getOkrDisclosureLogic();
        $returnArray = array();
        $flg = false;
        for ($i = 0; $i < count($tOkrArray); $i++) {
            if (array_key_exists('objective', $tOkrArray[$i])) {
                if ($flg == true) {
                    $basicOkrDTOObjective->setKeyResults($basicOkrDTOKeyResultArray);
                    $returnArray[] = $basicOkrDTOObjective;
                } else {
                    $returnArray[] = $basicOkrDTOObjective;
                }

                // 閲覧権限をチェック
                if (!$okrDisclosureLogic->checkDisclosure($auth->getUserId(), $auth->getRoleLevel(), $tOkrArray[$i]['objective'])) {
                    // 最終ループ
                    if ($i == (count($tOkrArray) - 1)) {
                        $basicOkrDTOObjective->setKeyResults($basicOkrDTOKeyResultArray);
                        $returnArray[] = $basicOkrDTOObjective;
                    }
                    $flg = false;
                    continue;
                }

                $basicOkrDTOObjective = new BasicOkrDTO();
                $basicOkrDTOObjective->setOkrId($tOkrArray[$i]['objective']->getOkrId());
                $basicOkrDTOObjective->setOkrName($tOkrArray[$i]['objective']->getName());
                if ($tOkrArray[$i]['objective']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
                    $basicOkrDTOObjective->setOwnerUserId($tOkrArray[$i]['objective']->getOwnerUser()->getUserId());
                    $basicOkrDTOObjective->setOwnerUserName($tOkrArray[$i]['objective']->getOwnerUser()->getLastName() . ' ' . $tOkrArray[$i]['objective']->getOwnerUser()->getFirstName());
                } elseif ($tOkrArray[$i]['objective']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                    $basicOkrDTOObjective->setOwnerGroupId($tOkrArray[$i]['objective']->getOwnerGroup()->getGroupId());
                    $basicOkrDTOObjective->setOwnerGroupName($tOkrArray[$i]['objective']->getOwnerGroup()->getGroupName());
                } else {
                    $basicOkrDTOObjective->setOwnerCompanyId($tOkrArray[$i]['objective']->getOwnerCompanyId());
                    $basicOkrDTOObjective->setOwnerCompanyName($mCompany->getCompanyName());
                }
                $basicOkrDTOObjective->setTargetValue($tOkrArray[$i]['objective']->getTargetValue());
                $basicOkrDTOObjective->setAchievedValue($tOkrArray[$i]['objective']->getAchievedValue());
                $basicOkrDTOObjective->setUnit($tOkrArray[$i]['objective']->getUnit());
                $basicOkrDTOObjective->setAchievementRate($tOkrArray[$i]['objective']->getAchievementRate());
                $basicOkrDTOObjective->setStatus($tOkrArray[$i]['objective']->getStatus());

                $basicOkrDTOKeyResultArray = array();
                $flg = false;
            } else {
                // キーリザルトがnullの場合、スキップ
                if ($tOkrArray[$i]['keyResult'] == null) {
                    // 最終ループ
                    if ($i == (count($tOkrArray) - 1)) {
                        $returnArray[] = $basicOkrDTOObjective;
                    }
                    continue;
                }

                // 閲覧権限をチェック
                if (!$okrDisclosureLogic->checkDisclosure($auth->getUserId(), $auth->getRoleLevel(), $tOkrArray[$i]['keyResult'])) {
                    // 最終ループ
                    if ($i == (count($tOkrArray) - 1)) {
                        $basicOkrDTOObjective->setKeyResults($basicOkrDTOKeyResultArray);
                        $returnArray[] = $basicOkrDTOObjective;
                    }
                    $flg = true;
                    continue;
                }

                $basicOkrDTOKeyResult = new BasicOkrDTO();
                $basicOkrDTOKeyResult->setOkrId($tOkrArray[$i]['keyResult']->getOkrId());
                $basicOkrDTOKeyResult->setOkrName($tOkrArray[$i]['keyResult']->getName());
                $basicOkrDTOKeyResult->setTargetValue($tOkrArray[$i]['keyResult']->getTargetValue());
                $basicOkrDTOKeyResult->setAchievedValue($tOkrArray[$i]['keyResult']->getAchievedValue());
                $basicOkrDTOKeyResult->setUnit($tOkrArray[$i]['keyResult']->getUnit());
                $basicOkrDTOKeyResult->setAchievementRate($tOkrArray[$i]['keyResult']->getAchievementRate());
                $basicOkrDTOKeyResult->setOwnerType($tOkrArray[$i]['keyResult']->getOwnerType());
                if ($tOkrArray[$i]['keyResult']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
                    $basicOkrDTOKeyResult->setOwnerUserId($tOkrArray[$i]['keyResult']->getOwnerUser()->getUserId());
                    $basicOkrDTOKeyResult->setOwnerUserName($tOkrArray[$i]['keyResult']->getOwnerUser()->getLastName() . ' ' . $tOkrArray[$i]['keyResult']->getOwnerUser()->getFirstName());
                } elseif ($tOkrArray[$i]['keyResult']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                    $basicOkrDTOKeyResult->setOwnerGroupId($tOkrArray[$i]['keyResult']->getOwnerGroup()->getGroupId());
                    $basicOkrDTOKeyResult->setOwnerGroupName($tOkrArray[$i]['keyResult']->getOwnerGroup()->getGroupName());
                } else {
                    $basicOkrDTOKeyResult->setOwnerCompanyId($tOkrArray[$i]['keyResult']->getOwnerCompanyId());
                    $basicOkrDTOKeyResult->setOwnerCompanyName($tOkrArray[$i]['companyName']);
                }
                $basicOkrDTOKeyResult->setStatus($tOkrArray[$i]['keyResult']->getStatus());
                $basicOkrDTOKeyResult->setWeightedAverageRatio($tOkrArray[$i]['keyResult']->getWeightedAverageRatio());
                $basicOkrDTOKeyResult->setRatioLockedFlg($tOkrArray[$i]['keyResult']->getRatioLockedFlg());

                $basicOkrDTOKeyResultArray[] = $basicOkrDTOKeyResult;
                $flg = true;
            }

            // 最終ループ
            if ($i == (count($tOkrArray) - 1)) {
                if ($flg) {
                    $basicOkrDTOObjective->setKeyResults($basicOkrDTOKeyResultArray);
                }
                $returnArray[] = $basicOkrDTOObjective;
            }
        }

        return $returnArray;
    }

    /**
     * 目標紐付け先情報取得
     *
     * @param string $subjectType 主体種別
     * @param integer $userId ユーザID
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getAlignmentsInfo($subjectType, $userId, $groupId, $timeframeId, $companyId)
    {
        $alignmentsInfoDTOArray = array();
        $tOkrRepos = $this->getTOkrRepository();

        // 目標紐付け先情報を取得
        if ($subjectType == Constant::SUBJECT_TYPE_USER) {
            /* ユーザの場合 */
            $userAlignmentsInfoArray = $tOkrRepos->getUserAlignmentsInfoForUser($userId, $timeframeId, $companyId);
            $groupAlignmentsInfoArray = $tOkrRepos->getGroupAlignmentsInfoForUser($userId, $timeframeId, $companyId);
            $companyAlignmentsInfoArray = $tOkrRepos->getCompanyAlignmentsInfoForUser($userId, $timeframeId, $companyId);
        } else {
            /* グループの場合 */
            $userAlignmentsInfoArray = $tOkrRepos->getUserAlignmentsInfoForGroup($groupId, $timeframeId, $companyId);
            $groupAlignmentsInfoArray = $tOkrRepos->getGroupAlignmentsInfoForGroup($groupId, $timeframeId, $companyId);
            $companyAlignmentsInfoArray = $tOkrRepos->getCompanyAlignmentsInfoForGroup($groupId, $timeframeId, $companyId);
        }

        // 目標紐付け先（ユーザ）情報をDTOに詰め替える
        foreach ($userAlignmentsInfoArray as $userAlignmentsInfo) {
            $userAlignmentsDTO = new UserAlignmentsDTO();
            $userAlignmentsDTO->setUserId($userAlignmentsInfo['userId']);
            $userAlignmentsDTO->setName($userAlignmentsInfo['lastName'] . ' ' . $userAlignmentsInfo['firstName']);
            $userAlignmentsDTO->setNumberOfOkrs($userAlignmentsInfo['numberOfOkrs']);

            $alignmentsInfoDTO = new AlignmentsInfoDTO();
            $alignmentsInfoDTO->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
            $alignmentsInfoDTO->setUser($userAlignmentsDTO);

            $alignmentsInfoDTOArray[] = $alignmentsInfoDTO;
        }

        // 目標紐付け先（グループ）情報をDTOに詰め替える
        foreach ($groupAlignmentsInfoArray as $groupAlignmentsInfo) {
            $groupAlignmentsDTO = new GroupAlignmentsDTO();
            $groupAlignmentsDTO->setGroupId($groupAlignmentsInfo['groupId']);
            $groupAlignmentsDTO->setName($groupAlignmentsInfo['groupName']);
            $groupAlignmentsDTO->setNumberOfOkrs($groupAlignmentsInfo['numberOfOkrs']);

            $alignmentsInfoDTO = new AlignmentsInfoDTO();
            $alignmentsInfoDTO->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
            $alignmentsInfoDTO->setGroup($groupAlignmentsDTO);

            $alignmentsInfoDTOArray[] = $alignmentsInfoDTO;
        }

        // 目標紐付け先（会社）情報をDTOに詰め替える
        foreach ($companyAlignmentsInfoArray as $companyAlignmentsInfo) {
            $companyAlignmentsDTO = new CompanyAlignmentsDTO();
            $companyAlignmentsDTO->setCompanyId($companyAlignmentsInfo['companyId']);
            $companyAlignmentsDTO->setName($companyAlignmentsInfo['companyName']);
            $companyAlignmentsDTO->setNumberOfOkrs($companyAlignmentsInfo['numberOfOkrs']);

            $alignmentsInfoDTO = new AlignmentsInfoDTO();
            $alignmentsInfoDTO->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
            $alignmentsInfoDTO->setCompany($companyAlignmentsDTO);

            $alignmentsInfoDTOArray[] = $alignmentsInfoDTO;
        }

        return $alignmentsInfoDTOArray;
    }

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
            $treeValues = $this->getLeftRightValues($parentOkrEntity->getOkrId(), $tTimeframe->getTimeframeId());
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
                $treeValues = $this->getLeftRightValues($parentOkrEntity->getOkrId(), $tTimeframe->getTimeframeId());
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

            // 達成率を再計算
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            $okrAchievementRateLogic->recalculate($tOkr, $companyId);

            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * ノードの左値・右値を取得する際に最左ノードまたは最右ノードをランダムに取得
     *
     * @param integer $parentOkrId 親OKRID
     * @return void
     */
    private function getLeftRightValues($parentOkrId, $timeframeId)
    {
        // 1または2をランダムに取得
        $rand = mt_rand(1, 2);
        $tOkrRepos = $this->getTOkrRepository();

        if ($rand == 1) {
            return $tOkrRepos->getLeftRightOfLeftestInsertionNode($parentOkrId, $timeframeId);
        } else {
            return $tOkrRepos->getLeftRightOfRightestInsertionNode($parentOkrId, $timeframeId);
        }
    }

    /**
     * OKR更新
     *
     * @param array $data リクエストJSON連想配列
     * @param \AppBundle\Entity\TOkr $tOkr OKRエンティティ
     * @return void
     */
    public function changeOkrInfo($data, $tOkr)
    {
        // OKR更新
        $tOkr->setName($data['okrName']);
        $tOkr->setDetail($data['okrDetail']);
        $tOkr->setStartDate(DateUtility::transIntoDatetime($data['startDate']));
        $tOkr->setEndDate(DateUtility::transIntoDatetime($data['endDate']));

        try {
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKR進捗登録
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param \AppBundle\Entity\TOkr $tOkr OKRエンティティ
     * @return void
     */
    public function registerAchievement($auth, $data, $tOkr)
    {
        // 投稿ありの場合、投稿先グループを取得
        $groupIdArray = array();
        if (!empty($data['post'])) {
            // 進捗登録対象OKRのオーナーがグループの場合、投稿先グループに入れる
            if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                $groupIdArray[] = $tOkr->getOwnerGroup()->getGroupId();
            }

            // 親OKRまたは祖父母OKRのオーナーがグループの場合、そのグループを投稿先グループに入れる
            $tOkrRepos = $this->getTOkrRepository();
            $parentAndGrandParentOkr = $tOkrRepos->getParentOkr($tOkr->getParentOkr()->getOkrId(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
            if ($tOkr->getType() == DBConstant::OKR_TYPE_OBJECTIVE) {
                if (!empty($parentAndGrandParentOkr[0]['childOkr'])) {
                    if ($parentAndGrandParentOkr[0]['childOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                        $groupIdArray[] = $parentAndGrandParentOkr[0]['childOkr']->getOwnerGroup()->getGroupId();
                    }
                }
            } else {
                if (!empty($parentAndGrandParentOkr[1]['parentOkr'])) {
                    if ($parentAndGrandParentOkr[1]['parentOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                        $groupIdArray[] = $parentAndGrandParentOkr[1]['parentOkr']->getOwnerGroup()->getGroupId();
                    }
                }
            }

            // 二重投稿を防ぐ
            if (count($groupIdArray) == 2) {
                if ($groupIdArray[0] == $groupIdArray[1]) {
                    unset($groupIdArray[1]);
                }
            }
        }

        // 前回進捗登録時の達成率を取得
        $previousAchievementRate = $tOkr->getAchievementRate();

        // 今回の達成率を計算
        $achievementRate = floor(($data['achievedValue'] / $data['targetValue']) * 1000) / 10;

        // トランザクション開始
        $this->beginTransaction();

        try {
            // OKR進捗登録
            $tOkr->setAchievedValue($data['achievedValue']);
            $tOkr->setTargetValue($data['targetValue']);
            $tOkr->setAchievementRate($achievementRate);

            // OKRアクティビティ登録
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkr);
            $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ACHIEVEMENT);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
            $tOkrActivity->setTargetValue($data['targetValue']);
            $tOkrActivity->setAchievedValue($data['achievedValue']);
            $tOkrActivity->setAchievementRate($achievementRate);
            $tOkrActivity->setChangedPercentage($achievementRate - $previousAchievementRate);
            $this->persist($tOkrActivity);

            // 投稿登録
            foreach ($groupIdArray as $groupId) {
                $tPost = new TPost();
                $tPost->setTimelineOwnerGroupId($groupId);
                $tPost->setPosterId($auth->getUserId());
                $tPost->setPost($data['post']);
                $tPost->setPostedDatetime(DateUtility::getCurrentDatetime());
                $tPost->setOkrActivity($tOkrActivity);
                $tPost->setDisclosureType($tOkr->getDisclosureType());
                $this->persist($tPost);
            }

            // 達成率を再計算
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            $okrAchievementRateLogic->recalculate($tOkr, $auth->getCompanyId());

            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKR削除
     *
     * @param double $treeLeft 入れ子集合モデルの左値
     * @param double $treeRight 入れ子集合モデルの右値
     * @param integer $timeframeId タイムフレームID
     * @return void
     */
    public function deleteOkrs($treeLeft, $treeRight, $timeframeId)
    {
        $tOkrRepos = $this->getTOkrRepository();

        try {
            // 削除対象OKRとそれに紐づくOKRを全て削除する
            $tOkrRepos->deleteOkrAndAllAlignmentOkrs($treeLeft, $treeRight, $timeframeId);
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
