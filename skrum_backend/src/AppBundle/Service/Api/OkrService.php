<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TOkrActivity;
use AppBundle\Entity\TTimeframe;
use AppBundle\Api\ResponseDTO\NestedObject\AlignmentsInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO;
use AppBundle\Api\ResponseDTO\NestedObject\CompanyAlignmentsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupAlignmentsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\UserAlignmentsDTO;

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
     * @param Auth $auth 認証情報
     * @param integer $userId 取得対象ユーザID
     * @param integer $groupId 取得対象グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getObjectivesAndKeyResults(string $subjectType, Auth $auth, int $userId = null, int $groupId = null, int $timeframeId, int $companyId): array
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

        $disclosureLogic = $this->getDisclosureLogic();
        $tOkrArrayCount = count($tOkrArray);
        $returnArray = array();
        $flg = false;
        for ($i = 0; $i < $tOkrArrayCount; ++$i) {
            if (array_key_exists('objective', $tOkrArray[$i])) {
                // 2回目のループ以降、前回ループ分のDTOを配列に入れる
                if ($i !== 0) {
                    if ($flg) {
                        $basicOkrDTOObjective->setKeyResults($basicOkrDTOKeyResultArray);
                    }
                    $returnArray[] = $basicOkrDTOObjective;
                }

                // 閲覧権限をチェック
                if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkrArray[$i]['objective'])) {
                    $flg = false;
                    continue;
                }

                $basicOkrDTOObjective = new BasicOkrDTO();
                $basicOkrDTOObjective->setOkrId($tOkrArray[$i]['objective']->getOkrId());
                $basicOkrDTOObjective->setOkrName($tOkrArray[$i]['objective']->getName());
                if ($tOkrArray[$i]['objective']->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                    $basicOkrDTOObjective->setOwnerUserId($tOkrArray[$i]['objective']->getOwnerUser()->getUserId());
                    $basicOkrDTOObjective->setOwnerUserName($tOkrArray[$i]['objective']->getOwnerUser()->getLastName() . ' ' . $tOkrArray[$i]['objective']->getOwnerUser()->getFirstName());
                } elseif ($tOkrArray[$i]['objective']->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
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
                    if ($i === ($tOkrArrayCount - 1)) {
                        $returnArray[] = $basicOkrDTOObjective;
                    }
                    continue;
                }

                // 閲覧権限をチェック
                if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkrArray[$i]['keyResult'])) {
                    // 最終ループ
                    if ($i === ($tOkrArrayCount - 1)) {
                        if ($flg) {
                            $basicOkrDTOObjective->setKeyResults($basicOkrDTOKeyResultArray);
                        }
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
                if ($tOkrArray[$i]['keyResult']->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                    $basicOkrDTOKeyResult->setOwnerUserId($tOkrArray[$i]['keyResult']->getOwnerUser()->getUserId());
                    $basicOkrDTOKeyResult->setOwnerUserName($tOkrArray[$i]['keyResult']->getOwnerUser()->getLastName() . ' ' . $tOkrArray[$i]['keyResult']->getOwnerUser()->getFirstName());
                } elseif ($tOkrArray[$i]['keyResult']->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
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
            if ($i === ($tOkrArrayCount - 1)) {
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
    public function getAlignmentsInfo(string $subjectType, int $userId = null, int $groupId = null, int $timeframeId, int $companyId): array
    {
        $alignmentsInfoDTOArray = array();
        $tOkrRepos = $this->getTOkrRepository();

        // 目標紐付け先情報を取得
        if ($subjectType === Constant::SUBJECT_TYPE_USER) {
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
     * @param Auth $auth 認証情報
     * @param string $ownerType OKRオーナー種別
     * @param array $data リクエストJSON連想配列
     * @param TTimeframe $tTimeframe タイムフレームエンティティ
     * @param MUser $mUser オーナーユーザエンティティ
     * @param MGroup $mGroup オーナーグループエンティティ
     * @param integer $companyId オーナー会社ID
     * @param boolean $alignmentFlg 紐付け先OKR有りフラグ
     * @param TOkr $parentOkrEntity 紐付け先OKRエンティティ
     * @return BasicOkrDTO
     */
    public function createOkr(Auth $auth, string $ownerType, array $data, TTimeframe $tTimeframe, MUser $mUser = null, MGroup $mGroup = null, int $companyId, bool $alignmentFlg, TOkr $parentOkrEntity = null): BasicOkrDTO
    {
        // 開始日と終了日の妥当性チェック
        DateUtility::checkStartDateAndEndDate($data['startDate'], $data['endDate']);

        if ($alignmentFlg) {
            // 紐付け先チェック
            $userId = null;
            $groupId = null;
            if ($mUser !== null) $userId = $mUser->getUserId();
            if ($mGroup !== null) $groupId = $mGroup->getGroupId();
            $okrOperationLogic = $this->getOkrOperationLogic();
            $okrOperationLogic->checkAlignment($data['okrType'], $ownerType, $userId, $groupId, $parentOkrEntity);

            // 入れ子区間モデルの左値と右値を取得
            $treeValues = $this->getLeftRightValues($parentOkrEntity->getOkrId(), $tTimeframe->getTimeframeId());
        } else {
            // 会社のOBJECTIVEを登録する場合、ルートノードが存在するかチェック
            if ($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY && $data['okrType'] === DBConstant::OKR_TYPE_OBJECTIVE) {
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
            if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
                $tOkr->setOwnerUser($mUser);
            } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $tOkr->setOwnerGroup($mGroup);
            } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY) {
                $tOkr->setOwnerCompanyId($companyId);
            }
            $tOkr->setStartDate(DateUtility::transIntoDatetime($data['startDate']));
            $tOkr->setEndDate(DateUtility::transIntoDatetime($data['endDate']));
            $tOkr->setStatus(DBConstant::OKR_STATUS_OPEN);
            $tOkr->setDisclosureType($data['disclosureType']);
            if ($data['okrType'] === DBConstant::OKR_TYPE_KEY_RESULT) $tOkr->setRatioLockedFlg(DBConstant::FLG_FALSE);
            $this->persist($tOkr);

            // OKRアクティビティ登録（作成）
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkr);
            $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_GENERATE);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
            $tOkrActivity->setTargetValue($data['targetValue']);
            $tOkrActivity->setAchievedValue(0);
            $tOkrActivity->setAchievementRate(0);
            $tOkrActivity->setChangedPercentage(0);
            $this->persist($tOkrActivity);

            // 自動投稿登録（作成）
            $postLogic = $this->getPostLogic();
            $postLogic->autoPost($auth, $this->getParameter('auto_post_type_generate'), $tOkr, $tOkrActivity);

            // OKRアクティビティ登録（紐付け）
            if ($alignmentFlg) {
                if (!($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY && $data['okrType'] === DBConstant::OKR_TYPE_OBJECTIVE)) {
                    $tOkrActivity = new TOkrActivity();
                    $tOkrActivity->setOkr($tOkr);
                    $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_ALIGN);
                    $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
                    $tOkrActivity->setNewParentOkrId($parentOkrEntity->getOkrId());
                    $this->persist($tOkrActivity);
                }
            }

            $this->flush();

            // 達成率を再計算
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            $okrAchievementRateLogic->recalculate($auth, $tOkr, true);

            $this->flush();
            $this->commit();

            // レスポンス用DTO作成
            $basicOkrDTO = new BasicOkrDTO();
            $basicOkrDTO->setOkrId($tOkr->getOkrId());
            $basicOkrDTO->setOkrType($tOkr->getType());
            $basicOkrDTO->setOkrName($tOkr->getName());
            $basicOkrDTO->setOkrDetail($tOkr->getDetail());
            $basicOkrDTO->setOwnerType($tOkr->getOwnerType());
            if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                $basicOkrDTO->setOwnerUserId($tOkr->getOwnerUser()->getUserId());
                $basicOkrDTO->setOwnerUserName($tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName());
            } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $basicOkrDTO->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                $basicOkrDTO->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
            } else {
                // 会社エンティティを取得
                $mCompanyRepos = $this->getMCompanyRepository();
                $mCompany = $mCompanyRepos->find($companyId);

                $basicOkrDTO->setOwnerCompanyId($tOkr->getOwnerCompanyId());
                $basicOkrDTO->setOwnerCompanyName($mCompany->getCompanyName());
            }
            $basicOkrDTO->setTargetValue($tOkr->getTargetValue());
            $basicOkrDTO->setAchievedValue($tOkr->getAchievedValue());
            $basicOkrDTO->setUnit($tOkr->getUnit());
            $basicOkrDTO->setAchievementRate($tOkr->getAchievementRate());
            if ($tOkr->getParentOkr() !== null) {
                $basicOkrDTO->setParentOkrId($tOkr->getParentOkr()->getOkrId());
            }
            $basicOkrDTO->setStartDate($tOkr->getStartDate());
            $basicOkrDTO->setEndDate($tOkr->getEndDate());
            $basicOkrDTO->setStatus($tOkr->getStatus());
            $basicOkrDTO->setWeightedAverageRatio($tOkr->getWeightedAverageRatio());
            $basicOkrDTO->setRatioLockedFlg($tOkr->getRatioLockedFlg());

            return $basicOkrDTO;
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

    /**
     * OKR更新
     *
     * @param array $data リクエストJSON連想配列
     * @param TOkr $tOkr OKRエンティティ
     * @return void
     */
    public function changeOkrInfo(array $data, TOkr $tOkr)
    {
        // 開始日と終了日の妥当性チェック
        if (array_key_exists('startDate', $data) || array_key_exists('endDate', $data)) {
            if (array_key_exists('startDate', $data)) {
                $startDate = $data['startDate'];
            } else {
                $startDate = DateUtility::transIntoDatetimeString($tOkr->getStartDate());
            }

            if (array_key_exists('endDate', $data)) {
                $endDate = $data['endDate'];
            } else {
                $endDate = DateUtility::transIntoDatetimeString($tOkr->getEndDate());
            }

            DateUtility::checkStartDateAndEndDate($startDate, $endDate);
        }

        // OKR更新
        if (array_key_exists('okrName', $data) && !empty($data['okrName'])) {
            $tOkr->setName($data['okrName']);
        }
        if (array_key_exists('okrDetail', $data)) {
            $tOkr->setDetail($data['okrDetail']);
        }
        if (array_key_exists('startDate', $data) && !empty($data['startDate'])) {
            $tOkr->setStartDate(DateUtility::transIntoDatetime($data['startDate']));
        }
        if (array_key_exists('endDate', $data) && !empty($data['endDate'])) {
            $tOkr->setEndDate(DateUtility::transIntoDatetime($data['endDate']));
        }

        try {
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKR進捗登録
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param TOkr $tOkr OKRエンティティ
     * @return BasicOkrDTO
     */
    public function registerAchievement(Auth $auth, array $data, TOkr $tOkr): BasicOkrDTO
    {
        // 「OKR種別＝1:目標」の場合、かつ子OKRが紐づいている場合、進捗登録不可
        $tOkrRepos = $this->getTOkrRepository();
        if ($tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
            $tOkrArray = $tOkrRepos->findBy(array('parentOkr' => $tOkr->getOkrId()), null, 1);
            if (count($tOkrArray) !== 0) {
                throw new ApplicationException('進捗登録対象目標に子OKRが紐づいている場合、進捗登録できません');
            }
        }

        // 前回の達成値を取得
        $previousAchievedValue = $tOkr->getAchievedValue();

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

            // 自動投稿（進捗登録時）文面作成
            $balanceAmount = $data['achievedValue'] - $previousAchievedValue;
            if ($balanceAmount >= 0) {
                $mathSymbol = '+';
            } else {
                $mathSymbol = null;
            }
            $unit = $tOkr->getUnit();
            $autoPostAchievement = sprintf(
                    $this->getParameter('auto_post_type_achievement'),
                    $previousAchievedValue,
                    $unit,
                    $data['achievedValue'],
                    $unit,
                    $mathSymbol,
                    $balanceAmount,
                    $unit);

            // 投稿
            $postLogic = $this->getPostLogic();
            if (array_key_exists('post', $data)) {
                $manualPost = $data['post'];

                // 手動投稿登録
                $postLogic->manualPost($auth, $manualPost, $autoPostAchievement, $tOkr, $tOkrActivity);
            } else {
                // 自動投稿登録
                $postLogic->autoPost($auth, $autoPostAchievement, $tOkr, $tOkrActivity);
            }

            // 自動投稿登録（◯%達成時）
            $postLogic->autoPostAboutAchievement($auth, $achievementRate, $previousAchievementRate, $tOkr, $tOkrActivity);

            // 達成率を再計算
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            $okrAchievementRateLogic->recalculate($auth, $tOkr, false);

            $this->flush();
            $this->commit();

            // レスポンス用DTOを作成
            $basicOkrDTO = new BasicOkrDTO;
            $basicOkrDTO->setOkrId($tOkr->getOkrId());
            $basicOkrDTO->setAchievedValue($tOkr->getAchievedValue());
            $basicOkrDTO->setTargetValue($tOkr->getTargetValue());
            $basicOkrDTO->setAchievementRate($tOkr->getAchievementRate());

            return $basicOkrDTO;
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKR削除
     *
     * @param Auth $auth 認証情報
     * @param TOkr $tOkr 削除対象OKRエンティティ
     * @return void
     */
    public function deleteOkrs(Auth $auth, TOkr $tOkr)
    {
        // 親OKRを取得
        $parentOkr = $tOkr->getParentOkr();

        // トランザクション開始
        $this->beginTransaction();

        try {
            // 削除対象OKRとそれに紐づくOKRを全て削除する
            if ($tOkr->getTreeLeft() !== null) {
                // 入れ子区間モデルの左値・右値が存在する場合
                $tOkrRepos = $this->getTOkrRepository();
                $tOkrRepos->deleteOkrAndAllAlignmentOkrs($tOkr->getTreeLeft(), $tOkr->getTreeRight(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
            } else {
                // 入れ子区間モデルの左値・右値が存在しない場合
                $okrOperationLogic = $this->getOkrOperationLogic();
                $okrOperationLogic->deleteOkrAndAllAlignmentOkrs($tOkr);
            }

            // 達成率を再計算
            if ($parentOkr != null) {
                $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
                $okrAchievementRateLogic->recalculateFromParent($auth, $parentOkr, true);
            }

            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
