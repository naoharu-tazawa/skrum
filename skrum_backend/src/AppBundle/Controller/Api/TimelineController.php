<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
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
     * タイムライン取得
     *
     * @Rest\Get("/v1/groups/{groupId}/posts.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return array
     */
    public function getGroupPostsAction(Request $request, string $groupId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistanceIncludingArchivedGroups($groupId, $auth->getCompanyId());

        // タイムライン取得処理
        $timelineService = $this->getTimelineService();
        $postDTOArray = $timelineService->getTimeline($auth, $groupId);

        return $postDTOArray;
    }

    /**
     * コメント投稿
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
        if ($tPost->getParent() != null) {
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
}
