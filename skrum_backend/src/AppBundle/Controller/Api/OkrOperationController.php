<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Api\ResponseDTO\OkrDetailsDTO;

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
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function changeOkrParentAction(Request $request, string $okrId): OkrDetailsDTO
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

        // ユーザ/グループ/会社操作権限一括チェック
        $permissionLogic = $this->getPermissionLogic();
        $permissionLogic->checkUserGroupCompanyOperationSelfOK($auth, $tOkr);

        // 紐付け先OKR変更処理
        $okrOperationService = $this->getOkrOperationService();
        $okrDetailsDTO = $okrOperationService->changeParent($auth, $tOkr, $newParentOkr);

        return $okrDetailsDTO;
    }
}
