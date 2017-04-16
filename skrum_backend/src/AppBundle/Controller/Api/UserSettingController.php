<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\JsonSchemaException;
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
        $result = $userSettingService->preregisterUser($data['emailAddress'], $auth->getSubdomain(), $auth->getCompanyId(), $data['roleAssignmentId']);

        if ($result) {
            return array('result' => 'OK');
        } else {
            return array('result' => 'NG');
        }
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
}
