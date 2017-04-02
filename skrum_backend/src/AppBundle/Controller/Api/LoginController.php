<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;

/**
 * ログインコントローラ
 *
 * @author naoharu.tazawa
 */
class LoginController extends BaseController
{
    /**
     * ログイン
     *
     * @Rest\Post("/login.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function loginAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/LoginPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // ログイン処理
        $loginService = $this->getLoginService();
        $result = $loginService->login($data['emailAddress'], $data['password']);

        if ($result) {
            return array('result' => 'OK');
        } else {
            return array('result' => 'NG');
        }
    }
}
