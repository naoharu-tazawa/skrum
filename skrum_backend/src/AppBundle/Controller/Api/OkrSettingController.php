<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Controller\BaseController;
use AppBundle\Utils\DBConstant;

/**
 * OKR設定コントローラ
 *
 * @author naoharu.tazawa
 */
class OkrSettingController extends BaseController
{
    /**
     * OKRクローズ
     *
     * @Rest\Put("/okrs/{okrId}/close.{_format}")
     * @param $request リクエストオブジェクト
     * @param integer $okrId OKRID
     * @return array
     */
    public function putOkrCloseAction(Request $request, $okrId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // 操作権限チェック
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $permissionLogic = $this->getPermissionLogic();
            $checkResult = $permissionLogic->checkUserOperation($auth->getUserId(), $tOkr->getOwnerUser()->getUserId());
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // OKRクローズ処理
        $okrSettingService = $this->getOkrSettingService();
        $okrSettingService->closeOkr($tOkr);

        return array('result' => 'OK');
    }

    /**
     * OKRオープン
     *
     * @Rest\Put("/okrs/{okrId}/open.{_format}")
     * @param $request リクエストオブジェクト
     * @param integer $okrId OKRID
     * @return array
     */
    public function putOkrOpenAction(Request $request, $okrId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // 操作権限チェック
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $permissionLogic = $this->getPermissionLogic();
            $checkResult = $permissionLogic->checkUserOperation($auth->getUserId(), $tOkr->getOwnerUser()->getUserId());
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // OKRクローズ処理
        $okrSettingService = $this->getOkrSettingService();
        $okrSettingService->openOkr($tOkr);

        return array('result' => 'OK');
    }

    /**
     * OKR公開設定変更
     *
     * @Rest\Put("/okrs/{okrId}/changedisclosure.{_format}")
     * @param $request リクエストオブジェクト
     * @param integer $okrId OKRID
     * @return array
     */
    public function changeOkrDisclosureAction(Request $request, $okrId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/ChangeOkrDisclosurePdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // 操作権限チェック
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $permissionLogic = $this->getPermissionLogic();
            $checkResult = $permissionLogic->checkUserOperation($auth->getUserId(), $tOkr->getOwnerUser()->getUserId());
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // OKR公開種別変更処理
        $okrSettingService = $this->getOkrSettingService();
        $okrSettingService->changeDisclosure($tOkr, $data['disclosureType']);

        return array('result' => 'OK');
    }
}
