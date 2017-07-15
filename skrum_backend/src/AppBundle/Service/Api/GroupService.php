<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\NoDataException;
use AppBundle\Exception\SystemException;
use AppBundle\Repository\TOkrRepository;
use AppBundle\Logic\OkrAchievementRateLogic;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TGroupTree;
use AppBundle\Entity\TOkr;
use AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathElementDTO;

/**
 * グループサービスクラス
 *
 * @author naoharu.tazawa
 */
class GroupService extends BaseService
{
    /**
     * グループ新規登録
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param TGroupTree $tGroupTree グループツリーエンティティ
     * @return BasicGroupInfoDTO
     */
    public function createGroup(Auth $auth, array $data, TGroupTree $groupTreeEntity = null): BasicGroupInfoDTO
    {
        // グループ名に'/'(スラッシュ)が入っている場合除外する
        $data['groupName'] = str_replace('/', '', $data['groupName']);

        // トランザクション開始
        $this->beginTransaction();

        try {
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($auth->getCompanyId());

            // グループ登録
            $mGroup = new MGroup();
            $mGroup->setCompany($mCompany);
            $mGroup->setGroupName($data['groupName']);
            $mGroup->setGroupType($data['groupType']);
            $mGroup->setLeaderUserId($auth->getUserId());
            $this->persist($mGroup);
            $this->flush();

            // グループツリー登録
            if ($groupTreeEntity !== null) {
                $tGroupTree = new TGroupTree();
                $tGroupTree->setGroup($mGroup);
                $tGroupTree->setGroupTreePath($groupTreeEntity->getGroupTreePath() . $mGroup->getGroupId() . '/');
                $tGroupTree->setGroupTreePathName($groupTreeEntity->getGroupTreePathName() . $mGroup->getGroupName() . '/');
                $this->persist($tGroupTree);
            }

            $this->flush();
            $this->commit();

            // レスポンスDTOを生成
            $basicGroupInfoDTO = new BasicGroupInfoDTO;
            $basicGroupInfoDTO->setGroupId($mGroup->getGroupId());
            $basicGroupInfoDTO->setName($mGroup->getGroupName());
            if ($groupTreeEntity !== null) {
                $groupTreePathArray = explode('/', $tGroupTree->getGroupTreePath(), -1);
                $groupTreePathNameArray = explode('/', $tGroupTree->getGroupTreePathName(), -1);
                $count = count($groupTreePathArray);
                $groupIdAndNameArray = array();
                for ($i = 0; $i < $count; ++$i) {
                    $groupPathElementDTO = new GroupPathElementDTO();
                    if ($i === 0) {
                        $groupPathElementDTO->setId($auth->getCompanyId());
                    } else {
                        $groupPathElementDTO->setId($groupTreePathArray[$i]);
                    }
                    $groupPathElementDTO->setName($groupTreePathNameArray[$i]);
                    $groupIdAndNameArray[] = $groupPathElementDTO;
                }
                $basicGroupInfoDTO->setGroupPaths($groupIdAndNameArray);
            }
            $basicGroupInfoDTO->setMission($mGroup->getMission());
            if ($mGroup->getLeaderUserId() !== null) {
                $basicGroupInfoDTO->setLeaderUserId($mGroup->getLeaderUserId());
                $mUserRepos = $this->getMUserRepository();
                $mUser = $mUserRepos->find($mGroup->getLeaderUserId());
                $basicGroupInfoDTO->setLeaderName($mUser->getLastName() . ' ' . $mUser->getFirstName());
            }

            return $basicGroupInfoDTO;
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * グループ基本情報取得
     *
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return BasicGroupInfoDTO
     */
    public function getBasicGroupInfo(int $groupId, int $companyId): BasicGroupInfoDTO
    {
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->getGroupWithLeaderUser($groupId, $companyId);
        if (count($mGroupArray) == 0) {
            throw new NoDataException('グループが存在しません');
        }

        // グループパスを取得
        $tGroupTreeRepos = $this->getTGroupTreeRepository();
        $tGroupTreeArray = $tGroupTreeRepos->findBy(array('group' => $groupId));
        $groupPathArray = array();
        foreach ($tGroupTreeArray as $tGroupTree) {
            $groupTreePathArray = explode('/', $tGroupTree->getGroupTreePath(), -1);
            $groupTreePathNameArray = explode('/', $tGroupTree->getGroupTreePathName(), -1);
            $count = count($groupTreePathArray);
            $groupIdAndNameArray = array();
            for ($i = 0; $i < $count; ++$i) {
                $groupPathElementDTO = new GroupPathElementDTO();
                if ($i === 0) {
                    $groupPathElementDTO->setId($companyId);
                } else {
                    $groupPathElementDTO->setId($groupTreePathArray[$i]);
                }
                $groupPathElementDTO->setName($groupTreePathNameArray[$i]);
                $groupIdAndNameArray[] = $groupPathElementDTO;
            }

            $groupPathDTO = new GroupPathDTO();
            $groupPathDTO->setGroupTreeId($tGroupTree->getId());
            $groupPathDTO->setGroupPath($groupIdAndNameArray);
            $groupPathArray[] = $groupPathDTO;
        }

        $basicGroupInfoDTO = new BasicGroupInfoDTO();
        $basicGroupInfoDTO->setGroupId($mGroupArray['mGroup']->getGroupId());
        $basicGroupInfoDTO->setName($mGroupArray['mGroup']->getGroupName());
        $basicGroupInfoDTO->setGroupPaths($groupPathArray);
        $basicGroupInfoDTO->setMission($mGroupArray['mGroup']->getMission());
        if ($mGroupArray['mGroup']->getLeaderUserId() !== null) {
            $basicGroupInfoDTO->setLeaderUserId($mGroupArray['mGroup']->getLeaderUserId());
            $basicGroupInfoDTO->setLeaderName($mGroupArray['mUser']->getLastName() . ' ' . $mGroupArray['mUser']->getFirstName());
        }

        return $basicGroupInfoDTO;
    }

    /**
     * グループ情報更新
     *
     * @param array $data リクエストJSON連想配列
     * @param MGroup $mGroup グループエンティティ
     * @param integer $companyId 会社ID
     * @return void
     */
    public function updateGroup(array $data, MGroup $mGroup, int $companyId)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // グループ情報更新
            if (array_key_exists('name', $data) && !empty($data['name'])) {
                $data['name'] = str_replace('/', '', $data['name']);
                $mGroup->setGroupName($data['name']);
            }
            if (array_key_exists('mission', $data)) {
                $mGroup->setMission($data['mission']);
            }
            $this->flush();

            // グループパス名を更新
            if (array_key_exists('name', $data) && !empty($data['name'])) {
                // グループIDをグループパスに含むグループパスエンティティを取得
                $groupId = $mGroup->getGroupId();
                $tGroupTreeRepos = $this->getTGroupTreeRepository();
                $tGroupTreeArray = $tGroupTreeRepos->getLikeGroupId($groupId, $companyId);

                // グループパス名を更新
                foreach ($tGroupTreeArray as $tGroupTree) {
                    // グループパスとグループパス名を'/'で分割し配列に格納
                    $groupTreePathItems = explode('/', $tGroupTree->getGroupTreePath(), -1);
                    $groupTreePathNameItems = explode('/', $tGroupTree->getGroupTreePathName(), -1);

                    // グループIDが一致する箇所のグループパス名中のグループ名を変更
                    $count = count($groupTreePathItems);
                    for ($i = 0; $i < $count; ++$i) {
                        if ($groupTreePathItems[$i] == $groupId) {
                            $groupTreePathNameItems[$i] = $data['name'];
                        }
                    }

                    // グループパスを再構成
                    $newGroupTreePathName = '';
                    foreach ($groupTreePathNameItems as $groupTreePathName) {
                        $newGroupTreePathName .= $groupTreePathName . '/';
                    }

                    // グループパス名を更新
                    $tGroupTree->setGroupTreePathName($newGroupTreePathName);
                    $this->flush();
                }
            }

            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * グループリーダー更新
     *
     * @param MGroup $mGroup グループエンティティ
     * @param integer $userId ユーザID
     * @return void
     */
    public function changeGroupLeader(MGroup $mGroup, int $userId)
    {
        // 現在のグループリーダーが変更対象ユーザと同一の場合、更新処理を行わない
        if ($mGroup->getLeaderUserId() == $userId) {
            return;
        }

        // グループにユーザが所属しているかチェック
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $tGroupMemberArray = $tGroupMemberRepos->getGroupMember($mGroup->getGroupId(), $userId);
        if (count($tGroupMemberArray) == 0) {
            throw new NoDataException('このユーザはグループメンバーではありません');
        }

        // グループリーダー更新
        $mGroup->setLeaderUserId($userId);

        try {
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * グループ削除
     *
     * @param Auth $auth 認証情報
     * @param MGroup $mGroup グループエンティティ
     * @return void
     */
    public function deleteGroup(Auth $auth, MGroup $mGroup)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // グループメンバー削除
            $tGroupMemberRepos = $this->getTGroupMemberRepository();
            $tGroupMemberRepos->deleteAllGroupMembers($mGroup->getGroupId());

            // グループパス削除
            $tGroupTreeRepos = $this->getTGroupTreeRepository();
            $tGroupTreeRepos->deleteAllPaths($mGroup->getGroupId());

            /* 今回の開発では以下のコメントアウト部分の対応は行わない */
//             // グループが所有する全てのOKR、及びそれに紐づく全てのOKR、OKRアクティビティを削除
//             $tTimeframeRepos = $this->getTTimeframeRepository();
//             $tTimeframeArray = $tTimeframeRepos->findBy(array('company' => $auth->getCompanyId()), array('timeframeId' => 'DESC'));
//             $tOkrRepos = $this->getTOkrRepository();
//             $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
//             foreach ($tTimeframeArray as $tTimeframe) {
//                 $tOkrArray = $tOkrRepos->getGroupObjectivesAndKeyResults($mGroup->getGroupId(), $tTimeframe->getTimeframeId(), $auth->getCompanyId());

//                 // キーリザルトから削除
//                 foreach ($tOkrArray as $tOkrKeyResult) {
//                     if (array_key_exists('keyResult', $tOkrKeyResult)) {
//                         if ($tOkrKeyResult['keyResult'] != null) {
//                             $this->deleteOkrs($auth, $tOkrKeyResult['keyResult'], $tOkrRepos, $okrAchievementRateLogic);
//                         }
//                     }
//                 }

//                 // キーリザルト削除後に目標を削除
//                 foreach ($tOkrArray as $tOkrObjective) {
//                     if (array_key_exists('objective', $tOkrObjective)) {
//                         if ($tOkrObjective['objective'] != null) {
//                             $this->deleteOkrs($auth, $tOkrObjective['objective'], $tOkrRepos, $okrAchievementRateLogic);
//                         }
//                     }
//                 }
//             }

            // アーカイブ済フラグを立てる
            $mGroup->setArchivedFlg(DBConstant::FLG_TRUE);

            $this->flush();
            $this->commit();
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
     * @param TOkrRepository $tOkrRepos OKRリポジトリ
     * @param OkrAchievementRateLogic $okrAchievementRateLogic OKR達成率ロジック
     * @return void
     */
    private function deleteOkrs(Auth $auth, TOkr $tOkr, TOkrRepository $tOkrRepos, OkrAchievementRateLogic $okrAchievementRateLogic)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // 達成率を再計算
            $tOkr->setWeightedAverageRatio(0);
            $tOkr->setRatioLockedFlg(DBConstant::FLG_TRUE);
            $this->flush();
            $okrAchievementRateLogic->recalculate($auth, $tOkr, true);

            // 削除対象OKRとそれに紐づくOKRを全て削除する
            $tOkrRepos->deleteOkrAndAllAlignmentOkrs($tOkr->getTreeLeft(), $tOkr->getTreeRight(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
            $this->flush();

            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
