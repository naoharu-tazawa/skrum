<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\DBConstant;
use AppBundle\Utils\Constant;
use AppBundle\Entity\MCompany;
use AppBundle\Entity\MGroup;
use AppBundle\Api\ResponseDTO\ImageVersionDTO;

/**
 * 画像アップロードサービスクラス
 *
 * @author naoharu.tazawa
 */
class ImageUploadService extends BaseService
{
    /**
     * 画像アップロード
     *
     * @param array $data リクエストJSON連想配列
     * @param string $subjectType 操作対象主体種別
     * @param integer $userId ユーザID
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return void
     */
    public function uploadImage(array $data, string $subjectType, int $userId = null, int $groupId = null, int $companyId = null)
    {
        // Amazon S3クライアントを取得
        $client = $this->getContainer()->get('aws.s3');

        // 実行環境によってバケットを選択
        $bucket = null;
        if ($this->getContainer()->get('kernel')->getEnvironment() === 'prod') {
            $bucket = Constant::S3_BUCKET_PROD;
        } elseif ($this->getContainer()->get('kernel')->getEnvironment() === 'test') {
            $bucket = Constant::S3_BUCKET_TEST;
        } elseif ($this->getContainer()->get('kernel')->getEnvironment() === 'dev') {
            $bucket = Constant::S3_BUCKET_DEV;
        }

        // アップロード先のS3内のファイルパスを指定
        if ($subjectType === Constant::SUBJECT_TYPE_USER) {
            // 画像バージョンを取得
            $mUserRepos = $this->getMUserRepository();
            $mUser = $mUserRepos->find($userId);
            $imageVersion = $mUser->getImageVersion();

            // ファイルパスを生成
            $oldFilePathInS3 = 'c/' . $companyId . '/u/' . $userId . '/image' . $imageVersion;
            $filePathInS3 = 'c/' . $companyId . '/u/' . $userId . '/image' . ($imageVersion + 1);
        } elseif ($subjectType === Constant::SUBJECT_TYPE_GROUP) {
            // 画像バージョンを取得
            $mGroupRepos = $this->getMGroupRepository();
            $mGroup = $mGroupRepos->find($groupId);
            $imageVersion = $mGroup->getImageVersion();

            // ファイルパスを生成
            $oldFilePathInS3 = 'c/' . $companyId . '/g/' . $groupId . '/image' . $imageVersion;
            $filePathInS3 = 'c/' . $companyId . '/g/' . $groupId . '/image' . ($imageVersion + 1);
        } elseif ($subjectType === Constant::SUBJECT_TYPE_COMPANY) {
            // 画像バージョンを取得
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($companyId);
            $imageVersion = $mCompany->getImageVersion();

            // ファイルパスを生成
            $oldFilePathInS3 = 'c/' . $companyId . '/image' . $imageVersion;
            $filePathInS3 = 'c/' . $companyId . '/image' . ($imageVersion + 1);
        }

        try {
            // Delete an old object in Amazon S3
            if ($imageVersion !== 0) {
                $result = $client->deleteObject(array(
                        'Bucket'     => $bucket,
                        'Key'        => $oldFilePathInS3
                ));
            }

            // Upload an object to Amazon S3
            $result = $client->putObject(array(
                    'Bucket'     => $bucket,
                    'Key'        => $filePathInS3,
                    'Metadata'   => array(
                            'mime-type' => $data['mimeType']
                    ),
                    'Body'       => $data['image']
            ));

            // 画像バージョンを更新
            if ($subjectType === Constant::SUBJECT_TYPE_USER) {
                $mUser->setImageVersion($imageVersion + 1);
            } elseif ($subjectType === Constant::SUBJECT_TYPE_GROUP) {
                $mGroup->setImageVersion($imageVersion + 1);
            } elseif ($subjectType === Constant::SUBJECT_TYPE_COMPANY) {
                $mCompany->setImageVersion($imageVersion + 1);

                // グループマスタの会社レコードも更新
                $mGroupRepos = $this->getMGroupRepository();
                $mGroup = $mGroupRepos->findOneBy(array('company' => $companyId, 'groupType' => DBConstant::GROUP_TYPE_COMPANY));
                $mGroup->setImageVersion($imageVersion + 1);
            }
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }

        // レスポンスDTOを生成
        $imageVersionDTO = new ImageVersionDTO();
        $imageVersionDTO->setImageVersion($imageVersion + 1);

        return $imageVersionDTO;
    }
}
