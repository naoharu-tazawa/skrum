<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Utils\Permission;

/**
 * CSVアップロードコントローラ
 *
 * @author naoharu.tazawa
 */
class CsvUploadController extends BaseController
{
    /**
     * ユーザ追加ファイルアップロード
     *
     * @Rest\Post("/v1/csv/additionalusers.{_format}")
     * Permission(value="csv_upload")
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    public function postCsvAdditionalusersAction(Request $request): array
    {
        // JSONからCSVファイルを取得
        $data = $this->getCSVFromJson($request);

        // MIMEタイプチェック
        if ($data['mimeType'] !== 'text/csv') {
            return array('error' => 'CSVファイルではありません');
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // DB登録
        $csvUploadService = $this->getCsvUploadService();
        $result = $csvUploadService->registerCsv($auth, $data['content']);

        return $result;
    }

    /**
     * JSONからCSVを取得
     *
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    private function getCSVFromJson(Request $request): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostCsvAdditionalusersPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // BASE64をデコード
        $data['content'] = base64_decode($data['content']);
        // 文字コードをSJISからUTF-8に変換
        $data['content'] = mb_convert_encoding($data['content'], "UTF-8", "SJIS");

        return $data;
    }
}
