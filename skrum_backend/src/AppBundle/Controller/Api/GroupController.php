<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;

/**
 * グループコントローラ
 *
 * @author naoharu.tazawa
 */
class GroupController extends BaseController
{
    /**
     * グループ新規登録
     *
     * @Rest\Post("/groups.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function postGroupsAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostGroupsPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ新規登録処理
        $groupService = $this->getGroupService();
        $groupService->createGroup($auth, $data);

        return array('result' => 'OK');
    }
}
