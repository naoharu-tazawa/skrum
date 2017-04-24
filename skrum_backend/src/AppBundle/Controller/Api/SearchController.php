<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Api\ResponseDTO\GroupPageSearchDTO;
use AppBundle\Api\ResponseDTO\UserPageSearchDTO;

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
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function searchUsersAction(Request $request): array
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
     * ユーザ検索（ページング）
     *
     * @Rest\Get("/v1/users/pagesearch.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function pagesearchUsersAction(Request $request): UserPageSearchDTO
    {
        // リクエストパラメータを取得
        $keyword = $request->get('q');
        $page = $request->get('p');

        // リクエストパラメータのバリデーション
        $errors = $this->checkNumeric($page);
        if($errors) throw new InvalidParameterException('要求ページの値が不正です', $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ検索処理
        $searchService = $this->getSearchService();
        $userPageSearchDTO = $searchService->pagesearchUser($auth, $keyword, (int) $page);

        return $userPageSearchDTO;
    }

    /**
     * グループ検索
     *
     * @Rest\Get("/v1/groups/search.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function searchGroupAction(Request $request): array
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
     * グループ検索（ページング）
     *
     * @Rest\Get("/v1/groups/pagesearch.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function pagesearchGroupAction(Request $request): GroupPageSearchDTO
    {
        // リクエストパラメータを取得
        $keyword = $request->get('q');
        $page = $request->get('p');

        // リクエストパラメータのバリデーション
        $errors = $this->checkNumeric($page);
        if($errors) throw new InvalidParameterException('要求ページの値が不正です', $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ検索処理
        $searchService = $this->getSearchService();
        $groupPageSearchDTO = $searchService->pagesearchGroup($auth, $keyword, (int) $page);

        return $groupPageSearchDTO;
    }

    /**
     * オーナー検索
     *
     * @Rest\Get("/v1/owners/search.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function searchOwnerAction(Request $request): array
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
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function searchPathsAction(Request $request): array
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
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function searchOkrsAction(Request $request): array
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
