<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Utils\DBConstant;
use AppBundle\Api\ResponseDTO\UserGroupDTO;
use AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO;

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
     * @Rest\Post("/v1/groups.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return BasicGroupInfoDTO
     */
    public function postGroupsAction(Request $request): BasicGroupInfoDTO
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostGroupsPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループパス存在チェック
        $tGroupTree = null;
        if (array_key_exists('groupPathId', $data)) {
            $tGroupTree = $this->getDBExistanceLogic()->checkGroupPathExistance($data['groupPathId'], $auth->getCompanyId());
        }

        // 権限チェック
        if ($auth->getRoleLevel() <= DBConstant::ROLE_LEVEL_NORMAL && $data['groupType'] === DBConstant::GROUP_TYPE_DEPARTMENT) {
            throw new PermissionException('一般ユーザは部門の作成はできません');
        }

        // グループ新規登録処理
        $groupService = $this->getGroupService();
        $basicGroupInfoDTO = $groupService->createGroup($auth, $data, $tGroupTree);

        return $basicGroupInfoDTO;
    }

    /**
     * ユーザ所属グループ一覧取得
     *
     * @Rest\Get("/v1/users/{userId}/groups.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return UserGroupDTO
     */
    public function getUserGroupsAction(Request $request, string $userId): UserGroupDTO
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ基本情報取得
        $userService = $this->getUserService();
        $basicUserInfoDTO = $userService->getBasicUserInfo($userId, $auth->getCompanyId());

        // 所属グループリスト取得
        $groupMemberService = $this->getGroupMemberService();
        $groupDTOArray = $groupMemberService->getGroupsWithAchievementRate($userId, $timeframeId);

        // 返却DTOをセット
        $userGroupDTO = new UserGroupDTO();
        $userGroupDTO->setUser($basicUserInfoDTO);
        $userGroupDTO->setGroups($groupDTOArray);

        return $userGroupDTO;
    }

    /**
     * グループ基本情報変更
     *
     * @Rest\Put("/v1/groups/{groupId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return array
     */
    public function putGroupAction(Request $request, string $groupId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PutGroupPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkGroupOperation($auth, $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // グループ情報更新処理
        $groupService = $this->getGroupService();
        $groupService->updateGroup($data, $mGroup, $auth->getCompanyId());

        return array('result' => 'OK');
    }

    /**
     * グループリーダー変更
     *
     * @Rest\Put("/v1/groups/{groupId}/leaders/{userId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @param string $userId ユーザID
     * @return array
     */
    public function putGroupLeaderAction(Request $request, string $groupId, string $userId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // グループ存在チェック
        $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkGroupOperation($auth, $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // グループリーダー変更処理
        $groupService = $this->getGroupService();
        $groupService->changeGroupLeader($mGroup, $userId);

        return array('result' => 'OK');
    }

    /**
     * グループ削除
     *
     * @Rest\Delete("/v1/groups/{groupId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return array
     */
    public function deleteGroupAction(Request $request, string $groupId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkGroupOperation($auth, $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // グループ削除処理
        $groupService = $this->getGroupService();
        $groupService->deleteGroup($auth, $mGroup);

        return array('result' => 'OK');
    }
}
