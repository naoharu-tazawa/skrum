<?php

namespace AppBundle\Service\Api;

use AppBundle\Entity\TGroupMember;
use AppBundle\Exception\SystemException;
use AppBundle\Exception\ApplicationException;
use AppBundle\Service\BaseService;
use AppBundle\Api\ResponseDTO\NestedObject\MemberDTO;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Api\ResponseDTO\NestedObject\GroupDTO;
use AppBundle\Utils\DBConstant;

/**
 * グループメンバーサービスクラス
 *
 * @author naoharu.tazawa
 */
class GroupMemberService extends BaseService
{
    /**
     * グループメンバー追加
     *
     * @param \AppBundle\Entity\MUser $mUser ユーザエンティティ
     * @param \AppBundle\Entity\MGroup $mGroup グループエンティティ
     * @return void
     */
    public function addMember($mUser, $mGroup)
    {
        // グループメンバー排他チェック
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $tGroupMemberArray = $tGroupMemberRepos->getGroupMember($mGroup->getGroupId(), $mUser->getUserId());
        if (count($tGroupMemberArray) > 0) {
            throw new ApplicationException('このユーザはグループに既に登録済みです');
        }

        // グループメンバー登録
        $tGroupMember = new TGroupMember();
        $tGroupMember->setGroup($mGroup);
        $tGroupMember->setUser($mUser);
        try {
            $this->persist($tGroupMember);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * グループメンバー取得
     *
     * @param integer $groupId グループID
     * @return array
     */
    public function getMembers($groupId)
    {
        $mUserArray = $this->getMUserArray($groupId);

        $members = array();
        foreach ($mUserArray as $mUser) {
            $memberDTO = new MemberDTO();
            $memberDTO->setUserId($mUser->getUserId());
            $memberDTO->setLastName($mUser->getLastName());
            $memberDTO->setFirstName($mUser->getFirstName());
            $memberDTO->setPosition($mUser->getPosition());
            $memberDTO->setLastLogin($mUser->getLastAccessDatetime());

            $members[] = $memberDTO;
        }

        return $members;
    }

    /**
     * ユーザ（グループメンバー）エンティティ配列取得
     *
     * @param integer $groupId グループID
     * @return array
     */
    public function getMUserArray($groupId)
    {
         $tGroupMemberRepos = $this->getTGroupMemberRepository();
         return $tGroupMemberRepos->getAllGroupMembers($groupId);
    }

    /**
     * グループメンバー削除
     *
     * @param integer $groupId グループID
     * @param integer $userId ユーザID
     * @return void
     */
    public function deleteMember($groupId, $userId)
    {
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $tGroupMember = $tGroupMemberRepos->findOneBy(array('group' => $groupId, 'user' => $userId));
        if ($tGroupMember === null) {
            throw new DoubleOperationException('このユーザはグループから既に削除されています');
        }

        try {
            $this->remove($tGroupMember);
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * ユーザ所属グループリスト取得
     *
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getGroupsWithAchievementRate($userId, $timeframeId)
    {
        $groupInfoArray = $this->getGroupInfoArray($userId, $timeframeId);

        // グループ情報配列を整形してDTOに詰め替える
        $groups = array();
        for ($i = 0; $i < count($groupInfoArray); $i++) {
            if ($i == 0) {
                // 初回ループ

                $groupDTO = new GroupDTO();
                $groupDTO->setGroupId($groupInfoArray[$i]['groupId']);
                $groupDTO->setGroupName($groupInfoArray[$i]['groupName']);

                $achievementRateArray = array();
                $achievementRateArray[] = $groupInfoArray[$i]['achievementRate'];
            } else {
                // 初回と最終以外のループ

                if ($groupInfoArray[$i]['groupId'] == $groupId) {
                    // グループIDが前回ループ時と同じ場合

                    $achievementRateArray[] = $groupInfoArray[$i]['achievementRate'];
                } else {
                    // グループIDが前回ループ時と異なる場合

                    // 達成率には平均値を入れる
                    $groupDTO->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);
                    $groups[] = $groupDTO;

                    $groupDTO = new GroupDTO();
                    $groupDTO->setGroupId($groupInfoArray[$i]['groupId']);
                    $groupDTO->setGroupName($groupInfoArray[$i]['groupName']);

                    $achievementRateArray = array();
                    $achievementRateArray[] = $groupInfoArray[$i]['achievementRate'];
                }
            }

            // グループID保持
            $groupId = $groupInfoArray[$i]['groupId'];

            // 最終ループ
            if ($i == (count($groupInfoArray) - 1)) {
                // 達成率には平均値を入れる
                $groupDTO->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);
                $groups[] = $groupDTO;
            }
        }

        return $groups;
    }

    /**
     * グループ情報配列取得
     *
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getGroupInfoArray($userId, $timeframeId)
    {
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        return $tGroupMemberRepos->getAllGroupsWithAchievementRate($userId, $timeframeId);
    }

    /**
     * ユーザ所属チーム/部門リスト取得
     *
     * @param integer $userId ユーザID
     * @return array
     */
    public function getTeamsAndDepartments($userId)
    {
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $groupInfoArray = $tGroupMemberRepos->getAllGroups($userId);

        // グループ情報配列を整形してDTOに詰め替える
        $teams = array();
        $departments = array();
        foreach ($groupInfoArray as $groupInfo) {
            $groupDTO = new GroupDTO();
            $groupDTO->setGroupId($groupInfo['groupId']);
            $groupDTO->setGroupName($groupInfo['groupName']);
            if ($groupInfo['groupType'] == DBConstant::GROUP_TYPE_TEAM) {
                $teams[] = $groupDTO;
            } elseif ($groupInfo['groupType'] == DBConstant::GROUP_TYPE_DEPARTMENT) {
                $departments[] = $groupDTO;
            }
        }

        return array('teams' => $teams, 'departments' => $departments);
    }
}
