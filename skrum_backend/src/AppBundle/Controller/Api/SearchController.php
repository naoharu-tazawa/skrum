<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Controller\BaseController;
use AppBundle\Utils\DBConstant;

/**
 * 検索コントローラ
 *
 * @author naoharu.tazawa
 */
class SearchController extends BaseController
{
    /**
     * ユーザ検索
     *
     * @Rest\Get("/users/search.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function searchUsersAction(Request $request)
    {
        // リクエストパラメータを取得
        $keyword = $request->get('q');

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ検索処理
        $searchService = $this->getSearchService();
        $userSearchDTOArray = $searchService->searchUser($auth, $keyword);

        return $userSearchDTOArray;
    }
}
