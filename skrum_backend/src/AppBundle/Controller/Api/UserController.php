<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Controller\BaseController;
use AppBundle\Utils\DBConstant;

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
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @return array
     */
    public function putUserAction(Request $request, $userId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PutUserPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $mUser = $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // ユーザ情報更新処理
        $userService = $this->getUserService();
        $userService->updateUser($data, $mUser);

        return array('result' => 'OK');
    }

    /**
     * ユーザ削除
     *
     * @Rest\Delete("/v1/users/{userId}.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @return array
     */
    public function deleteUserAction(Request $request, $userId)
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
            $checkResult = $permissionLogic->checkUserOperation($auth->getUserId(), $userId);
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // ユーザ削除処理
        $userService = $this->getUserService();
        $userService->deleteUser($mUser);

        return array('result' => 'OK');
    }
}
