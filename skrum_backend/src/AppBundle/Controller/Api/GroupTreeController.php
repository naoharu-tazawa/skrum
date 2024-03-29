<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathDTO;

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
     * @Rest\Post("/v1/groups/{groupId}/paths.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return array
     */
    public function postGroupPathsAction(Request $request, string $groupId): GroupPathDTO
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

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkGroupOperation($auth, $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // グループ新規登録処理
        $groupTreeService = $this->getGroupTreeService();
        $groupPathDTO = $groupTreeService->createGroupPath($auth, $mGroup, $tGroupTree->getGroupTreePath(), $tGroupTree->getGroupTreePathName());

        return $groupPathDTO;
    }

    /**
     * 所属先グループ削除
     *
     * @Rest\Delete("/v1/groups/{groupId}/paths/{groupTreeId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @param string $groupTreeId グループツリーID
     * @return array
     */
    public function deleteGroupPathAction(Request $request, string $groupId, string $groupTreeId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // グループパス存在チェック
        $tGroupTree = $this->getDBExistanceLogic()->checkGroupPathExistance($groupTreeId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkGroupOperation($auth, $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // グループパス削除処理
        $groupTreeService = $this->getGroupTreeService();
        $groupTreeService->deleteGroupPath($tGroupTree, $groupId);

        return array('result' => 'OK');
    }
}
