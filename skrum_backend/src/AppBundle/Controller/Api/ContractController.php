<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Utils\Permission;
use AppBundle\Api\ResponseDTO\ContractDTO;

/**
 * 契約コントローラ
 *
 * @author naoharu.tazawa
 */
class ContractController extends BaseController
{
    /**
     * 契約プラン情報取得
     *
     * @Rest\Get("/v1/companies/{companyId}/contracts.{_format}")
     * @Permission(value="plan_edit")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return ContractDTO
     */
    public function getCompanyContractsAction(Request $request, string $companyId): ContractDTO
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // 契約プラン情報取得処理
        $contractService = $this->getContractService();
        $contractDTO = $contractService->getContractInfo($companyId);

        return $contractDTO;
    }
}
