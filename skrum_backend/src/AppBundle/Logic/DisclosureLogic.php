<?php

namespace AppBundle\Logic;

use AppBundle\Utils\DBConstant;

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
     * @param \AppBundle\Entity\TOkr $tOkr チェック対象OKRエンティティ
     * @return boolean チェック結果
     */
    public function checkOkr($subjectUserId, $subjectUserRoleLevel, $tOkr)
    {
        // 公開種別を取得
        $disclosureType = $tOkr->getDisclosureType();

        // 閲覧権限チェック
        if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
            // 全体公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_OVERALL) {
                return true;
            }

            // 本人のみ公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_SELF) {
                if ($subjectUserId == $tOkr->getOwnerUser()->getUserId()) {
                    return true;
                } else {
                    return false;
                }
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

            // グループ公開の場合
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_OVERALL) {
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
            return true;
        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_ROOT) {
            return false;
        }
    }

    /**
     * 投稿閲覧権限チェック
     *
     * @param integer $subjectUserId 操作主体ユーザ
     * @param integer $subjectUserRoleLevel 操作主体ユーザのロールレベル
     * @param \AppBundle\Entity\TPost $tPost チェック対象投稿エンティティ
     * @return boolean チェック結果
     */
    public function checkPost($subjectUserId, $subjectUserRoleLevel, $tPost)
    {
        // 公開種別を取得
        $disclosureType = $tPost->getDisclosureType();

        // 閲覧権限チェック
        // 全体公開の場合
        if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_OVERALL) {
            return true;
        }

        // 本人のみ公開の場合
        if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_SELF) {
            if ($subjectUserId === $tPost->getPosterId()) {
                return true;
            } else {
                return false;
            }
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
