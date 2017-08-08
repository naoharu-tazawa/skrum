<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Constant;

/**
 * 画像アップロードサービスクラス
 *
 * @author naoharu.tazawa
 */
class ImageUploadService extends BaseService
{
    /**
     * 画像バージョンの更新
     *
     * @param string $subjectType 操作対象主体種別
     * @param integer $userId ユーザID
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return void
     */
    public function incrementImageVersion(string $subjectType, int $userId = null, int $groupId = null, int $companyId = null)
    {
        // 画像保存済みフラグを更新
        try {
            if ($subjectType === Constant::SUBJECT_TYPE_USER) {
                $mUserRepos = $this->getMUserRepository();
                $mUser = $mUserRepos->find($userId);
                $mUser->setImageVersion($mUser->getImageVersion() + 1);
            } elseif ($subjectType === Constant::SUBJECT_TYPE_GROUP) {
                $mGroupRepos = $this->getMGroupRepository();
                $mGroup = $mGroupRepos->find($groupId);
                $mGroup->setImageVersion($mGroup->getImageVersion() + 1);
            } elseif ($subjectType === Constant::SUBJECT_TYPE_COMPANY) {
                $mCompanyRepos = $this->getMCompanyRepository();
                $mCompany = $mCompanyRepos->find($companyId);
                $mCompany->setImageVersion($mCompany->getImageVersion() + 1);
            }

            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
