<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\JsonSchemaException;

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
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function preregisterAction(Request $request): array
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
        $userSettingService->preregisterUser($data['emailAddress'], $data['subdomain']);

        return array('result' => 'OK');
    }

    /**
     * ログイン
     *
     * @Rest\Post("/v1/login.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function loginAction(Request $request): array
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
     * @Rest\Post("/v1/signup.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function signupAction(Request $request): array
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
     * @Rest\Post("/v1/join.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function joinAction(Request $request): array
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
