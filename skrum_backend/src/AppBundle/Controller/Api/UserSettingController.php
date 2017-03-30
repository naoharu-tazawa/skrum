<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;

/**
 * ユーザ設定コントローラ
 *
 * @author naoharu.tazawa
 */
class UserSettingController extends BaseController
{
    /**
     * 新規登録メール送信
     *
     * @Rest\Post("/preregister.{_format}")
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

        // URLトークンを生成
        $urltoken = $this->getToken();

        // ユーザ設定サービスを取得
        $userSettingService = $this->getUserSettingService();
        $result = $userSettingService->preregisterUser($data['emailAddress'], $urltoken);

        if ($result) {
            return array('result' => 'OK');
        } else {
            return array('result' => 'NG');
        }
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

        // ユーザ設定サービスを取得
        $userSettingService = $this->getUserSettingService();

        // 初期ユーザ登録処理
        $result = $userSettingService->signup($data['password'], $data['urltoken']);

        if ($result) {
            return array('result' => 'OK');
        } else {
            return array('result' => 'NG');
        }
    }

    /**
     * トークン取得
     *
     * @return string トークン
     */
    private function getToken()
    {
        return hash('sha256',uniqid(rand(),1));
    }
}
