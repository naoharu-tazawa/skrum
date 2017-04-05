<?php

namespace AppBundle\Logic;

use AppBundle\Exception\ApplicationException;
use AppBundle\Utils\DBConstant;

/**
 * 権限ロジッククラス
 *
 * @author naoharu.tazawa
 */
class PermissionLogic extends BaseLogic
{
    /**
     * グループ操作権限チェック
     *
     * @param integer $subjectUserId
     * @param integer $targetGroupId
     * @return boolean チェック結果
     */
    public function checkGroupOperation($subjectUserId, $targetGroupId)
    {
        // 主体ユーザのロールレベルを取得
        $roleLevel = $this->getRoleLevel($subjectUserId);

        //　グループエンティティを取得
        $mGroupRepos = $this->getMGroupRepository();
        $mGroup = $mGroupRepos->find($targetGroupId);
        if ($mGroup === null) {
            throw new ApplicationException('グループが存在しません');
        }

        // 権限チェックを行う
        if ($mGroup->getGroupType() === DBConstant::GROUP_TYPE_DEPARTMENT) {
            // 「グループ種別＝1:部門」の場合
            switch ($roleLevel) {
                case DBConstant::ROLE_LEVEL_NORMAL:
                    return false;
                case DBConstant::ROLE_LEVEL_ADMIN:
                case DBConstant::ROLE_LEVEL_SUPERADMIN:
                    return true;
            }
        } elseif ($mGroup->getGroupType() === DBConstant::GROUP_TYPE_COMPANY) {
            // 「グループ種別＝3:会社」の場合
            return false;
        } else {
            // それ以外の場合
            return true;
        }
    }

    /**
     * ユーザのロールレベルを取得
     *
     * @param integer $userId
     * @return string ロールレベル
     */
    private function getRoleLevel($userId)
    {
        $mUserRepos = $this->getMUserRepository();
        $mUser = $mUserRepos->find($userId);
        if ($mUser === null) {
            throw new ApplicationException('ユーザが存在しません');
        }

        return $mUser->getRoleAssignment()->getRoleLevel();
    }
}
