<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\NoDataException;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\MUser;
use AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\DepartmentDTO;

/**
 * ユーザサービスクラス
 *
 * @author naoharu.tazawa
 */
class UserService extends BaseService
{
    /**
     * ユーザ基本情報取得
     *
     * @param integer $userId グループID
     * @param integer $companyId 会社ID
     * @return BasicUserInfoDTO
     */
    public function getBasicUserInfo(int $userId, int $companyId): BasicUserInfoDTO
    {
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->findBy(array('userId' => $userId, 'company' => $companyId));
        if (count($mUserArray) === 0) {
            throw new NoDataException('ユーザが存在しません');
        }

        // 所属部門を取得
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $mGroupArray = $tGroupMemberRepos->getDepartments($userId);
        $departmentArray = array();
        foreach ($mGroupArray as $mGroup) {
            $departmentDTO = new DepartmentDTO();
            $departmentDTO->setGroupId($mGroup->getGroupId());
            $departmentDTO->setDepartmentName($mGroup->getGroupName());
            $departmentArray[] = $departmentDTO;
        }

        $basicUserInfoDTO = new BasicUserInfoDTO();
        $basicUserInfoDTO->setUserId($mUserArray[0]->getUserId());
        $basicUserInfoDTO->setName($mUserArray[0]->getLastName() . ' ' . $mUserArray[0]->getFirstName());
        $basicUserInfoDTO->setDepartments($departmentArray);
        $basicUserInfoDTO->setPosition($mUserArray[0]->getPosition());
        $basicUserInfoDTO->setPhoneNumber($mUserArray[0]->getPhoneNumber());
        $basicUserInfoDTO->setEmailAddress($mUserArray[0]->getEmailAddress());

        return $basicUserInfoDTO;
    }

    /**
     * ユーザ情報更新
     *
     * @param array $data リクエストJSON連想配列
     * @param MUser $mUser ユーザエンティティ
     * @return void
     */
    public function updateUser(array $data, MUser $mUser)
    {
        // ユーザ情報更新
        $mUser->setLastName($data['lastName']);
        $mUser->setFirstName($data['firstName']);
        $mUser->setPosition($data['position']);
        $mUser->setPhoneNumber($data['phoneNumber']);

        try {
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * ユーザ削除
     *
     * @param MUser $mUser ユーザエンティティ
     * @return void
     */
    public function deleteUser(MUser $mUser)
    {
        // ユーザ削除
        try {
            $this->remove($mUser);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
