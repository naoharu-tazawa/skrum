<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;

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
     * @Rest\Get("/v1/users/search.{_format}")
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

    /**
     * グループ検索
     *
     * @Rest\Get("/v1/groups/search.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function searchGroupAction(Request $request)
    {
        // リクエストパラメータを取得
        $keyword = $request->get('q');

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ検索処理
        $searchService = $this->getSearchService();
        $groupSearchDTOArray = $searchService->searchGroup($auth, $keyword);

        return $groupSearchDTOArray;
    }

    /**
     * オーナー検索
     *
     * @Rest\Get("/v1/owners/search.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function searchOwnerAction(Request $request)
    {
        // リクエストパラメータを取得
        $keyword = $request->get('q');

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // オーナー検索処理
        $searchService = $this->getSearchService();
        $ownerSearchDTOArray = $searchService->searchOwner($auth, $keyword);

        return $ownerSearchDTOArray;
    }

    /**
     * 所属先グループ検索
     *
     * @Rest\Get("/v1/paths/search.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function searchPathsAction(Request $request)
    {
        // リクエストパラメータを取得
        $keyword = $request->get('q');

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ検索処理
        $searchService = $this->getSearchService();
        $groupTreeSearchDTOArray = $searchService->searchGroupTree($auth, $keyword);

        return $groupTreeSearchDTOArray;
    }

    /**
     * OKR検索
     *
     * @Rest\Get("/v1/okrs/search.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function searchOkrsAction(Request $request)
    {
        // リクエストパラメータを取得
        $okrId = $request->get('oid');
        $keyword = $request->get('q');

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // グループ検索処理
        $searchService = $this->getSearchService();
        $okrSearchDTOArray = $searchService->searchOkr($auth, $keyword, $tOkr);

        return $okrSearchDTOArray;
    }
}
