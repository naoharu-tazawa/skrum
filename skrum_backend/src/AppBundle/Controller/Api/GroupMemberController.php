<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Utils\Constant;
use AppBundle\Api\ResponseDTO\GroupMemberDTO;
use AppBundle\Api\ResponseDTO\NestedObject\MemberDTO;

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
     * @Rest\Post("/v1/groups/{groupId}/members.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return MemberDTO
     */
    public function postGroupMembersAction(Request $request, string $groupId): MemberDTO
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

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
        $checkResult = $permissionLogic->checkGroupOperation($auth, $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // OKR一覧取得
        $okrService = $this->getOkrService();
        $okrsArray = $okrService->getObjectivesAndKeyResults(Constant::SUBJECT_TYPE_USER, $auth, $data['userId'], null, $timeframeId, $auth->getCompanyId());

        // グループメンバー追加登録処理
        $groupMemberService = $this->getGroupMemberService();
        $memberDTO = $groupMemberService->addMember($mUser, $mGroup, $okrsArray);

        return $memberDTO;
    }

    /**
     * グループメンバー取得
     *
     * @Rest\Get("/v1/groups/{groupId}/members.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return GroupMemberDTO
     */
    public function getGroupMembersAction(Request $request, string $groupId): GroupMemberDTO
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistanceIncludingArchivedGroups($groupId, $auth->getCompanyId());

        // グループ基本情報取得
        $groupService = $this->getGroupService();
        $basicGroupInfoDTO = $groupService->getBasicGroupInfo($groupId, $auth->getCompanyId());

        // グループメンバー取得
        $groupMemberService = $this->getGroupMemberService();
        $memberDTOArray = $groupMemberService->getMembers($groupId, $timeframeId);

        // 返却DTOをセット
        $groupMemberDTO = new GroupMemberDTO();
        $groupMemberDTO->setGroup($basicGroupInfoDTO);
        $groupMemberDTO->setMembers($memberDTOArray);

        return $groupMemberDTO;
    }

    /**
     * グループメンバー削除
     *
     * @Rest\Delete("/v1/groups/{groupId}/members/{userId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @param string $userId ユーザID
     * @return array
     */
    public function deleteGroupMemberAction(Request $request, string $groupId, string $userId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // ユーザ存在チェック
        $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkGroupOperation($auth, $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // グループメンバー取得
        $groupMemberService = $this->getGroupMemberService();
        $groupMemberService->deleteMember($mGroup, $userId);

        return array('result' => 'OK');
    }
}