<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
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
}
