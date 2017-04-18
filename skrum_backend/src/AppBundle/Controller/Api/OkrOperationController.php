<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Utils\DBConstant;

/**
 * OKR操作コントローラ
 *
 * @author naoharu.tazawa
 */
class OkrOperationController extends BaseController
{
    /**
     * 紐付け先OKR変更
     *
     * @Rest\Put("/v1/okrs/{okrId}/changeparent.{_format}")
     * @param $request リクエストオブジェクト
     * @param $okrId OKRID
     * @return array
     */
    public function changeOkrParentAction(Request $request, $okrId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/ChangeOkrParentPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());
        $newParentOkr = $this->getDBExistanceLogic()->checkOkrExistance($data['newParentOkrId'], $auth->getCompanyId());

        // 操作権限チェック
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $permissionLogic = $this->getPermissionLogic();
            $checkResult = $permissionLogic->checkUserOperation($auth->getUserId(), $tOkr->getOwnerUser()->getUserId());
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // 紐付け先OKR変更処理
        $okrOperationService = $this->getOkrOperationService();
        $okrOperationService->changeParent($auth, $tOkr, $newParentOkr);

        return array('result' => 'OK');
    }
}
