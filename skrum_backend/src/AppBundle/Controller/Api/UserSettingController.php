<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Utils\Permission;

/**
 * ユーザ設定コントローラ
 *
 * @author naoharu.tazawa
 */
class UserSettingController extends BaseController
{
    /**
     * ユーザ招待メール送信
     *
     * @Rest\Post("/v1/invite.{_format}")
     * @Permission(value="user_add")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function inviteAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/InvitePdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ招待メール送信処理
        $userSettingService = $this->getUserSettingService();
        $userSettingService->inviteUser($data['emailAddress'], $auth->getSubdomain(), $auth->getCompanyId(), $data['roleAssignmentId']);

        return array('result' => 'OK');
    }

    /**
     * 初期設定登録
     *
     * @Rest\Post("/v1/companies/{companyId}/establish.{_format}")
     * @param $request リクエストオブジェクト
     * @param $companyId 会社ID
     * @return array
     */
    public function establishCompanyAction(Request $request, $companyId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/EstablishCompanyPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // 初期設定登録処理
        $userSettingService = $this->getUserSettingService();
        $userSettingService->establishCompany($auth, $data['user'], $data['company'], $data['timeframe']);

        return array('result' => 'OK');
    }

    /**
     * 追加ユーザ初期設定登録
     *
     * @Rest\Post("/v1/users/{userId}/establish.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @return array
     */
    public function establishUserAction(Request $request, $userId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/EstablishUserPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザIDの一致をチェック
        if ($userId != $auth->getUserId()) {
            throw new ApplicationException('ユーザIDが存在しません');
        }

        // 追加ユーザ初期設定登録処理
        $userSettingService = $this->getUserSettingService();
        $userSettingService->establishUser($auth, $data['user']);

        return array('result' => 'OK');
    }

    /**
     * パスワードリセット
     *
     * @Rest\Post("/v1/users/{userId}/resetpassword.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @return array
     */
    public function resetUserPasswordAction(Request $request, $userId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $mUser = $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkUserOperation($auth->getUserId(), $userId);
        if (!$checkResult) {
            throw new PermissionException('ユーザ操作権限がありません');
        }

        // パスワード変更処理
        $userSettingService = $this->getUserSettingService();
        $userSettingService->resetPassword($auth, $mUser);

        return array('result' => 'OK');
    }

    /**
     * パスワード変更
     *
     * @Rest\Post("/v1/users/{userId}/changepassword.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @return array
     */
    public function changeUserPasswordAction(Request $request, $userId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/ChangeUserPasswordPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザIDの一致をチェック
        if ($userId != $auth->getUserId()) {
            throw new ApplicationException('ユーザIDが存在しません');
        }

        // パスワード変更処理
        $userSettingService = $this->getUserSettingService();
        $userSettingService->changePassword($auth, $data['currentPassword'], $data['newPassword']);

        return array('result' => 'OK');
    }

    /**
     * ロール一覧取得
     *
     * @Rest\Get("/v1/companies/{companyId}/roles.{_format}")
     * @param $request リクエストオブジェクト
     * @param $companyId 会社ID
     * @return array
     */
    public function getCompanyRolesAction(Request $request, $companyId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // ロール一覧取得処理
        $userSettingService = $this->getUserSettingService();
        $roleDTOArray = $userSettingService->getRoles($companyId);

        return $roleDTOArray;
    }

    /**
     * ユーザ権限変更
     *
     * @Rest\Put("/v1/users/{userId}/roles/{roleAssignmentId}.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @param $roleAssignmentId ロール割当ID
     * @return array
     */
    public function putUserRoleAction(Request $request, $userId, $roleAssignmentId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $mUser = $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // ロール割当存在チェック
        $mRoleAssignment = $this->getDBExistanceLogic()->checkRoleAssignmentExistance($roleAssignmentId, $auth->getCompanyId());

        // ユーザ権限更新処理
        $userSettingService = $this->getUserSettingService();
        $userSettingService->changeRole($auth, $mUser, $mRoleAssignment);

        return array('result' => 'OK');
    }
}
