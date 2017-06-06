<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Utils\Permission;
use AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO;

/**
 * 会社コントローラ
 *
 * @author naoharu.tazawa
 */
class CompanyController extends BaseController
{
    /**
     * 会社基本情報取得
     *
     * @Rest\Get("/v1/companies/{companyId}.{_format}")
     * @Permission(value="company_profile_edit")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return BasicCompanyInfoDTO
     */
    public function getCompanyAction(Request $request, string $companyId): BasicCompanyInfoDTO
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // 会社基本情報取得
        $companyService = $this->getCompanyService();
        $basicCompanyInfoDTO = $companyService->getBasicCompanyInfo($companyId);

        return $basicCompanyInfoDTO;
    }

    /**
     * 会社基本情報変更
     *
     * @Rest\Put("/v1/companies/{companyId}.{_format}")
     * @Permission(value="company_profile_edit")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return array
     */
    public function putCompanyAction(Request $request, string $companyId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PutCompanyPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // 会社情報更新処理
        $companyService = $this->getCompanyService();
        $companyService->updateCompany($data, $companyId);

        return array('result' => 'OK');
    }
}
