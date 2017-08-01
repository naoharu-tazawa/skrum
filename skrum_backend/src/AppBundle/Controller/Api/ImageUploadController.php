<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Utils\DBConstant;

/**
 * 画像アップロードコントローラ
 *
 * @author naoharu.tazawa
 */
class ImageUploadController extends BaseController
{
    /**
     * ユーザ画像アップロード
     *
     * @Rest\Post("/v1/users/{userId}/images.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return array
     */
    public function postUserImagesAction(Request $request, int $userId): array
    {
        // JSONから画像ファイルを取得
        $imageFile = $this->getImageFromJson($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkUserOperationSelfOK($auth, $userId);
        if (!$checkResult) {
            throw new PermissionException('ユーザ操作権限がありません');
        }

        // Amazon S3クライアントを取得
        $client = $this->get('aws.s3');

        // アップロード先のS3内のファイルパスを指定
        $filePathInS3 = 'c/' . $auth->getCompanyId() . '/u/' . $userId . '/picture';

        // Upload an object to Amazon S3
        $result = $client->putObject(array(
                'Bucket'     => 'skrum',
                'Key'        => $filePathInS3,
                'Metadata'   => array(
                        'mime-type' => 'image/jpeg'
                ),
                'Body'       => $imageFile
        ));

        return array('result' => 'OK');
    }

    /**
     * グループ画像アップロード
     *
     * @Rest\Post("/v1/groups/{groupId}/images.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return array
     */
    public function postGroupImagesAction(Request $request, int $groupId): array
    {
        // JSONから画像ファイルを取得
        $imageFile = $this->getImageFromJson($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // 操作権限チェック
        $permissionLogic = $this->getPermissionLogic();
        $checkResult = $permissionLogic->checkGroupOperation($auth, $groupId);
        if (!$checkResult) {
            throw new PermissionException('グループ操作権限がありません');
        }

        // Amazon S3クライアントを取得
        $client = $this->get('aws.s3');

        // アップロード先のS3内のファイルパスを指定
        $filePathInS3 = 'c/' . $auth->getCompanyId() . '/g/' . $groupId . '/picture';

        // Upload an object to Amazon S3
        $result = $client->putObject(array(
                'Bucket'     => 'skrum',
                'Key'        => $filePathInS3,
                'Metadata'   => array(
                        'mime-type' => 'image/jpeg'
                ),
                'Body'       => $imageFile
        ));

        return array('result' => 'OK');
    }

    /**
     * 会社画像アップロード
     *
     * @Rest\Post("/v1/companies/{companyId}/images.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return array
     */
    public function postCompanyImagesAction(Request $request, int $companyId): array
    {
        // JSONから画像ファイルを取得
        $imageFile = $this->getImageFromJson($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // 操作権限チェック
        if ($auth->getRoleLevel() < DBConstant::ROLE_LEVEL_SUPERADMIN) {
            throw new PermissionException('スーパー管理者ユーザのみ操作可能です');
        }

        // Amazon S3クライアントを取得
        $client = $this->get('aws.s3');

        // アップロード先のS3内のファイルパスを指定
        $filePathInS3 = 'c/' . $companyId . '/picture';

        // Upload an object to Amazon S3
        $result = $client->putObject(array(
                'Bucket'     => 'skrum',
                'Key'        => $filePathInS3,
                'Metadata'   => array(
                        'mime-type' => 'image/jpeg'
                ),
                'Body'       => $imageFile
        ));

        return array('result' => 'OK');
    }

    /**
     * JSONから画像を取得
     *
     * @param Request $request リクエストオブジェクト
     * @return array
     */
    private function getImageFromJson(Request $request): string
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostImagePdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // BASE64をデコード
        return base64_decode($data['image']);
    }
}
