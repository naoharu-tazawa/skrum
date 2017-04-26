<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DBConstant;
use AppBundle\Api\ResponseDTO\GroupPageSearchDTO;
use AppBundle\Api\ResponseDTO\GroupSearchDTO;
use AppBundle\Api\ResponseDTO\GroupTreeSearchDTO;
use AppBundle\Api\ResponseDTO\OkrSearchDTO;
use AppBundle\Api\ResponseDTO\OwnerSearchDTO;
use AppBundle\Api\ResponseDTO\UserPageSearchDTO;
use AppBundle\Api\ResponseDTO\UserSearchDTO;
use AppBundle\Utils\DateUtility;

/**
 * 検索サービスクラス
 *
 * @author naoharu.tazawa
 */
class SearchService extends BaseService
{
    /**
     * ユーザ検索
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchUser(Auth $auth, $keyword)
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // ユーザ検索
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->searchUser($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        $userSearchDTOArray = array();
        foreach ($mUserArray as $mUser) {
            $userSearchDTO = new UserSearchDTO();
            $userSearchDTO->setUserId($mUser['userId']);
            $userSearchDTO->setUserName($mUser['lastName'] . ' ' . $mUser['firstName']);

            $userSearchDTOArray[] = $userSearchDTO;
        }

        return $userSearchDTOArray;
    }

    /**
     * ユーザ検索（ページング）
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @param string $page 要求ページ
     * @return array
     */
    public function pagesearchUser(Auth $auth, string $keyword, int $page): UserPageSearchDTO
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // ユーザ検索
        $mUserRepos = $this->getMUserRepository();
        $count = $mUserRepos->getPagesearchCount($escapedKeyword, $auth->getCompanyId());
        $mUserArray = $mUserRepos->pagesearchUser($escapedKeyword, $page, $this->getParameter('paging_data_number'), $auth->getCompanyId());

        // DTOに詰め替える
        $userSearchDTOArray = array();
        foreach ($mUserArray as $mUser) {
            $userSearchDTO = new UserSearchDTO();
            $userSearchDTO->setUserId($mUser['user_id']);
            $userSearchDTO->setUserName($mUser['last_name'] . ' ' . $mUser['first_name']);
            $userSearchDTO->setRoleAssignmentId($mUser['role_assignment_id']);
            $userSearchDTO->setRoleLevel($mUser['role_level']);
            $userSearchDTO->setLastLogin(DateUtility::transIntoDatetime($mUser['last_access_datetime']));

            $userSearchDTOArray[] = $userSearchDTO;
        }

        $userPageSearchDTO = new UserPageSearchDTO();
        $userPageSearchDTO->setCount($count);
        $userPageSearchDTO->setResults($userSearchDTOArray);

        return $userPageSearchDTO;
    }

    /**
     * グループ検索
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchGroup(Auth $auth, $keyword)
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // グループ検索
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->searchGroup($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        $groupSearchDTOArray = array();
        foreach ($mGroupArray as $mGroup) {
            $groupSearchDTO = new GroupSearchDTO();
            $groupSearchDTO->setGroupId($mGroup['groupId']);
            $groupSearchDTO->setGroupName($mGroup['groupName']);

            $groupSearchDTOArray[] = $groupSearchDTO;
        }

        return $groupSearchDTOArray;
    }

    /**
     * グループ検索（ページング）
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @param string $page 要求ページ
     * @return array
     */
    public function pagesearchGroup(Auth $auth, string $keyword, int $page): GroupPageSearchDTO
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // グループ検索
        $mGroupRepos = $this->getMGroupRepository();
        $count = $mGroupRepos->getPagesearchCount($escapedKeyword, $auth->getCompanyId());
        $mGroupArray = $mGroupRepos->pagesearchGroup($escapedKeyword, $page, $this->getParameter('paging_data_number'), $auth->getCompanyId());

        // DTOに詰め替える
        $groupSearchDTOArray = array();
        foreach ($mGroupArray as $mGroup) {
            $groupSearchDTO = new GroupSearchDTO();
            $groupSearchDTO->setGroupId($mGroup['groupId']);
            $groupSearchDTO->setGroupType($mGroup['groupType']);
            $groupSearchDTO->setGroupName($mGroup['groupName']);

            $groupSearchDTOArray[] = $groupSearchDTO;
        }

        $groupPageSearchDTO = new GroupPageSearchDTO();
        $groupPageSearchDTO->setCount($count);
        $groupPageSearchDTO->setResults($groupSearchDTOArray);

        return $groupPageSearchDTO;
    }

    /**
     * オーナー検索
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchOwner(Auth $auth, $keyword)
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        $ownerSearchDTOArray = array();

        // ユーザ検索
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->searchUser($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        foreach ($mUserArray as $mUser) {
            $ownerSearchDTO = new OwnerSearchDTO();
            $ownerSearchDTO->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
            $ownerSearchDTO->setUserId($mUser['userId']);
            $ownerSearchDTO->setUserName($mUser['lastName'] . ' ' . $mUser['firstName']);

            $ownerSearchDTOArray[] = $ownerSearchDTO;
        }

        // グループ検索
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->searchGroup($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        foreach ($mGroupArray as $mGroup) {
            $ownerSearchDTO = new OwnerSearchDTO();
            $ownerSearchDTO->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
            $ownerSearchDTO->setGroupId($mGroup['groupId']);
            $ownerSearchDTO->setGroupName($mGroup['groupName']);

            $ownerSearchDTOArray[] = $ownerSearchDTO;
        }

        // 会社検索
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompanyArray = $mCompanyRepos->searchCompany($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        foreach ($mCompanyArray as $mCompany) {
            $ownerSearchDTO = new OwnerSearchDTO();
            $ownerSearchDTO->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
            $ownerSearchDTO->setCompanyId($mCompany['companyId']);
            $ownerSearchDTO->setCompanyName($mCompany['companyName']);

            $ownerSearchDTOArray[] = $ownerSearchDTO;
        }

        return $ownerSearchDTOArray;
    }

    /**
     * グループツリーパス検索
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchGroupTree(Auth $auth, $keyword)
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // グループ検索
        $mGroupRepos = $this->getMGroupRepository();
        $tGroupTreeArray = $mGroupRepos->searchGroupTree($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        $groupTreeSearchDTOArray = array();
        foreach ($tGroupTreeArray as $tGroupTree) {
            $groupTreeSearchDTO = new GroupTreeSearchDTO();
            $groupTreeSearchDTO->setGroupPathId($tGroupTree['id']);
            $groupTreeSearchDTO->setGroupPathName($tGroupTree['groupTreePathName']);

            $groupTreeSearchDTOArray[] = $groupTreeSearchDTO;
        }

        return $groupTreeSearchDTOArray;
    }

    /**
     * OKR検索
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @param \AppBundle\Entity\TOkr $okrEntity OKRエンティティ
     * @return array
     */
    public function searchOkr(Auth $auth, $keyword, $okrEntity)
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // OKR検索
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->searchOkr($escapedKeyword, $okrEntity, $auth->getCompanyId());

        // DTOに詰め替える
        $okrSearchDTOArray = array();
        foreach ($tOkrArray as $tOkr) {
            $okrSearchDTO = new OkrSearchDTO();
            $okrSearchDTO->setOkrId($tOkr['okr_id']);
            $okrSearchDTO->setOkrName($tOkr['name']);
            $okrSearchDTO->setOwnerType($tOkr['owner_type']);
            if ($tOkr['owner_type'] == DBConstant::OKR_OWNER_TYPE_USER) {
                $okrSearchDTO->setUserId($tOkr['user_id']);
                $okrSearchDTO->setUserName($tOkr['last_name'] . ' ' . $tOkr['first_name']);
            } elseif ($tOkr['owner_type'] == DBConstant::OKR_OWNER_TYPE_GROUP) {
                $okrSearchDTO->setGroupId($tOkr['group_id']);
                $okrSearchDTO->setGroupName($tOkr['group_name']);
            } else {
                $okrSearchDTO->setCompanyId($tOkr['company_id']);
                $okrSearchDTO->setCompanyName($tOkr['company_name']);
            }

            $okrSearchDTOArray[] = $okrSearchDTO;
        }

        return $okrSearchDTOArray;
    }
}
