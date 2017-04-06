<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Api\ResponseDTO\UserGroupDTO;

/**
 * グループコントローラ
 *
 * @author naoharu.tazawa
 */
class GroupController extends BaseController
{
    /**
     * グループ新規登録
     *
     * @Rest\Post("/groups.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function postGroupsAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostGroupsPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ新規登録処理
        $groupService = $this->getGroupService();
        $groupService->createGroup($auth, $data);

        return array('result' => 'OK');
    }

    /**
     * ユーザ所属グループ一覧取得
     *
     * @Rest\Get("/users/{userId}/groups.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @return array
     */
    public function getUserGroupsAction(Request $request, $userId)
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザIDの一致をチェック
        if ($userId != $auth->getUserId()) {
            throw new ApplicationException('ユーザIDが存在しません');
        }

        // ユーザ基本情報取得
        $userService = $this->getUserService();
        $basicUserInfoDTO = $userService->getBasicUserInfo($userId, $auth->getCompanyId());

        // 所属グループリスト取得
        $groupMemberService = $this->getGroupMemberService();
        $groupDTOArray = $groupMemberService->getGroups($userId, $timeframeId);

        // 返却DTOをセット
        $userGroupDTO = new UserGroupDTO();
        $userGroupDTO->setUser($basicUserInfoDTO);
        $userGroupDTO->setGroups($groupDTOArray);

        return $userGroupDTO;
    }
}
