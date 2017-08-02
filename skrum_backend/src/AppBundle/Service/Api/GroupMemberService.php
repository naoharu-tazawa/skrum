<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TGroupMember;
use AppBundle\Api\ResponseDTO\AdditionalGroupMemberDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupDTO;
use AppBundle\Api\ResponseDTO\NestedObject\MemberDTO;

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
     * @param MUser $mUser ユーザエンティティ
     * @param MGroup $mGroup グループエンティティ
     * @param array $userOkrsArray 当該メンバー所有OKR配列
     * @param array $groupOkrsArray 当該グループ所有OKR配列
     * @return MemberDTO
     */
    public function addMember(MUser $mUser, MGroup $mGroup, array $userOkrsArray, array $groupOkrsArray): AdditionalGroupMemberDTO
    {
        // グループメンバー排他チェック
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $tGroupMemberArray = $tGroupMemberRepos->getGroupMember($mGroup->getGroupId(), $mUser->getUserId());
        if (count($tGroupMemberArray) > 0) {
            throw new DoubleOperationException('このユーザはグループに既に登録済みです');
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

        // MemberDTOの生成
        $memberDTO = new MemberDTO();
        $memberDTO->setUserId($mUser->getUserId());
        $memberDTO->setName($mUser->getLastName() . ' ' . $mUser->getFirstName());
        $memberDTO->setPosition($mUser->getPosition());
        $userAchievementRateArray = array();
        foreach ($userOkrsArray as $userOkr) {
            $userAchievementRateArray[] = $userOkr->getAchievementRate();
        }
        if (count($userAchievementRateArray) !== 0) {
            $memberDTO->setAchievementRate(floor((array_sum($userAchievementRateArray) / count($userAchievementRateArray)) * 10) / 10);
        } else {
            $memberDTO->setAchievementRate(0);
        }
        $tLoginRepos = $this->getTLoginRepository();
        $memberDTO->setLastLogin($tLoginRepos->getLastLogin($mUser->getUserId()));

        // GroupDTOの生成
        $groupDTO = new GroupDTO();
        $groupDTO->setGroupId($mGroup->getGroupId());
        $groupDTO->setGroupName($mGroup->getGroupName());
        $groupAchievementRateArray = array();
        foreach ($groupOkrsArray as $groupOkr) {
            $groupAchievementRateArray[] = $groupOkr->getAchievementRate();
        }
        if (count($groupAchievementRateArray) !== 0) {
            $groupDTO->setAchievementRate(floor((array_sum($groupAchievementRateArray) / count($groupAchievementRateArray)) * 10) / 10);
        } else {
            $groupDTO->setAchievementRate(0);
        }

        // AdditionalGroupMemberDTOの生成
        $additionalGroupMemberDTO = new AdditionalGroupMemberDTO();
        $additionalGroupMemberDTO->setUser($memberDTO);
        $additionalGroupMemberDTO->setGroup($groupDTO);

        return $additionalGroupMemberDTO;
    }

    /**
     * グループメンバー取得
     *
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getMembers(int $groupId, int $timeframeId): array
    {
        $userInfoArray = $this->getUserInfoArray($groupId, $timeframeId);

        // グループ情報配列を整形してDTOに詰め替える
        $members = array();
        $tLoginRepos = $this->getTLoginRepository();
        $userInfoArrayCount = count($userInfoArray);
        for ($i = 0; $i < $userInfoArrayCount; ++$i) {
            if ($i === 0) {
                // 初回ループ

                $memberDTO = new MemberDTO();
                $memberDTO->setUserId($userInfoArray[$i]['userId']);
                $memberDTO->setName($userInfoArray[$i]['lastName'] . ' ' . $userInfoArray[$i]['firstName']);
                $memberDTO->setPosition($userInfoArray[$i]['position']);
                $memberDTO->setLastLogin($tLoginRepos->getLastLogin($userInfoArray[$i]['userId']));

                $achievementRateArray = array();
                $achievementRateArray[] = $userInfoArray[$i]['achievementRate'];
            } else {
                // 初回と最終以外のループ

                if ($userInfoArray[$i]['userId'] == $userId) {
                    // ユーザIDが前回ループ時と同じ場合

                    $achievementRateArray[] = $userInfoArray[$i]['achievementRate'];
                } else {
                    // グループIDが前回ループ時と異なる場合

                    // 達成率には平均値を入れる
                    $memberDTO->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);
                    $members[] = $memberDTO;

                    $memberDTO = new MemberDTO();
                    $memberDTO->setUserId($userInfoArray[$i]['userId']);
                    $memberDTO->setName($userInfoArray[$i]['lastName'] . ' ' . $userInfoArray[$i]['firstName']);
                    $memberDTO->setPosition($userInfoArray[$i]['position']);
                    $memberDTO->setLastLogin($tLoginRepos->getLastLogin($userInfoArray[$i]['userId']));

                    $achievementRateArray = array();
                    $achievementRateArray[] = $userInfoArray[$i]['achievementRate'];
                }
            }

            // グループID保持
            $userId = $userInfoArray[$i]['userId'];

            // 最終ループ
            if ($i === ($userInfoArrayCount - 1)) {
                // 達成率には平均値を入れる
                $memberDTO->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);
                $members[] = $memberDTO;
            }
        }

        return $members;
    }

    /**
     * グループメンバー取得（リーダー用）
     *
     * @param integer $groupId グループID
     * @return array
     */
    public function getPossibleleaders(int $groupId): array
    {
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $possibleLeaderArray = $tGroupMemberRepos->getAllGroupMembers($groupId);

        $possibleLeaders = array();
        foreach ($possibleLeaderArray as $possibleLeader) {
            $memberDTO = new MemberDTO();
            $memberDTO->setUserId($possibleLeader->getUserId());
            $memberDTO->setName($possibleLeader->getLastName() . ' ' . $possibleLeader->getFirstName());
            $possibleLeaders[] = $memberDTO;
        }

        return $possibleLeaders;
    }

    /**
     * ユーザ（グループメンバー）エンティティ配列取得
     *
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getUserInfoArray(int $groupId, int $timeframeId): array
    {
         $tGroupMemberRepos = $this->getTGroupMemberRepository();

         return $tGroupMemberRepos->getAllGroupMembersWithAchievementRate($groupId, $timeframeId);
    }

    /**
     * グループメンバー削除
     *
     * @param MGroup $groupId グループID
     * @param integer $userId ユーザID
     * @return void
     */
    public function deleteMember(MGroup $mGroup, int $userId)
    {
        // グループメンバー存在チェック
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $tGroupMember = $tGroupMemberRepos->findOneBy(array('group' => $mGroup->getGroupId(), 'user' => $userId));
        if ($tGroupMember === null) {
            throw new DoubleOperationException('このユーザはグループから既に削除されています');
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            // グループメンバー削除
            $this->remove($tGroupMember);

            // グループリーダーになっている場合は、NULLを設定
            if ($mGroup->getLeaderUserId() === $userId) {
                $mGroup->setLeaderUserId(null);
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
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
    public function getGroupsWithAchievementRate(int $userId, int $timeframeId): array
    {
        $groupInfoArray = $this->getGroupInfoArray($userId, $timeframeId);

        // グループ情報配列を整形してDTOに詰め替える
        $groups = array();
        $groupInfoArrayCount = count($groupInfoArray);;
        for ($i = 0; $i < $groupInfoArrayCount; ++$i) {
            if ($i === 0) {
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
            if ($i === ($groupInfoArrayCount - 1)) {
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
    public function getGroupInfoArray(int $userId, int $timeframeId): array
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
    public function getTeamsAndDepartments(int $userId): array
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
