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
     * 画像保存済みフラグ更新
     *
     * @param string $subjectType 操作対象主体種別
     * @param integer $userId ユーザID
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return void
     */
    public function updateHasImage(string $subjectType, int $userId = null, int $groupId = null, int $companyId = null)
    {
        // 画像保存済みフラグを更新
        try {
            if ($subjectType === Constant::SUBJECT_TYPE_USER) {
                $mUserRepos = $this->getMUserRepository();
                $mUser = $mUserRepos->find($userId);
                $mUser->setHasImage(DBConstant::FLG_TRUE);
            } elseif ($subjectType === Constant::SUBJECT_TYPE_GROUP) {
                $mGroupRepos = $this->getMGroupRepository();
                $mGroup = $mGroupRepos->find($groupId);
                $mGroup->setHasImage(DBConstant::FLG_TRUE);
            } elseif ($subjectType === Constant::SUBJECT_TYPE_COMPANY) {
                $mCompanyRepos = $this->getMCompanyRepository();
                $mCompany = $mCompanyRepos->find($companyId);
                $mCompany->setHasImage(DBConstant::FLG_TRUE);
            }

            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
