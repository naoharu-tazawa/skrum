<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;
use AppBundle\Api\ResponseDTO\GroupPageSearchDTO;
use AppBundle\Api\ResponseDTO\GroupSearchDTO;
use AppBundle\Api\ResponseDTO\GroupTreeSearchDTO;
use AppBundle\Api\ResponseDTO\OkrSearchDTO;
use AppBundle\Api\ResponseDTO\OwnerSearchDTO;
use AppBundle\Api\ResponseDTO\UserPageSearchDTO;
use AppBundle\Api\ResponseDTO\UserSearchDTO;

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
    public function searchUser(Auth $auth, string $keyword): array
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
     * @param integer $page 要求ページ
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
    public function searchGroup(Auth $auth, string $keyword): array
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // グループ検索
        $mGroupRepos = $this->getMGroupRepository();
        $groupInfoArray = $mGroupRepos->searchGroup($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        $groupSearchDTOArray = array();
        foreach ($groupInfoArray as $groupInfo) {
            $groupSearchDTO = new GroupSearchDTO();
            $groupSearchDTO->setGroupId($groupInfo['groupId']);
            $groupSearchDTO->setGroupName($groupInfo['groupName']);

            $groupSearchDTOArray[] = $groupSearchDTO;
        }

        return $groupSearchDTOArray;
    }

    /**
     * 参加グループ検索
     *
     * @param Auth $auth 認証情報
     * @param integer $userId ユーザID
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchJoiningGroup(Auth $auth, int $userId, string $keyword): array
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // 参加グループ検索
        $mGroupRepos = $this->getMGroupRepository();
        $groupInfoArray = $mGroupRepos->searchJoiningGroup($userId, $escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        $groupSearchDTOArray = array();
        foreach ($groupInfoArray as $groupInfo) {
            $groupSearchDTO = new GroupSearchDTO();
            $groupSearchDTO->setGroupId($groupInfo['groupId']);
            $groupSearchDTO->setGroupName($groupInfo['groupName']);

            $groupSearchDTOArray[] = $groupSearchDTO;
        }

        return $groupSearchDTOArray;
    }

    /**
     * グループ検索（ページング）
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @param integer $page 要求ページ
     * @return GroupPageSearchDTO
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
    public function searchOwner(Auth $auth, string $keyword): array
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
            $ownerSearchDTO->setOwnerUserId($mUser['userId']);
            $ownerSearchDTO->setOwnerUserName($mUser['lastName'] . ' ' . $mUser['firstName']);

            $ownerSearchDTOArray[] = $ownerSearchDTO;
        }

        // グループ検索
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->searchGroup($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        foreach ($mGroupArray as $mGroup) {
            $ownerSearchDTO = new OwnerSearchDTO();
            $ownerSearchDTO->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
            $ownerSearchDTO->setOwnerGroupId($mGroup['groupId']);
            $ownerSearchDTO->setOwnerGroupName($mGroup['groupName']);

            $ownerSearchDTOArray[] = $ownerSearchDTO;
        }

        // 会社検索
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompanyArray = $mCompanyRepos->searchCompany($escapedKeyword, $auth->getCompanyId());

        // DTOに詰め替える
        foreach ($mCompanyArray as $mCompany) {
            $ownerSearchDTO = new OwnerSearchDTO();
            $ownerSearchDTO->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
            $ownerSearchDTO->setOwnerCompanyId($mCompany['companyId']);
            $ownerSearchDTO->setOwnerCompanyName($mCompany['companyName']);

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
    public function searchGroupTree(Auth $auth, string $keyword): array
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
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function searchOkr(Auth $auth, string $keyword, int $timeframeId = null): array
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // OKR検索
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->searchOkr($escapedKeyword, $timeframeId, $auth->getCompanyId());

        // DTOに詰め替える
        $okrSearchDTOArray = array();
        foreach ($tOkrArray as $tOkr) {
            $okrSearchDTO = new OkrSearchDTO();
            $okrSearchDTO->setOkrId($tOkr['okr_id']);
            $okrSearchDTO->setOkrName($tOkr['name']);
            $okrSearchDTO->setOwnerType($tOkr['owner_type']);
            if ($tOkr['owner_type'] == DBConstant::OKR_OWNER_TYPE_USER) {
                $okrSearchDTO->setOwnerUserId($tOkr['user_id']);
                $okrSearchDTO->setOwnerUserName($tOkr['last_name'] . ' ' . $tOkr['first_name']);
            } elseif ($tOkr['owner_type'] == DBConstant::OKR_OWNER_TYPE_GROUP) {
                $okrSearchDTO->setOwnerGroupId($tOkr['group_id']);
                $okrSearchDTO->setOwnerGroupName($tOkr['group_name']);
            } else {
                $okrSearchDTO->setOwnerCompanyId($tOkr['company_id']);
                $okrSearchDTO->setOwnerCompanyName($tOkr['company_name']);
            }

            $okrSearchDTOArray[] = $okrSearchDTO;
        }

        return $okrSearchDTOArray;
    }

    /**
     * 紐付け先OKR検索（新規目標登録時・紐付け先変更時(対象:Objective)兼用）
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @param integer $timeframeId タイムフレームID
     * @param string $ownerType OKRオーナータイプ
     * @param integer $ownerUserId OKRオーナーユーザーID
     * @param integer $ownerGroupId OKRオーナーグループID
     * @param integer $ownerCompanyId OKRオーナー会社ID
     * @return array
     */
    public function searchParentOkr(Auth $auth, string $keyword, int $timeframeId = null, string $ownerType, int $ownerUserId = null, int $ownerGroupId = null, int $ownerCompanyId = null): array
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // OKR検索
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->searchParentOkr($escapedKeyword, $timeframeId, $auth->getCompanyId(), $ownerType, $ownerUserId, $ownerGroupId, $ownerCompanyId);

        // DTOに詰め替える
        $okrSearchDTOArray = array();
        foreach ($tOkrArray as $tOkr) {
            $okrSearchDTO = new OkrSearchDTO();
            $okrSearchDTO->setOkrId($tOkr['okr_id']);
            $okrSearchDTO->setOkrName($tOkr['name']);
            $okrSearchDTO->setOwnerType($tOkr['owner_type']);
            if ($tOkr['owner_type'] == DBConstant::OKR_OWNER_TYPE_USER) {
                $okrSearchDTO->setOwnerUserId($tOkr['user_id']);
                $okrSearchDTO->setOwnerUserName($tOkr['last_name'] . ' ' . $tOkr['first_name']);
            } elseif ($tOkr['owner_type'] == DBConstant::OKR_OWNER_TYPE_GROUP) {
                $okrSearchDTO->setOwnerGroupId($tOkr['group_id']);
                $okrSearchDTO->setOwnerGroupName($tOkr['group_name']);
            } else {
                $okrSearchDTO->setOwnerCompanyId($tOkr['company_id']);
                $okrSearchDTO->setOwnerCompanyName($tOkr['company_name']);
            }

            $okrSearchDTOArray[] = $okrSearchDTO;
        }

        return $okrSearchDTOArray;
    }

    /**
     * 紐付け先Objective検索（紐付け先変更時(対象:Key Result)用）
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @param integer $timeframeId タイムフレームID
     * @param string $ownerType OKRオーナータイプ
     * @param integer $ownerUserId OKRオーナーユーザーID
     * @param integer $ownerGroupId OKRオーナーグループID
     * @param integer $ownerCompanyId OKRオーナー会社ID
     * @return array
     */
    public function searchParentObjective(Auth $auth, string $keyword, int $timeframeId = null, string $ownerType, int $ownerUserId = null, int $ownerGroupId = null, int $ownerCompanyId = null): array
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        // OKR検索
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->searchParentObjective($escapedKeyword, $timeframeId, $auth->getCompanyId(), $ownerType, $ownerUserId, $ownerGroupId, $ownerCompanyId);

        // DTOに詰め替える
        $okrSearchDTOArray = array();
        foreach ($tOkrArray as $tOkr) {
            $okrSearchDTO = new OkrSearchDTO();
            $okrSearchDTO->setOkrId($tOkr['okr_id']);
            $okrSearchDTO->setOkrName($tOkr['name']);
            $okrSearchDTO->setOwnerType($tOkr['owner_type']);
            if ($tOkr['owner_type'] == DBConstant::OKR_OWNER_TYPE_USER) {
                $okrSearchDTO->setOwnerUserId($tOkr['user_id']);
                $okrSearchDTO->setOwnerUserName($tOkr['last_name'] . ' ' . $tOkr['first_name']);
            } elseif ($tOkr['owner_type'] == DBConstant::OKR_OWNER_TYPE_GROUP) {
                $okrSearchDTO->setOwnerGroupId($tOkr['group_id']);
                $okrSearchDTO->setOwnerGroupName($tOkr['group_name']);
            } else {
                $okrSearchDTO->setOwnerCompanyId($tOkr['company_id']);
                $okrSearchDTO->setOwnerCompanyName($tOkr['company_name']);
            }

            $okrSearchDTOArray[] = $okrSearchDTO;
        }

        return $okrSearchDTOArray;
    }
}
