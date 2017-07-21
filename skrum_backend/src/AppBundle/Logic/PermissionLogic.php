<?php

namespace AppBundle\Logic;

use AppBundle\Exception\NoDataException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DBConstant;

/**
 * 権限ロジッククラス
 *
 * @author naoharu.tazawa
 */
class PermissionLogic extends BaseLogic
{
    /**
     * ユーザ操作権限チェック（自ユーザ可）
     *
     * @param Auth $auth 認証情報
     * @param integer $targetUserId 操作対象ユーザ
     * @return boolean チェック結果
     */
    public function checkUserOperationSelfOK(Auth $auth, int $targetUserId): bool
    {
        // 同一ユーザならチェックOK
        if ($auth->getUserId() == $targetUserId) {
            return true;
        }

        // 権限チェック
        return $this->checkUserOperation($auth->getRoleLevel(), $targetUserId);

    }

    /**
     * ユーザ操作権限チェック（自ユーザ不可）
     *
     * @param Auth $auth 認証情報
     * @param integer $targetUserId 操作対象ユーザ
     * @return boolean チェック結果
     */
    public function checkUserOperationSelfNG(Auth $auth, int $targetUserId): bool
    {
        // 同一ユーザはチェックNG
        if ($auth->getUserId() == $targetUserId) {
            return false;
        }

        // 権限チェック
        return $this->checkUserOperation($auth->getRoleLevel(), $targetUserId);
    }

    /**
     * ユーザ操作権限チェック
     *
     * @param integer $subjectUserRoleLevel 主体ユーザのロールレベル
     * @param integer $targetUserId 操作対象ユーザ
     * @return boolean チェック結果
     */
    private function checkUserOperation(int $subjectUserRoleLevel, int $targetUserId): bool
    {
        // 操作対象ユーザのロールレベルを取得
        $targetUserRoleLevel = $this->getRoleLevel($targetUserId);

        // 権限チェックを行う
        switch ($subjectUserRoleLevel) {
            case DBConstant::ROLE_LEVEL_NORMAL:
                return false;
            case DBConstant::ROLE_LEVEL_ADMIN:
                if ($targetUserRoleLevel <= DBConstant::ROLE_LEVEL_ADMIN) {
                    return true;
                } else {
                    return false;
                }
            case DBConstant::ROLE_LEVEL_SUPERADMIN:
                return true;
        }
    }

    /**
     * グループ操作権限チェック
     *
     * @param Auth $auth 認証情報
     * @param integer $targetGroupId 操作対象グループ
     * @return boolean チェック結果
     */
    public function checkGroupOperation(Auth $auth, int $targetGroupId): bool
    {
        //　グループエンティティを取得
        $mGroupRepos = $this->getMGroupRepository();
        $mGroup = $mGroupRepos->find($targetGroupId);
        if ($mGroup === null) {
            throw new NoDataException('グループが存在しません');
        }

        // 権限チェックを行う
        if ($mGroup->getGroupType() === DBConstant::GROUP_TYPE_DEPARTMENT) {
            // 「グループ種別＝1:部門」の場合
            switch ($auth->getRoleLevel()) {
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
     * @return integer ロールレベル
     */
    private function getRoleLevel(int $userId): int
    {
        $mUserRepos = $this->getMUserRepository();
        $mUser = $mUserRepos->find($userId);
        if ($mUser === null) {
            throw new NoDataException('ユーザが存在しません');
        }

        return $mUser->getRoleAssignment()->getRoleLevel();
    }
}
