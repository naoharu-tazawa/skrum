<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
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
     * @Rest\Put("/v1/okrs/{okrId}/close.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function putOkrCloseAction(Request $request, string $okrId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // 操作権限チェック
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $permissionLogic = $this->getPermissionLogic();
            $checkResult = $permissionLogic->checkUserOperationSelfOK($auth, $tOkr->getOwnerUser()->getUserId());
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
     * @Rest\Put("/v1/okrs/{okrId}/open.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function putOkrOpenAction(Request $request, string $okrId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // 操作権限チェック
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $permissionLogic = $this->getPermissionLogic();
            $checkResult = $permissionLogic->checkUserOperationSelfOK($auth, $tOkr->getOwnerUser()->getUserId());
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
     * @Rest\Put("/v1/okrs/{okrId}/changedisclosure.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function changeOkrDisclosureAction(Request $request, string $okrId): array
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

        // ユーザ/グループ/会社操作権限一括チェック
        $permissionLogic = $this->getPermissionLogic();
        $permissionLogic->checkUserGroupCompanyOkrOperationSelfOK($auth, $tOkr);

        // OKR公開種別変更処理
        $okrSettingService = $this->getOkrSettingService();
        $okrSettingService->changeDisclosure($tOkr, $data['disclosureType']);

        return array('result' => 'OK');
    }

    /**
     * OKRオーナー変更
     *
     * @Rest\Put("/v1/okrs/{okrId}/changeowner.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function changeOkrOwnerAction(Request $request, string $okrId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/ChangeOkrOwnerPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // オーナー種別に応じて必要なJsonSchemaのプロパティをチェック
        if ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_USER) {
            if (empty($data['ownerUserId'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        } elseif ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_GROUP) {
            if (empty($data['ownerGroupId'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        } else {
            if (empty($data['ownerCompanyId'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // オーナー種別に応じて存在チェック
        $mUser = null;
        $mGroup = null;
        if ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_USER) {
            // ユーザ存在チェック
            $mUser = $this->getDBExistanceLogic()->checkUserExistance($data['ownerUserId'], $auth->getCompanyId());
        } elseif ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_GROUP) {
            // グループ存在チェック
            $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($data['ownerGroupId'], $auth->getCompanyId());
        } else {
            // 会社IDの一致をチェック
            if ($data['ownerCompanyId'] != $auth->getCompanyId()) {
                throw new ApplicationException('会社IDが存在しません');
            }
        }

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // ユーザ/グループ/会社操作権限一括チェック
        $permissionLogic = $this->getPermissionLogic();
        $permissionLogic->checkUserGroupCompanyOkrOperationSelfOK($auth, $tOkr);

        // OKRオーナー変更処理
        $okrSettingService = $this->getOkrSettingService();
        $okrSettingService->changeOwner($tOkr, $data['ownerType'], $mUser, $mGroup, $auth->getCompanyId());

        return array('result' => 'OK');
    }

    /**
     * KR加重平均割合設定
     *
     * @Rest\Put("/v1/okrs/{okrId}/setratio.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function setOkrRatioAction(Request $request, string $okrId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/SetOkrRatioPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // ユーザ操作権限チェック
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $permissionLogic = $this->getPermissionLogic();
            $checkResult = $permissionLogic->checkUserOperationSelfOK($auth, $tOkr->getOwnerUser()->getUserId());
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // KR加重平均割合更新処理
        $okrSettingService = $this->getOkrSettingService();
        $okrSettingService->updateRatio($auth, $data, $tOkr);

        return array('result' => 'OK');
    }
}
