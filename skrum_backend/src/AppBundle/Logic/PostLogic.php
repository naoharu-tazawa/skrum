<?php

namespace AppBundle\Logic;

use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TOkrActivity;
use AppBundle\Entity\TPost;
use AppBundle\Entity\TPostTo;

/**
 * 投稿ロジッククラス
 *
 * @author naoharu.tazawa
 */
class PostLogic extends BaseLogic
{
    /**
     * 手動投稿
     *
     * @param Auth $auth 認証情報
     * @param string $manualPost 手動投稿
     * @param string $autoPost 自動投稿
     * @param TOkr $tOkr 操作対象OKRエンティティ
     * @param TOkrActivity $tOkrActivity 紐付け対象OKRアクティビティエンティティ
     * @return void
     * @throws \Exception
     */
    public function manualPost(Auth $auth, string $manualPost = null, string $autoPost = null, TOkr $tOkr, TOkrActivity $tOkrActivity)
    {
        $groupIdArray = array();
        $tOkrRepos = $this->getTOkrRepository();

        // 自動投稿内容の主語
        $subject = null;

        if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY) {
            // 操作対象OKRのオーナーが会社の場合、会社IDに対応するグループIDを投稿先グループに入れる
            $mGroupRepos = $this->getMGroupRepository();
            $mGroup = $mGroupRepos->findBy(array('company' => $auth->getCompanyId(), 'groupType' => DBConstant::GROUP_TYPE_COMPANY));
            $groupIdArray[] = $mGroup[0]->getGroupId();

            // 自動投稿内容の主語を取得
            $subject = $mGroup[0]->getGroupName();
        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
            // 操作対象OKRのオーナーがユーザの場合、ユーザ所属グループ（共有設定しているグループのみ）を投稿先グループに入れる
            $tGroupMemberRepos = $this->getTGroupMemberRepository();
            $tGroupMemberArray = $tGroupMemberRepos->findBy(array('user' => $tOkr->getOwnerUser()->getUserId(), 'postShareFlg' => DBConstant::FLG_TRUE));
            foreach ($tGroupMemberArray as $tGroupMember) {
                $groupIdArray[] = $tGroupMember->getGroup()->getGroupId();
            }

            // 自動投稿内容の主語を取得
            $subject = $tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName();
        } else {
            // 操作対象OKRのオーナーがグループの場合、投稿先グループに入れる
            if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $groupIdArray[] = $tOkr->getOwnerGroup()->getGroupId();

                // 自動投稿内容の主語を取得
                $subject = $tOkr->getOwnerGroup()->getGroupName();
            }

            // 親OKRまたは祖父母OKRのオーナーがグループの場合、そのグループを投稿先グループに入れる
            if ($tOkr->getParentOkr() !== null) {
                $parentAndGrandParentOkr = $tOkrRepos->getParentOkr($tOkr->getParentOkr()->getOkrId(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
                if ($tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
                    if (!empty($parentAndGrandParentOkr[0]['childOkr'])) {
                        if ($parentAndGrandParentOkr[0]['childOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                            $groupIdArray[] = $parentAndGrandParentOkr[0]['childOkr']->getOwnerGroup()->getGroupId();
                        }
                    }
                } else {
                    if (!empty($parentAndGrandParentOkr[1]['parentOkr'])) {
                        if ($parentAndGrandParentOkr[1]['parentOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                            $groupIdArray[] = $parentAndGrandParentOkr[1]['parentOkr']->getOwnerGroup()->getGroupId();
                        }
                    }
                }
            }

            // 二重投稿を防ぐ
            if (count($groupIdArray) === 2) {
                if ($groupIdArray[0] == $groupIdArray[1]) {
                    unset($groupIdArray[1]);
                }
            }
        }

        // 投稿登録
        $tPost = new TPost();
        $tPost->setPosterType(DBConstant::POSTER_TYPE_USER);
        $tPost->setPosterUserId($auth->getUserId());
        if ($manualPost !== null) $tPost->setPost($manualPost);
        if ($autoPost !== null) $tPost->setAutoPost($subject . $autoPost);
        $tPost->setPostedDatetime(DateUtility::getCurrentDatetime());
        $tPost->setOkrActivity($tOkrActivity);
        $tPost->setDisclosureType($tOkr->getDisclosureType());
        $this->persist($tPost);

        // 投稿先登録
        foreach ($groupIdArray as $groupId) {
            $tPostTo = new TPostTo();
            $tPostTo->setPost($tPost);
            $tPostTo->setTimelineOwnerGroupId($groupId);
            $this->persist($tPostTo);
        }

        $this->flush();
    }

    /**
     * 自動投稿
     *
     * @param Auth $auth 認証情報
     * @param string $autoPost 自動投稿
     * @param TOkr $tOkr 操作対象OKRエンティティ
     * @param TOkrActivity $tOkrActivity 紐付け対象OKRアクティビティエンティティ
     * @return void
     * @throws \Exception
     */
    public function autoPost(Auth $auth, string $autoPost, TOkr $tOkr, TOkrActivity $tOkrActivity)
    {
        if (!empty($autoPost)) {
            $groupIdArray = array();
            $tOkrRepos = $this->getTOkrRepository();

            // 自動投稿内容の主語
            $subject = null;

            if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY) {
                // 操作対象OKRのオーナーが会社の場合、会社IDに対応するグループIDを投稿先グループに入れる
                $mGroupRepos = $this->getMGroupRepository();
                $mGroup = $mGroupRepos->findBy(array('company' => $auth->getCompanyId(), 'groupType' => DBConstant::GROUP_TYPE_COMPANY));
                $groupIdArray[] = $mGroup[0]->getGroupId();

                // 自動投稿内容の主語を取得
                $subject = $mGroup[0]->getGroupName();
            } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                // 操作対象OKRのオーナーがユーザの場合、ユーザ所属グループ（共有設定しているグループのみ）を投稿先グループに入れる
                $tGroupMemberRepos = $this->getTGroupMemberRepository();
                $tGroupMemberArray = $tGroupMemberRepos->findBy(array('user' => $tOkr->getOwnerUser()->getUserId(), 'postShareFlg' => DBConstant::FLG_TRUE));
                foreach ($tGroupMemberArray as $tGroupMember) {
                    $groupIdArray[] = $tGroupMember->getGroup()->getGroupId();
                }

                // 自動投稿内容の主語を取得
                $subject = $tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName();
            } else {
                // 操作対象OKRのオーナーがグループの場合、投稿先グループに入れる
                if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                    $groupIdArray[] = $tOkr->getOwnerGroup()->getGroupId();

                    // 自動投稿内容の主語を取得
                    $subject = $tOkr->getOwnerGroup()->getGroupName();
                }

                // 親OKRまたは祖父母OKRのオーナーがグループの場合、そのグループを投稿先グループに入れる
                if ($tOkr->getParentOkr() !== null) {
                    $parentAndGrandParentOkr = $tOkrRepos->getParentOkr($tOkr->getParentOkr()->getOkrId(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
                    if ($tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
                        if (!empty($parentAndGrandParentOkr[0]['childOkr'])) {
                            if ($parentAndGrandParentOkr[0]['childOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                                $groupIdArray[] = $parentAndGrandParentOkr[0]['childOkr']->getOwnerGroup()->getGroupId();
                            }
                        }
                    } else {
                        if (!empty($parentAndGrandParentOkr[1]['parentOkr'])) {
                            if ($parentAndGrandParentOkr[1]['parentOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                                $groupIdArray[] = $parentAndGrandParentOkr[1]['parentOkr']->getOwnerGroup()->getGroupId();
                            }
                        }
                    }
                }

                // 二重投稿を防ぐ
                if (count($groupIdArray) === 2) {
                    if ($groupIdArray[0] == $groupIdArray[1]) {
                        unset($groupIdArray[1]);
                    }
                }
            }

            // 投稿登録
            $tPost = new TPost();
            if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER || $tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $tPost->setPosterType(DBConstant::POSTER_TYPE_GROUP);
                $tPost->setPosterGroupId($groupIdArray[0]);
            } else {
                $tPost->setPosterType(DBConstant::POSTER_TYPE_COMPANY);
                $tPost->setPosterCompanyId($auth->getCompanyId());
            }
            $tPost->setAutoPost($subject . $autoPost);
            $tPost->setPostedDatetime(DateUtility::getCurrentDatetime());
            $tPost->setOkrActivity($tOkrActivity);
            $tPost->setDisclosureType($tOkr->getDisclosureType());
            $this->persist($tPost);

            // 投稿先登録
            foreach ($groupIdArray as $groupId) {
                $tPostTo = new TPostTo();
                $tPostTo->setPost($tPost);
                $tPostTo->setTimelineOwnerGroupId($groupId);
                $this->persist($tPostTo);
            }

            $this->flush();
        }
    }

    /**
     * 自動投稿（投稿者が常にユーザの場合）
     *
     * @param Auth $auth 認証情報
     * @param string $autoPost 自動投稿
     * @param TOkr $tOkr 操作対象OKRエンティティ
     * @param TOkrActivity $tOkrActivity 紐付け対象OKRアクティビティエンティティ
     * @return void
     * @throws \Exception
     */
    public function autoPostPosterIsUser(Auth $auth, string $autoPost, TOkr $tOkr, TOkrActivity $tOkrActivity)
    {
        $this->manualPost($auth, null, $autoPost, $tOkr, $tOkrActivity);
    }

    /**
     * 自動投稿（◯%達成時）
     *
     * @param Auth $auth 認証情報
     * @param integer $achievementRate 達成率
     * @param integer $previousAchievementRate 前回達成率
     * @param TOkr $tOkr 操作対象OKRエンティティ
     * @param TOkrActivity $tOkrActivity 紐付け対象OKRアクティビティエンティティ
     * @return void
     * @throws \Exception
     */
    public function autoPostAboutAchievement(Auth $auth, int $achievementRate, int $previousAchievementRate, TOkr $tOkr, TOkrActivity $tOkrActivity)
    {
        // 自動投稿内容の主語
        $subject = null;

        // 自動投稿文面作成
        if ($tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
            $format = $this->getParameter('auto_post_type_achievement_rate_o');
        } elseif ($tOkr->getType() === DBConstant::OKR_TYPE_KEY_RESULT) {
            $format = $this->getParameter('auto_post_type_achievement_rate_kr');
        }
        $autoPost = null;
        if ($achievementRate >= 100 && $previousAchievementRate < 100) {
            $autoPost = sprintf($format, 100);
        } elseif ($achievementRate >= 70 && $previousAchievementRate < 70) {
            $autoPost = sprintf($format, 70);
        } elseif ($achievementRate >= 50 && $previousAchievementRate < 50) {
            $autoPost = sprintf($format, 50);
        } elseif ($achievementRate >= 30 && $previousAchievementRate < 30) {
            $autoPost = sprintf($format, 30);
        }

        if ($autoPost !== null) {
            $groupIdArray = array();
            $tOkrRepos = $this->getTOkrRepository();

            if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY) {
                // 操作対象OKRのオーナーが会社の場合、会社IDに対応するグループIDを投稿先グループに入れる
                $mGroupRepos = $this->getMGroupRepository();
                $mGroup = $mGroupRepos->findBy(array('company' => $auth->getCompanyId(), 'groupType' => DBConstant::GROUP_TYPE_COMPANY));
                $groupIdArray[] = $mGroup[0]->getGroupId();

                // 自動投稿内容の主語を取得
                $subject = $mGroup[0]->getGroupName();
            } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                // 操作対象OKRのオーナーがユーザの場合
                // 親OKRまたは祖父母OKRのオーナーがグループの場合、そのグループを投稿先グループに入れる（posterGroupIdとする）
                if ($tOkr->getParentOkr() !== null) {
                    $parentAndGrandParentOkr = $tOkrRepos->getParentOkr($tOkr->getParentOkr()->getOkrId(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
                    if ($tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
                        if (!empty($parentAndGrandParentOkr[0]['childOkr'])) {
                            if ($parentAndGrandParentOkr[0]['childOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                                $groupIdArray[] = $parentAndGrandParentOkr[0]['childOkr']->getOwnerGroup()->getGroupId();
                            }
                        }
                    } else {
                        if (!empty($parentAndGrandParentOkr[1]['parentOkr'])) {
                            if ($parentAndGrandParentOkr[1]['parentOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                                $groupIdArray[] = $parentAndGrandParentOkr[1]['parentOkr']->getOwnerGroup()->getGroupId();
                            }
                        }
                    }
                }

                // ユーザ所属グループ（共有設定しているグループのみ）を投稿先グループに入れる
                $tGroupMemberRepos = $this->getTGroupMemberRepository();
                $tGroupMemberArray = $tGroupMemberRepos->findBy(array('user' => $tOkr->getOwnerUser()->getUserId(), 'postShareFlg' => DBConstant::FLG_TRUE), array('group' => 'DESC'));

                foreach ($tGroupMemberArray as $tGroupMember) {
                    if ($tOkr->getParentOkr() !== null) {
                        if ($groupIdArray[0] !== $tGroupMember->getGroup()->getGroupId()) {
                            $groupIdArray[] = $tGroupMember->getGroup()->getGroupId();
                        }
                    } else {
                        $groupIdArray[] = $tGroupMember->getGroup()->getGroupId();
                    }

                }

                // 自動投稿内容の主語を取得
                $subject = $tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName();
            } else {
                // 操作対象OKRのオーナーがグループの場合、投稿先グループに入れる
                if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                    $groupIdArray[] = $tOkr->getOwnerGroup()->getGroupId();

                    // 自動投稿内容の主語を取得
                    $subject = $tOkr->getOwnerGroup()->getGroupName();
                }

                // 親OKRまたは祖父母OKRのオーナーがグループの場合、そのグループを投稿先グループに入れる
                if ($tOkr->getParentOkr() !== null) {
                    $parentAndGrandParentOkr = $tOkrRepos->getParentOkr($tOkr->getParentOkr()->getOkrId(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
                    if ($tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
                        if (!empty($parentAndGrandParentOkr[0]['childOkr'])) {
                            if ($parentAndGrandParentOkr[0]['childOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                                $groupIdArray[] = $parentAndGrandParentOkr[0]['childOkr']->getOwnerGroup()->getGroupId();
                            }
                        }
                    } else {
                        if (!empty($parentAndGrandParentOkr[1]['parentOkr'])) {
                            if ($parentAndGrandParentOkr[1]['parentOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                                $groupIdArray[] = $parentAndGrandParentOkr[1]['parentOkr']->getOwnerGroup()->getGroupId();
                            }
                        }
                    }
                }

                // 二重投稿を防ぐ
                if (count($groupIdArray) === 2) {
                    if ($groupIdArray[0] == $groupIdArray[1]) {
                        unset($groupIdArray[1]);
                    }
                }
            }

            // 投稿登録
            $tPost = new TPost();
            if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER || $tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $tPost->setPosterType(DBConstant::POSTER_TYPE_GROUP);
                $tPost->setPosterGroupId($groupIdArray[0]);
            } else {
                $tPost->setPosterType(DBConstant::POSTER_TYPE_COMPANY);
                $tPost->setPosterCompanyId($auth->getCompanyId());
            }
            $tPost->setAutoPost($subject . $autoPost);
            $tPost->setPostedDatetime(DateUtility::getCurrentDatetime());
            $tPost->setOkrActivity($tOkrActivity);
            $tPost->setDisclosureType($tOkr->getDisclosureType());
            $this->persist($tPost);
            foreach ($groupIdArray as $groupId) {
                $tPostTo = new TPostTo();
                $tPostTo->setPost($tPost);
                $tPostTo->setTimelineOwnerGroupId($groupId);
                $this->persist($tPostTo);
            }

            $this->flush();
        }
    }
}
