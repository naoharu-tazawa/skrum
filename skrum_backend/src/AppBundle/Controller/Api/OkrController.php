<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Utils\DBConstant;
use AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO;

/**
 * OKRコントローラ
 *
 * @author naoharu.tazawa
 */
class OkrController extends BaseController
{
    /**
     * 目標新規登録
     *
     * @Rest\Post("/v1/okrs.{_format}")
     * @param Request $request リクエストオブジェクト
     * @return BasicOkrDTO
     */
    public function postOkrsAction(Request $request): BasicOkrDTO
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostOkrsPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // OKR種別に応じて必要なJsonSchemaのプロパティをチェック
        if ($data['okrType'] === DBConstant::OKR_TYPE_KEY_RESULT) {
            if (empty($data['parentOkrId'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        }

        // オーナー種別に応じて必要なJsonSchemaのプロパティをチェック
        if ($data['ownerType'] === DBConstant::OKR_OWNER_TYPE_USER) {
            if (empty($data['ownerUserId'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        } elseif ($data['ownerType'] === DBConstant::OKR_OWNER_TYPE_GROUP) {
            if (empty($data['ownerGroupId'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        } else {
            if (empty($data['ownerCompanyId'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        }

        // オーナー種別に応じてJsonSchemaのOKR公開種別をチェック
        if ($data['ownerType'] === DBConstant::OKR_OWNER_TYPE_COMPANY) {
            if ($data['disclosureType'] !== DBConstant::OKR_DISCLOSURE_TYPE_OVERALL && $data['disclosureType'] !== DBConstant::OKR_DISCLOSURE_TYPE_ADMIN) {
                throw new JsonSchemaException("オーナー種別が会社の場合、公開種別は'全体公開'または'管理者公開'しか選択できません");
            }
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // オーナーユーザ存在チェック
        $mUser = null;
        if ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_USER) {
            $mUser = $this->getDBExistanceLogic()->checkUserExistance($data['ownerUserId'], $auth->getCompanyId());
        }

        // オーナーグループ存在チェック
        $mGroup = null;
        if ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_GROUP) {
            $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($data['ownerGroupId'], $auth->getCompanyId());
        }

        // オーナー会社存在チェック
        if ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_COMPANY) {
            if ($data['ownerCompanyId'] != $auth->getCompanyId()) {
                throw new ApplicationException('オーナー会社IDが存在しません');
            }
        }

        $alignmentFlg = false;
        $tOkr = null;
        if (array_key_exists('parentOkrId', $data)) {
            /* 紐付け先ありの場合 */

            // 紐付け先OKR存在チェック
            $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($data['parentOkrId'], $auth->getCompanyId());

            // 紐付け先OKRのタイムフレームエンティティを取得
            $tTimeframe = $tOkr->getTimeframe();

            if ($data['okrType'] === DBConstant::OKR_TYPE_OBJECTIVE) {
                if (!empty($data['timeframeId'])) {
                    // 新規登録対象OKRと紐付け先OKRのタイムフレームの一致をチェック
                    if ($tOkr->getTimeframe()->getTimeframeId() != $data['timeframeId']) {
                        throw new ApplicationException('登録OKRと紐付け先OKRのタイムフレームIDが一致しません');
                    }
                }
            }
            $alignmentFlg = true;
        } else {
            /* 紐付け先なしの場合 */

            // タイムフレームエンティティ取得
            if (!empty($data['timeframeId'])) {
                $tTimeframe = $this->getDBExistanceLogic()->checkTimeframeExistance($data['timeframeId'], $auth->getCompanyId());
            } else {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        }

        // 目標新規登録処理
        $okrService = $this->getOkrService();
        $basicOkrDTO = $okrService->createOkr($auth, $data['ownerType'], $data, $tTimeframe, $mUser, $mGroup, $auth->getCompanyId(), $alignmentFlg, $tOkr);

        return $basicOkrDTO;
    }

    /**
     * OKR基本情報変更
     *
     * @Rest\Put("/v1/okrs/{okrId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function putOkrAction(Request $request, string $okrId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PutOkrPdu');
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
            $checkResult = $permissionLogic->checkUserOperationSelfOK($auth, $tOkr->getOwnerUser()->getUserId());
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // OKR更新処理
        $okrService = $this->getOkrService();
        $okrService->changeOkrInfo($data, $tOkr);

        return array('result' => 'OK');
    }

    /**
     * OKR進捗登録
     *
     * @Rest\Post("/v1/okrs/{okrId}/achievements.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return BasicOkrDTO
     */
    public function postOkrAchievementsAction(Request $request, string $okrId): BasicOkrDTO
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostOkrAchievementsPdu');
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
            $checkResult = $permissionLogic->checkUserOperationSelfOK($auth, $tOkr->getOwnerUser()->getUserId());
            if (!$checkResult) {
                throw new PermissionException('ユーザ操作権限がありません');
            }
        }

        // OKR進捗登録処理
        $okrService = $this->getOkrService();
        $basicOkrDTO = $okrService->registerAchievement($auth, $data, $tOkr);

        return $basicOkrDTO;
    }

    /**
     * OKR削除
     *
     * @Rest\Delete("/v1/okrs/{okrId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function deleteOkrAction(Request $request, string $okrId): array
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

        // OKR削除処理
        $okrService = $this->getOkrService();
        $okrService->deleteOkrs($auth, $tOkr);

        return array('result' => 'OK');
    }
}
