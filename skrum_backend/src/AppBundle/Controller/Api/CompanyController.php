<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Api\ResponseDTO\UserGroupDTO;

/**
 * 会社コントローラ
 *
 * @author naoharu.tazawa
 */
class CompanyController extends BaseController
{
    /**
     * 会社基本情報変更
     *
     * @Rest\Put("/v1/companies/{companyId}.{_format}")
     * @param $request リクエストオブジェクト
     * @param $companyId 会社ID
     * @return array
     */
    public function putCompanyAction(Request $request, $companyId)
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
