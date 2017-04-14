<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Api\ResponseDTO\UserGroupDTO;

/**
 * グループツリーコントローラ
 *
 * @author naoharu.tazawa
 */
class GroupTreeController extends BaseController
{
    /**
     * 所属先グループ追加
     *
     * @Rest\Post("/groups/{groupId}/paths.{_format}")
     * @param $request リクエストオブジェクト
     * @param integer $groupId グループID
     * @return array
     */
    public function postGroupPathsAction(Request $request, $groupId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostGroupPathsPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // グループパス存在チェック
        $tGroupTree = $this->getDBExistanceLogic()->checkGroupPathExistance($data['groupPathId'], $auth->getCompanyId());

        // グループ新規登録処理
        $groupTreeService = $this->getGroupTreeService();
        $groupTreeService->createGroupPath($mGroup, $tGroupTree->getGroupTreePath(), $tGroupTree->getGroupTreePathName());

        return array('result' => 'OK');
    }
}