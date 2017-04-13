<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\ApplicationException;
use AppBundle\Controller\BaseController;

/**
 * タイムフレームコントローラ
 *
 * @author naoharu.tazawa
 */
class TimeframeController extends BaseController
{
    /**
     * タイムフレーム一覧取得
     *
     * @Rest\Get("/companies/{companyId}/timeframes.{_format}")
     * @param $request リクエストオブジェクト
     * @param $companyId 会社ID
     * @return array
     */
    public function getCompanyTimeframesAction(Request $request, $companyId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // タイムフレーム取得処理
        $timeframeService = $this->getTimeframeService();
        $timeframeDTOArray = $timeframeService->getTimeframes($companyId);

        return $timeframeDTOArray;
    }

    /**
     * デフォルトタイムフレーム変更
     *
     * @Rest\Put("/timeframes/{timeframeId}/setdefault.{_format}")
     * @param $request リクエストオブジェクト
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function setDefaultTimeframeAction(Request $request, $timeframeId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // タイムフレーム存在チェック
        $tTimeframe = $this->getDBExistanceLogic()->checkTimeframeExistance($timeframeId, $auth->getCompanyId());

        // デフォルトタイムフレーム変更処理
        $timeframeService = $this->getTimeframeService();
        $timeframeService->changeDefault($auth, $tTimeframe);

        return array('result' => 'OK');
    }

    /**
     * タイムフレーム追加
     *
     * @Rest\Post("/timeframes.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function postTimeframesAction(Request $request)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostTimeframesPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // タイムフレーム登録処理
        $timeframeService = $this->getTimeframeService();
        $timeframeService->registerTimeframe($auth, $data);

        return array('result' => 'OK');
    }

    /**
     * タイムフレーム編集
     *
     * @Rest\Put("/timeframes/{timeframeId}.{_format}")
     * @param $request リクエストオブジェクト
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function putTimeframeAction(Request $request, $timeframeId)
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostTimeframesPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // タイムフレーム存在チェック
        $tTimeframe = $this->getDBExistanceLogic()->checkTimeframeExistance($timeframeId, $auth->getCompanyId());

        // タイムフレーム更新処理
        $timeframeService = $this->getTimeframeService();
        $timeframeService->updateTimeframe($data, $tTimeframe);

        return array('result' => 'OK');
    }

    /**
     * タイムフレーム削除
     *
     * @Rest\Delete("/timeframes/{timeframeId}.{_format}")
     * @param $request リクエストオブジェクト
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function deleteTimeframeAction(Request $request, $timeframeId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // タイムフレーム存在チェック
        $tTimeframe = $this->getDBExistanceLogic()->checkTimeframeExistance($timeframeId, $auth->getCompanyId());

        // タイムフレーム削除処理
        $timeframeService = $this->getTimeframeService();
        $timeframeService->deleteTimeframe($tTimeframe);

        return array('result' => 'OK');
    }
}
