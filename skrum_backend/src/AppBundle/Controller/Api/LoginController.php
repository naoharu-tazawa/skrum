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
     * 新規登録メール送信
     * （ルーティングアノテーション対象外）
     *
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function preregisterAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PreregisterPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // サブドメイン重複チェック
        $loginService = $this->getLoginService();
        $loginService->checkSubdomain($data['subdomain']);

        // 新規ユーザ登録メール送信処理
        $userSettingService = $this->getUserSettingService();
        $result = $userSettingService->preregisterUser($data['emailAddress'], $data['subdomain']);

        if ($result) {
            return array('result' => 'OK');
        } else {
            return array('result' => 'NG');
        }
    }

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
        $jwt = $loginService->login($data['emailAddress'], $data['password'], $this->getSubdomain($request));

        return array('jwt' => $jwt);
    }

    /**
     * 新規ユーザ登録
     *
     * @Rest\Post("/signup.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function signupAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/SignupPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 新規ユーザ登録処理
        $loginService = $this->getLoginService();
        $jwt = $loginService->signup($data['password'], $data['urltoken'], $this->getSubdomain($request));

        return array('jwt' => $jwt);
    }

    /**
     * 追加ユーザ登録
     *
     * @Rest\Post("/join.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function joinAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/SignupPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 追加ユーザ登録処理
        $loginService = $this->getLoginService();
        $jwt = $loginService->join($data['password'], $data['urltoken'], $this->getSubdomain($request));

        return array('jwt' => $jwt);
    }
}
