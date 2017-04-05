<?php

namespace AppBundle\Logic;

use AppBundle\Exception\ApplicationException;

/**
 * DB存在チェックロジッククラス
 *
 * @author naoharu.tazawa
 */
class DBExistanceLogic extends BaseLogic
{
    /**
     * ユーザ存在チェック
     *
     * @param integer $targetUserId チェック対象ユーザID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Entity\MUser ユーザエンティティ
     */
    public function checkUserExistance($targetUserId, $companyId)
    {
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->getUser($targetUserId, $companyId);
        if (count($mUserArray) === 0) {
            throw new ApplicationException('ユーザが存在しません');
        }

        return $mUserArray[0];
    }

    /**
     * グループ存在チェック
     *
     * @param integer $targetGroupId チェック対象グループID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Entity\MGroup グループエンティティ
     */
    public function checkGroupExistance($targetGroupId, $companyId)
    {
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->getGroup($targetGroupId, $companyId);
        if (count($mGroupArray) === 0) {
            throw new ApplicationException('グループが存在しません');
        }

        return $mGroupArray[0];
    }
}
