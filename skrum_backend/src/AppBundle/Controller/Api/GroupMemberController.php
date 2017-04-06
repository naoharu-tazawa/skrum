<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\PermissionException;
use AppBundle\Api\ResponseDTO\GroupMemberDTO;

/**
 * グループメンバーコントローラ
 *
 * @author naoharu.tazawa
 */
class GroupMemberController extends BaseController
{
    /**
     * グループメンバー追加
     *
     * @Rest\Post("/groups/{groupId}/members.{_format}")
     * @param $request リクエストオブジェクト
     * @param $groupId グループID
     * @return array
     */
    public function postGroupMembersAction(Request $request, $groupId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostGroupMembersPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $mUser = $this->getDBExistanceLogic()->checkUserExistance($data['userId'], $auth->getCompanyId());

        // グループ存在チェック
        $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkGroupOperation($auth->getUserId(), $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // グループメンバー追加登録処理
        $groupMemberService = $this->getGroupMemberService();
        $groupMemberService->addMember($mUser, $mGroup);

        return array('result' => 'OK');
    }

    /**
     * グループメンバー取得
     *
     * @Rest\Get("/groups/{groupId}/members.{_format}")
     * @param $request リクエストオブジェクト
     * @param $groupId グループID
     * @return array
     */
    public function getGroupMembersAction(Request $request, $groupId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // グループ基本情報取得
        $groupService = $this->getGroupService();
        $basicGroupInfoDTO = $groupService->getBasicGroupInfo($groupId, $auth->getCompanyId());

        // グループメンバー取得
        $groupMemberService = $this->getGroupMemberService();
        $memberDTOArray = $groupMemberService->getMembers($groupId);

        // 返却DTOをセット
        $groupMemberDTO = new GroupMemberDTO();
        $groupMemberDTO->setGroup($basicGroupInfoDTO);
        $groupMemberDTO->setMembers($memberDTOArray);

        return $groupMemberDTO;
    }

    /**
     * グループメンバー削除
     *
     * @Rest\Delete("/groups/{groupId}/members/{userId}.{_format}")
     * @param $request リクエストオブジェクト
     * @param $groupId グループID
     * @param $userId ユーザID
     * @return array
     */
    public function deleteGroupMemberAction(Request $request, $groupId, $userId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // ユーザ存在チェック
        $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // グループメンバー取得
        $groupMemberService = $this->getGroupMemberService();
        $groupMemberService->deleteMember($groupId, $userId);

        return array('result' => 'OK');
    }
}