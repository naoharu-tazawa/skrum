<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Api\ResponseDTO\OkrDetailsDTO;

/**
 * OKR詳細コントローラ
 *
 * @author naoharu.tazawa
 */
class OkrDetailsController extends BaseController
{
    /**
     * OKR詳細取得
     *
     * @Rest\Get("/v1/okrs/{okrId}/details.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return OkrDetailsDTO
     */
    public function getOkrDetailsAction(Request $request, string $okrId): OkrDetailsDTO
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // OKR詳細取得処理
        $okrDetailsService = $this->getOkrDetailsService();
        $okrDetailsDTO = $okrDetailsService->getOkrDetails($auth, $tOkr);

        return $okrDetailsDTO;
    }
}
