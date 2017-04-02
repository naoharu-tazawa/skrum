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

        // 新規ユーザ登録メール送信処理
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

        // 新規ユーザ登録処理
        $userSettingService = $this->getUserSettingService();
        $userSettingService->signup($data['password'], $data['urltoken']);

        return array('result' => 'OK');
    }

    /**
     * ユーザ招待メール送信
     *
     * @Rest\Post("/invite.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function inviteAction(Request $request)
    {
        // JWTより会社コードを取得（ここはあとで修正）
        $companyId = 1;

        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/InvitePdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // URLトークンを生成
        $urltoken = $this->getToken();

        // ユーザ招待メール送信処理
        $userSettingService = $this->getUserSettingService();
        $result = $userSettingService->preregisterUser($data['emailAddress'], $urltoken, $companyId, $data['roleId']);

        if ($result) {
            return array('result' => 'OK');
        } else {
            return array('result' => 'NG');
        }
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
        $userSettingService = $this->getUserSettingService();
        $userSettingService->join($data['password'], $data['urltoken']);

        return array('result' => 'OK');
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
