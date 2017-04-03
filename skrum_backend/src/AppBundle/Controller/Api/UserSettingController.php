<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;
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
     * @Rest\Post("/invite.{_format}")
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
        $result = $userSettingService->preregisterUser($data['emailAddress'], $auth->getSubdomain(), $auth->getCompanyId(), $data['roleId']);

        if ($result) {
            return array('result' => 'OK');
        } else {
            return array('result' => 'NG');
        }
    }
}
