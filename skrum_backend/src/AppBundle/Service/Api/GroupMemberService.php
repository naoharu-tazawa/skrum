<?php

namespace AppBundle\Service\Api;

use AppBundle\Entity\TGroupMember;
use AppBundle\Exception\SystemException;
use AppBundle\Exception\ApplicationException;
use AppBundle\Service\BaseService;
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
     * @param string $groupId グループID
     * @return array
     */
    public function getMembers($groupId)
    {
        $mUserArray = $this->getTGroupMemberArray($groupId);

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
     * グループメンバーエンティティ配列取得
     *
     * @param string $groupId グループID
     * @return array
     */
    public function getTGroupMemberArray($groupId)
    {
         $tGroupMemberRepos = $this->getTGroupMemberRepository();
         return $tGroupMemberRepos->getAllGroupMembers($groupId);
    }
}
