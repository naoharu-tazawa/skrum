<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Utils\DBConstant;
use AppBundle\Utils\Permission;

/**
 * ユーザコントローラ
 *
 * @author naoharu.tazawa
 */
class UserController extends BaseController
{
    /**
     * ユーザ基本情報変更
     *
     * @Rest\Put("/v1/users/{userId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return array
     */
    public function putUserAction(Request $request, string $userId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PutUserPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $mUser = $this->getDBExistanceLogic()->checkUserExistanceIncludingArchivedUsers($userId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkUserOperationSelfOK($auth, $userId);
        if (!$checkResult) {
            throw new PermissionException('ユーザ操作権限がありません');
        }

        // ユーザ情報更新処理
        $userService = $this->getUserService();
        $userService->updateUser($data, $mUser);

        return array('result' => 'OK');
    }

    /**
     * ユーザ削除
     *
     * @Rest\Delete("/v1/users/{userId}.{_format}")
     * @Permission(value="user_delete")
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return array
     */
    public function deleteUserAction(Request $request, string $userId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $mUser = $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // 操作権限チェック
        // 自ユーザ削除か他ユーザ削除で権限チェックロジックを分岐
        if ($auth->getUserId() == $userId) {
            // スーパー管理者ユーザは自ユーザ削除できない
            if ($auth->getRoleLevel() >= DBConstant::ROLE_LEVEL_SUPERADMIN) {
                throw new PermissionException('スーパー管理者ユーザは自ユーザを削除できません');
            }
        } else {
            // 権限ロジックでチェック
            $permissionLogic = $this->getPermissionLogic();
            $checkResult = $permissionLogic->checkUserOperationSelfOK($auth, $userId);
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // ユーザ削除処理
        $userService = $this->getUserService();
        $userService->deleteUser($mUser, $auth->getCompanyId());

        return array('result' => 'OK');
    }
}
