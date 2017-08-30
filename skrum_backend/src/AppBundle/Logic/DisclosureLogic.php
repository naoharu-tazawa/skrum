<?php

namespace AppBundle\Logic;

use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TPost;

/**
 * 公開ロジッククラス
 *
 * @author naoharu.tazawa
 */
class DisclosureLogic extends BaseLogic
{
    /**
     * OKR閲覧権限チェック
     *
     * @param integer $subjectUserId 操作主体ユーザ
     * @param integer $subjectUserRoleLevel 操作主体ユーザのロールレベル
     * @param TOkr $tOkr チェック対象OKRエンティティ
     * @return boolean チェック結果
     */
    public function checkOkr(int $subjectUserId, int $subjectUserRoleLevel, TOkr $tOkr): bool
    {
        // 公開種別を取得
        $disclosureType = $tOkr->getDisclosureType();

        // 閲覧権限チェック
        if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
            // 自分のOKRは閲覧可能
            if ($subjectUserId == $tOkr->getOwnerUser()->getUserId()) {
                return true;
            }

            // 全体公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_OVERALL) {
                return true;
            }

            // 操作主体ユーザがスーパー管理者ユーザの場合
            if ($subjectUserRoleLevel >= DBConstant::ROLE_LEVEL_SUPERADMIN) {
                return true;
            }

            // 管理者公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_ADMIN) {
                if ($subjectUserRoleLevel >= DBConstant::ROLE_LEVEL_ADMIN) {
                    return true;
                } else {
                    return false;
                }
            }

            // グループ公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_GROUP) {
                // 操作主体ユーザとOKRオーナーが所属している同一グループが存在するかチェック
                $tGroupMemberRepos = $this->getTGroupMemberRepository();
                $sameGroups = $tGroupMemberRepos->getTheSameGroups($subjectUserId, $tOkr->getOwnerUser()->getUserId());
                if (count($sameGroups) > 0) {
                    return true;
                } else {
                    return false;
                }
            }

            // グループ管理者公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_GROUP_ADMIN) {
                if ($subjectUserRoleLevel < DBConstant::ROLE_LEVEL_ADMIN) {
                    return false;
                }

                // 操作主体ユーザとOKRオーナーが所属している同一グループが存在するかチェック
                $tGroupMemberRepos = $this->getTGroupMemberRepository();
                $sameGroups = $tGroupMemberRepos->getTheSameGroups($subjectUserId, $tOkr->getOwnerUser()->getUserId());
                if (count($sameGroups) > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
            // 全体公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_OVERALL) {
                return true;
            }

            // 操作主体ユーザがスーパー管理者ユーザの場合
            if ($subjectUserRoleLevel >= DBConstant::ROLE_LEVEL_SUPERADMIN) {
                return true;
            }

            // 管理者公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_ADMIN) {
                if ($subjectUserRoleLevel >= DBConstant::ROLE_LEVEL_ADMIN) {
                    return true;
                } else {
                    return false;
                }
            }

            // グループ公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_GROUP) {
                // オーナーグループに操作主体ユーザが所属しているかチェック
                $tGroupMemberRepos = $this->getTGroupMemberRepository();
                $groupMemberArray = $tGroupMemberRepos->getGroupMember($tOkr->getOwnerGroup()->getGroupId(), $subjectUserId);
                if (count($groupMemberArray) === 1) {
                    return true;
                } else {
                    return false;
                }
            }

            // グループ管理者公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_GROUP_ADMIN) {
                if ($subjectUserRoleLevel < DBConstant::ROLE_LEVEL_ADMIN) {
                    return false;
                }

                // オーナーグループに操作主体ユーザが所属しているかチェック
                $tGroupMemberRepos = $this->getTGroupMemberRepository();
                $groupMemberArray = $tGroupMemberRepos->getGroupMember($tOkr->getOwnerGroup()->getGroupId(), $subjectUserId);
                if (count($groupMemberArray) === 1) {
                    return true;
                } else {
                    return false;
                }
            }
        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY) {
            // 全体公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_OVERALL) {
                return true;
            }

            // 管理者公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_ADMIN) {
                if ($subjectUserRoleLevel >= DBConstant::ROLE_LEVEL_ADMIN) {
                    return true;
                } else {
                    return false;
                }
            }
        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_ROOT) {
            return false;
        }
    }

    /**
     * 投稿閲覧権限チェック
     *
     * @param integer $subjectUserId 操作主体ユーザ
     * @param integer $subjectUserRoleLevel 操作主体ユーザのロールレベル
     * @param TPost $tPost チェック対象投稿エンティティ
     * @return boolean チェック結果
     */
    public function checkPost(int $subjectUserId, int $subjectUserRoleLevel, TPost $tPost): bool
    {
        // 閲覧権限チェック
        // 自分のOKRは閲覧可能
        if ($tPost->getPosterType() === DBConstant::POSTER_TYPE_USER) {
            if ($subjectUserId === $tPost->getPosterUserId()) {
                return true;
            }
        }

        // 公開種別を取得
        $disclosureType = $tPost->getDisclosureType();

        // 全体公開の場合
        if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_OVERALL) {
            return true;
        }

        // 操作主体ユーザがスーパー管理者ユーザの場合
        if ($subjectUserRoleLevel >= DBConstant::ROLE_LEVEL_SUPERADMIN) {
            return true;
        }

        // 管理者公開の場合
        if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_ADMIN) {
            if ($subjectUserRoleLevel >= DBConstant::ROLE_LEVEL_ADMIN) {
                return true;
            } else {
                return false;
            }
        }

        // グループ公開の場合
        if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_GROUP) {
            // 操作主体ユーザがタイムラインオーナーグループに所属しているかチェック
            $tGroupMemberRepos = $this->getTGroupMemberRepository();
            $tGroupMemberArray = $tGroupMemberRepos->getGroupMember($tPost->getTimelineOwnerGroupId(), $subjectUserId);
            if (count($tGroupMemberArray) === 1) {
                return true;
            } else {
                return false;
            }
        }

        // グループ管理者公開の場合
        if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_GROUP_ADMIN) {
            if ($subjectUserRoleLevel < DBConstant::ROLE_LEVEL_ADMIN) {
                return false;
            }

            // 操作主体ユーザがタイムラインオーナーグループに所属しているかチェック
            $tGroupMemberRepos = $this->getTGroupMemberRepository();
            $tGroupMemberArray = $tGroupMemberRepos->getGroupMember($tPost->getTimelineOwnerGroupId(), $subjectUserId);
            if (count($tGroupMemberArray) === 1) {
                return true;
            } else {
                return false;
            }
        }
    }
}
