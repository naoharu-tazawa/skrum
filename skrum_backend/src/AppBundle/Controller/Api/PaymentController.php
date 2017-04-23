<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Utils\Permission;

/**
 * 請求コントローラ
 *
 * @author naoharu.tazawa
 */
class PaymentController extends BaseController
{
    /**
     * 請求情報取得
     *
     * @Rest\Get("/v1/companies/{companyId}/payments.{_format}")
     * @Permission(value="payment_view")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return array
     */
    public function getCompanyPaymentsAction(Request $request, $companyId): array
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // 請求情報取得処理
        $paymentService = $this->getPaymentService();
        $paymentDTOArray = $paymentService->getPaymentInfo($companyId);

        return $paymentDTOArray;
    }
}
