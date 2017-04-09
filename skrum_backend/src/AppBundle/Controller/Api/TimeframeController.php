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
}
