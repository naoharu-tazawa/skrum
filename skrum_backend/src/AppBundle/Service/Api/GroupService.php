<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TGroupTree;
use AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathDTO;

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
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return void
     */
    public function createGroup($auth, $data)
    {
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

            if (array_key_exists('groupTreeId', $data)) {
                // 所属先グループツリーパスを取得
                $tGroupTreeRepos = $this->getTGroupTreeRepository();
                $result = $tGroupTreeRepos->getGroupTreePath($data['groupTreeId'], $auth->getCompanyId());

                // グループツリー登録
                $tGroupTree = new TGroupTree();
                $tGroupTree->setGroup($mGroup);
                $tGroupTree->setGroupTreePath($result['groupTreePath'] . $mGroup->getGroupId() . '/');
                $tGroupTree->setGroupTreePathName($result['groupTreePathName'] . $mGroup->getGroupName() . '/');
                $this->persist($tGroupTree);
            }

            $this->flush();
            $this->commit();
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
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO
     */
    public function getBasicGroupInfo($groupId, $companyId)
    {
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->getGroupWithLeaderUser($groupId, $companyId);
        if (count($mGroupArray) == 0) {
            throw new ApplicationException('グループが存在しません');
        }

        // グループパスを取得
        $tGroupTreeRepos = $this->getTGroupTreeRepository();
        $tGroupTreeArray = $tGroupTreeRepos->findBy(array('group' => $groupId));
        $groupPathArray = array();
        foreach ($tGroupTreeArray as $tGroupTree) {
            $groupPathDTO = new GroupPathDTO();
            $groupPathDTO->setGroupTreePath($tGroupTree->getGroupTreePath());
            $groupPathDTO->setGroupTreePathName($tGroupTree->getGroupTreePathName());
            $groupPathArray[] = $groupPathDTO;
        }

        $basicGroupInfoDTO = new BasicGroupInfoDTO();
        $basicGroupInfoDTO->setGroupId($mGroupArray['mGroup']->getGroupId());
        $basicGroupInfoDTO->setName($mGroupArray['mGroup']->getGroupName());
        $basicGroupInfoDTO->setGroupPaths($groupPathArray);
        $basicGroupInfoDTO->setMission($mGroupArray['mGroup']->getMission());
        $basicGroupInfoDTO->setLeaderUserId($mGroupArray['mGroup']->getLeaderUserId());
        $basicGroupInfoDTO->setLeaderName($mGroupArray['mUser']->getLastName() . ' ' . $mGroupArray['mUser']->getFirstName());

        return $basicGroupInfoDTO;
    }

    /**
     * グループ情報更新
     *
     * @param array $data リクエストJSON連想配列
     * @param \AppBundle\Entity\MGroup $mGroup グループエンティティ
     * @return void
     */
    public function updateGroup($data, $mGroup)
    {
        // グループ情報更新
        $mGroup->setGroupName($data['groupName']);
        $mGroup->setMission($data['mission']);

        try {
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * グループリーダー更新
     *
     * @param \AppBundle\Entity\MGroup $mGroup グループエンティティ
     * @param integer $userId ユーザID
     * @return void
     */
    public function changeGroupLeader($mGroup, $userId)
    {
        // 現在のグループリーダーが変更対象ユーザと同一の場合、更新処理を行わない
        if ($mGroup->getLeaderUserId() == $userId) {
            return;
        }

        // グループにユーザが所属しているかチェック
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $tGroupMemberArray = $tGroupMemberRepos->getGroupMember($mGroup->getGroupId(), $userId);
        if (count($tGroupMemberArray) == 0) {
            throw new ApplicationException('このユーザはグループメンバーではありません');
        }

        // グループリーダー更新
        $mGroup->setLeaderUserId($userId);

        try {
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * グループ削除
     *
     * @param \AppBundle\Entity\MGroup $mGroup グループエンティティ
     * @return void
     */
    public function deleteGroup($mGroup)
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

            // ToDo:要検討
            // グループが所有する全てのOKR、及びそれに紐づく全てのOKR、OKRアクティビティを削除
            // $tOkrRepos = $this->getTOkrRepository();
            // $tOkrArray = $tOkrRepos->getGroupObjectivesAndKeyResults($groupId, $timeframeId, $companyId);

            // グループ削除
            $this->remove($mGroup);

            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
