<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;
use AppBundle\Utils\DBConstant;

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
     * @Rest\Post("/objectives.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function postObjectivesAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostObjectivesPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // オーナーユーザ存在チェック
        if ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_USER) {
            $mUser = $this->getDBExistanceLogic()->checkUserExistance($data['ownerUserId'], $auth->getCompanyId());
        }

        // オーナーグループ存在チェック
        if ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_GROUP) {
            $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($data['ownerGroupId'], $auth->getCompanyId());
        }

        // オーナー会社存在チェック
        if ($data['ownerType'] == DBConstant::OKR_OWNER_TYPE_COMPANY) {
            if ($data['ownerCompanyId'] != $auth->getCompanyId()) {
                throw new ApplicationException('オーナー会社IDが存在しません');
            }
        }

        // タイムフレーム存在チェック
        $tTimeframe = $this->getDBExistanceLogic()->checkTimeframeExistance($data['timeframeId'], $auth->getCompanyId());

        // 紐付け先OKR存在チェック
        $alignmentFlg = false;
        if (array_key_exists('parentOkrId', $data)) {
            $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($data['parentOkrId'], $auth->getCompanyId());
            $alignmentFlg = true;
        }

        // 目標新規登録処理
        $okrService = $this->getOkrService();
        $okrService->createOkr($data['ownerType'], $data, $tTimeframe, $mUser, $mGroup, $auth->getCompanyId(), $alignmentFlg, $tOkr);

        return array('result' => 'OK');
    }
}
