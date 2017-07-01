<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Api\ResponseDTO\GroupPageSearchDTO;
use AppBundle\Api\ResponseDTO\UserPageSearchDTO;
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
     * @Rest\Get("/v1/users/search.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function searchUsersAction(Request $request): array
    {
        // リクエストパラメータを取得
        $keyword = $request->get('q');

        // リクエストパラメータのバリデーション
        $errors = $this->checkSearchKeyword($keyword);
        if($errors) throw new InvalidParameterException("検索キーワードが不正です", $errors);

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
        $errors1 = $this->checkSearchKeyword($keyword);
        if($errors1) throw new InvalidParameterException("検索キーワードが不正です", $errors1);
        $errors2 = $this->checkNumeric($page);
        if($errors2) throw new InvalidParameterException('要求ページの値が不正です', $errors2);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ検索処理
        $searchService = $this->getSearchService();
        $userPageSearchDTO = $searchService->pagesearchUser($auth, $keyword, $page);

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

        // リクエストパラメータのバリデーション
        $errors = $this->checkSearchKeyword($keyword);
        if($errors) throw new InvalidParameterException("検索キーワードが不正です", $errors);

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
        $errors1 = $this->checkSearchKeyword($keyword);
        if($errors1) throw new InvalidParameterException("検索キーワードが不正です", $errors1);
        $errors2 = $this->checkNumeric($page);
        if($errors2) throw new InvalidParameterException('要求ページの値が不正です', $errors2);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ検索処理
        $searchService = $this->getSearchService();
        $groupPageSearchDTO = $searchService->pagesearchGroup($auth, $keyword, $page);

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

        // リクエストパラメータのバリデーション
        $errors = $this->checkSearchKeyword($keyword);
        if($errors) throw new InvalidParameterException("検索キーワードが不正です", $errors);

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

        // リクエストパラメータのバリデーション
        $errors = $this->checkSearchKeyword($keyword);
        if($errors) throw new InvalidParameterException("検索キーワードが不正です", $errors);

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
        $timeframeId = $request->get('tfid');
        $okrId = $request->get('oid');
        $keyword = $request->get('q');

        // リクエストパラメータのバリデーション
        if ($timeframeId !== null) {
            $timeframeIdErrors = $this->checkIntID($timeframeId);
            if($timeframeIdErrors) throw new InvalidParameterException("タイムフレームIDが不正です", $timeframeIdErrors);
        }
        if ($okrId !== null) {
            $okrIdErrors = $this->checkIntID($okrId);
            if($okrIdErrors) throw new InvalidParameterException("OKRIDが不正です", $okrIdErrors);
        }
        $keywordErrors = $this->checkSearchKeyword($keyword);
        if($keywordErrors) throw new InvalidParameterException("検索キーワードが不正です", $keywordErrors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // タイムフレームID設定
        if ($okrId !== null) {
            // OKR存在チェック
            $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

            $timeframeId = $tOkr->getTimeframe()->getTimeframeId();
        }

        // グループ検索処理
        $searchService = $this->getSearchService();
        $okrSearchDTOArray = $searchService->searchOkr($auth, $keyword, $timeframeId);

        return $okrSearchDTOArray;
    }

    /**
     * 紐付け先OKR検索（新規目標登録時・紐付け先変更時(対象:Objective)兼用）
     *
     * @Rest\Get("/v1/parentokrs/search.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function searchParentokrsAction(Request $request): array
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid'); // タイムフレームID
        $okrId = $request->get('oid'); // OKRID
        $ownerType = $request->get('wtype'); // オーナー種別
        $ownerId = $request->get('wid'); // オーナー(ユーザ/グループ/会社)ID
        $keyword = $request->get('q'); // 検索ワード

        // リクエストパラメータのバリデーション

        // タイムフレームIDが存在する場合（新規目標登録時）
        if ($timeframeId !== null) {
            // タイムフレームIDチェック
            $timeframeIdErrors = $this->checkIntID($timeframeId);
            if($timeframeIdErrors) throw new InvalidParameterException("タイムフレームIDが不正です", $timeframeIdErrors);

            // オーナー種別チェック
            $ownerTypeErrors = $this->checkNumeric($ownerType);
            if($ownerTypeErrors) throw new InvalidParameterException("オーナー種別が不正です", $ownerTypeErrors);

            // オーナー(ユーザ/グループ/会社)IDチェック
            $ownerIdErrors = $this->checkIntID($ownerId);
            if($ownerIdErrors) throw new InvalidParameterException("オーナー(ユーザ/グループ/会社)IDが不正です", $ownerIdErrors);
        }

        // OKRIDが存在する場合（紐付け先変更時(対象:Objective)）
        if ($okrId !== null) {
            // OKRIDチェック
            $okrIdErrors = $this->checkIntID($okrId);
            if($okrIdErrors) throw new InvalidParameterException("OKRIDが不正です", $okrIdErrors);
        }

        // 検索ワードチェック
        $keywordErrors = $this->checkSearchKeyword($keyword);
        if($keywordErrors) throw new InvalidParameterException("検索キーワードが不正です", $keywordErrors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 変数を初期化
        $ownerUserId = null;
        $ownerGroupId = null;
        $ownerCompanyId = null;

        // オーナー(ユーザ/グループ/会社)IDを変数に格納
        if ($timeframeId !== null) {
            if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
                $ownerUserId = $ownerId;
            } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $ownerGroupId = $ownerId;
            } else {
                $ownerCompanyId = $ownerId;
            }
        }

        // タイムフレームID設定
        if ($okrId !== null) {
            // OKR存在チェック
            $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

            $ownerType = $tOkr->getOwnerType();
            if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
                $ownerUserId = $tOkr->getOwnerUser()->getUserId();
            } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $ownerGroupId = $tOkr->getOwnerGroup()->getGroupId();
            } else {
                $ownerCompanyId = $tOkr->getOwnerCompanyId();
            }

            $timeframeId = $tOkr->getTimeframe()->getTimeframeId();
        }

        // グループ検索処理
        $searchService = $this->getSearchService();
        $okrSearchDTOArray = $searchService->searchParentOkr($auth, $keyword, $timeframeId, $ownerType, $ownerUserId, $ownerGroupId, $ownerCompanyId);

        return $okrSearchDTOArray;
    }

    /**
     * 紐付け先Objective検索（紐付け先変更時(対象:Key Result)用）
     *
     * @Rest\Get("/v1/parentobjectives/search.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function searchParentobjectivesAction(Request $request): array
    {
        // リクエストパラメータを取得
        $okrId = $request->get('krid');
        $keyword = $request->get('q');

        // リクエストパラメータのバリデーション
        $okrIdErrors = $this->checkIntID($okrId);
        if($okrIdErrors) throw new InvalidParameterException("OKRIDが不正です", $okrIdErrors);
        $keywordErrors = $this->checkSearchKeyword($keyword);
        if($keywordErrors) throw new InvalidParameterException("検索キーワードが不正です", $keywordErrors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // オーナー(ユーザ/グループ/会社)IDを設定
        $ownerUserId = null;
        $ownerGroupId = null;
        $ownerCompanyId = null;
        $ownerType = $tOkr->getOwnerType();
        if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
            $ownerUserId = $tOkr->getOwnerUser()->getUserId();
        } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
            $ownerGroupId = $tOkr->getOwnerGroup()->getGroupId();
        } else {
            $ownerCompanyId = $tOkr->getOwnerCompanyId();
        }

        // タイムフレームID設定
        $timeframeId = $tOkr->getTimeframe()->getTimeframeId();

        // グループ検索処理
        $searchService = $this->getSearchService();
        $okrSearchDTOArray = $searchService->searchParentObjective($auth, $keyword, $timeframeId, $ownerType, $ownerUserId, $ownerGroupId, $ownerCompanyId);

        return $okrSearchDTOArray;
    }
}
