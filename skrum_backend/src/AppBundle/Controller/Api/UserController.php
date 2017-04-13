<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;
use AppBundle\Utils\Permission;
use AppBundle\Exception\ApplicationException;

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
     * @Rest\Put("/users/{userId}.{_format}")
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
}
