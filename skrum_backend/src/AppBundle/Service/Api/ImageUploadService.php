<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DBConstant;

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

                // グループマスタの会社レコードも更新
                $mGroupRepos = $this->getMGroupRepository();
                $mGroup = $mGroupRepos->findOneBy(array('company' => $companyId, 'groupType' => DBConstant::GROUP_TYPE_COMPANY));
                $mGroup->setImageVersion($mGroup->getImageVersion() + 1);
            }

            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 画像バージョンを1にする（Ver1.1リリース後削除）
     *
     * @param string $subjectType 操作対象主体種別
     * @param integer $userId ユーザID
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return void
     */
    public function imageVersion1(string $subjectType, int $userId = null, int $groupId = null, int $companyId = null)
    {
        // 画像保存済みフラグを更新
        try {
            if ($subjectType === Constant::SUBJECT_TYPE_USER) {
                $mUserRepos = $this->getMUserRepository();
                $mUser = $mUserRepos->find($userId);
                $mUser->setImageVersion(1);
            } elseif ($subjectType === Constant::SUBJECT_TYPE_GROUP) {
                $mGroupRepos = $this->getMGroupRepository();
                $mGroup = $mGroupRepos->find($groupId);
                $mGroup->setImageVersion(1);
            } elseif ($subjectType === Constant::SUBJECT_TYPE_COMPANY) {
                $mCompanyRepos = $this->getMCompanyRepository();
                $mCompany = $mCompanyRepos->find($companyId);
                $mCompany->setImageVersion(1);

                $mGroupRepos = $this->getMGroupRepository();
                $mGroup = $mGroupRepos->findOneBy(array('company' => $companyId, 'groupType' => DBConstant::GROUP_TYPE_COMPANY));
                $mGroup->setImageVersion(1);
            }

            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
