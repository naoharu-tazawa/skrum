<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Api\ResponseDTO\UserSearchDTO;
use AppBundle\Api\ResponseDTO\GroupSearchDTO;
use AppBundle\Api\ResponseDTO\GroupTreeSearchDTO;
use AppBundle\Api\ResponseDTO\OkrSearchDTO;
use AppBundle\Api\ResponseDTO\OwnerSearchDTO;
use AppBundle\Utils\DBConstant;

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
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchUser($auth, $keyword)
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
     * グループ検索
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchGroup($auth, $keyword)
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
     * オーナー検索
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchOwner($auth, $keyword)
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
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchGroupTree($auth, $keyword)
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
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @return array
     */
    public function searchOkr($auth, $keyword, $timeframeId)
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
            $okrSearchDTO->setOkrId($tOkr['okrId']);
            $okrSearchDTO->setOkrName($tOkr['name']);

            $okrSearchDTOArray[] = $okrSearchDTO;
        }

        return $okrSearchDTOArray;
    }
}
