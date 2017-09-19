<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Api\ResponseDTO\PostDTO;

/**
 * タイムラインコントローラ
 *
 * @author naoharu.tazawa
 */
class TimelineController extends BaseController
{
    /**
     * タイムライン取得（ユーザ）
     *
     * @Rest\Get("/v1/users/{userId}/posts.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return array
     */
    public function getUserPostsAction(Request $request, string $userId): array
    {
        // リクエストパラメータを取得
        $before = $request->get('before'); // 投稿ID

        // リクエストパラメータのバリデーション
        if ($before !== null) {
            $beforeErrors = $this->checkIntID($before);
            if($beforeErrors) throw new InvalidParameterException("タイムライン取得基準投稿IDが不正です", $beforeErrors);
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // タイムライン取得処理
        $timelineService = $this->getTimelineService();
        $postDTOArray = $timelineService->getUserTimeline($auth, $before);

        return $postDTOArray;
    }

    /**
     * タイムライン取得（グループ）
     *
     * @Rest\Get("/v1/groups/{groupId}/posts.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return array
     */
    public function getGroupPostsAction(Request $request, string $groupId): array
    {
        // リクエストパラメータを取得
        $before = $request->get('before'); // 投稿ID

        // リクエストパラメータのバリデーション
        if ($before !== null) {
            $beforeErrors = $this->checkIntID($before);
            if($beforeErrors) throw new InvalidParameterException("タイムライン取得基準投稿IDが不正です", $beforeErrors);
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistanceIncludingArchivedGroups($groupId, $auth->getCompanyId());

        // タイムライン取得処理
        $timelineService = $this->getTimelineService();
        $postDTOArray = $timelineService->getTimeline($auth, $groupId, $before);

        return $postDTOArray;
    }

    /**
     * タイムライン取得（会社）
     *
     * @Rest\Get("/v1/companies/{companyId}/posts.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return array
     */
    public function getCompanyPostsAction(Request $request, string $companyId): array
    {
        // リクエストパラメータを取得
        $before = $request->get('before'); // 投稿ID

        // リクエストパラメータのバリデーション
        if ($before !== null) {
            $beforeErrors = $this->checkIntID($before);
            if($beforeErrors) throw new InvalidParameterException("タイムライン取得基準投稿IDが不正です", $beforeErrors);
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // タイムライン取得処理
        $timelineService = $this->getTimelineService();
        $postDTOArray = $timelineService->getCompanyTimeline($auth, $companyId, $before);

        return $postDTOArray;
    }

    /**
     * コメント投稿（グループ）
     *
     * @Rest\Post("/v1/groups/{groupId}/posts.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return PostDTO
     */
    public function postGroupPostsAction(Request $request, string $groupId): PostDTO
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/CommentPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // コメント登録処理
        $timelineService = $this->getTimelineService();
        $postDTO = $timelineService->postComment($auth, $data, $groupId);

        return $postDTO;
    }

    /**
     * コメント投稿（会社）
     *
     * @Rest\Post("/v1/companies/{companyId}/posts.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return PostDTO
     */
    public function postCompanyPostsAction(Request $request, string $companyId): PostDTO
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/CommentPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // コメント登録処理
        $timelineService = $this->getTimelineService();
        $postDTO = $timelineService->postCompanyComment($auth, $data, $companyId);

        return $postDTO;
    }

    /**
     * リプライ投稿
     *
     * @Rest\Post("/v1/posts/{postId}/replies.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string postId 投稿ID
     * @return $postDTO
     */
    public function postPostRepliesAction(Request $request, string $postId): PostDTO
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/ReplyPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 投稿存在チェック
        $tPost = $this->getDBExistanceLogic()->checkPostExistance($postId, $auth->getCompanyId());

        // リプライ対象投稿がリプライの場合、リプライ不可
        if ($tPost->getParent() !== null) {
            throw new ApplicationException('リプライ投稿に対してリプライはできません');
        }

        // リプライ登録処理
        $timelineService = $this->getTimelineService();
        $postDTO = $timelineService->postReply($auth, $data, $tPost);

        return $postDTO;
    }

    /**
     * いいね
     *
     * @Rest\Post("/v1/posts/{postId}/likes.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string postId 投稿ID
     * @return array
     */
    public function postPostLikesAction(Request $request, string $postId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 投稿存在チェック
        $tPost = $this->getDBExistanceLogic()->checkPostExistance($postId, $auth->getCompanyId());

        // いいね登録処理
        $timelineService = $this->getTimelineService();
        $timelineService->like($auth->getUserId(), $tPost);

        return array('result' => 'OK');
    }

    /**
     * いいね解除
     *
     * @Rest\Delete("/v1/posts/{postId}/like.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string postId 投稿ID
     * @return array
     */
    public function deletePostLikeAction(Request $request, string $postId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 投稿存在チェック
        $tPost = $this->getDBExistanceLogic()->checkPostExistance($postId, $auth->getCompanyId());

        // いいね削除処理
        $timelineService = $this->getTimelineService();
        $timelineService->detachLike($auth->getUserId(), $tPost->getId());

        return array('result' => 'OK');
    }

    /**
     * 投稿削除
     *
     * @Rest\Delete("/v1/posts/{postId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string postId 投稿ID
     * @return array
     */
    public function deletePostAction(Request $request, string $postId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 投稿存在チェック
        $tPost = $this->getDBExistanceLogic()->checkPostExistance($postId, $auth->getCompanyId());

        // ユーザ/グループ/会社操作権限一括チェック
        $permissionLogic = $this->getPermissionLogic();
        $permissionLogic->checkUserGroupCompanyPostOperationSelfOK($auth, $tPost);

        // 投稿削除処理
        $timelineService = $this->getTimelineService();
        $timelineService->deletePost($tPost);

        return array('result' => 'OK');
    }

    /**
     * 投稿公開設定変更
     *
     * @Rest\Put("/v1/posts/{postId}/changedisclosure.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string postId 投稿ID
     * @return array
     */
    public function changePostDisclosureAction(Request $request, string $postId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/ChangeOkrDisclosurePdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 投稿存在チェック
        $tPost = $this->getDBExistanceLogic()->checkPostExistance($postId, $auth->getCompanyId());

        // リプライの公開設定変更は不可
        if ($tPost->getParent() !== null) {
            throw new ApplicationException('リプライ投稿に対しての公開設定変更はできません');
        }

        // ユーザ/グループ/会社操作権限一括チェック
        $permissionLogic = $this->getPermissionLogic();
        $permissionLogic->checkUserGroupCompanyPostOperationSelfOK($auth, $tPost);

        // 投稿公開設定変更処理
        $timelineService = $this->getTimelineService();
        $timelineService->changeDisclosure($tPost, $data['disclosureType']);

        return array('result' => 'OK');
    }
}
