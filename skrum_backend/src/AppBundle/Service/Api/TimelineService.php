<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TEmailReservation;
use AppBundle\Entity\TLike;
use AppBundle\Entity\TPost;
use AppBundle\Entity\TPostTo;
use AppBundle\Api\ResponseDTO\PostDTO;
use AppBundle\Api\ResponseDTO\NestedObject\AutoShareDTO;

/**
 * タイムラインサービスクラス
 *
 * @author naoharu.tazawa
 */
class TimelineService extends BaseService
{
    /**
     * タイムライン取得（ユーザ）
     *
     * @param Auth $auth 認証情報
     * @param integer $userId ユーザID
     * @param string $before 取得基準投稿ID
     * @return array
     */
    public function getUserTimeline(Auth $auth, int $userId, int $before = null): array
    {
        $tPostRepos = $this->getTPostRepository();
        $postInfoArray = $tPostRepos->getMyPosts($userId, $before);

        // 返却DTO配列を生成
        $disclosureLogic = $this->getDisclosureLogic();
        $tLikeRepos = $this->getTLikeRepository();
        $postDTOArray = array();
        $mCompany = null;
        while (count($postDTOArray) < 5) {
            if (count($postInfoArray) === 0) {
                break;
            }

            foreach ($postInfoArray as  $postInfo) {
                if (array_key_exists('post', $postInfo)) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkPost($auth->getUserId(), $auth->getRoleLevel(), $postInfo['post'])) {
                        continue;
                    }

                    // DTOに詰め替える
                    $postDTOPost = new PostDTO();
                    $postDTOPost->setPostId($postInfo['post']->getId());
                    $postDTOPost->setPosterType($postInfo['post']->getPosterType());
                    $postDTOPost->setPosterUserId($postInfo['post']->getPosterUserId());
                    $postDTOPost->setPosterUserName($postInfo['lastName'] . ' ' . $postInfo['firstName']);
                    $postDTOPost->setPosterUserImageVersion($postInfo['imageVersion']);
                    $postDTOPost->setPosterUserRoleLevel($postInfo['roleLevel']);
                    $postDTOPost->setPost($postInfo['post']->getPost());
                    $postDTOPost->setPostedDatetime($postInfo['post']->getPostedDatetime());
                    if ($postInfo['post']->getOkrActivity() !== null) {
                        $tOkr = $postInfo['post']->getOkrActivity()->getOkr();

                        $autoShare = new AutoShareDTO();
                        $autoShare->setAutoPost($postInfo['post']->getAutoPost());
                        $autoShare->setOkrId($tOkr->getOkrId());
                        $autoShare->setOkrName($tOkr->getName());
                        $autoShare->setOwnerType($tOkr->getOwnerType());
                        if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                            $autoShare->setOwnerUserId($tOkr->getOwnerUser()->getUserId());
                            $autoShare->setOwnerUserName($tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName());
                            $autoShare->setOwnerUserImageVersion($tOkr->getOwnerUser()->getImageVersion());
                        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                            $autoShare->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                            $autoShare->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
                            $autoShare->setOwnerGroupImageVersion($tOkr->getOwnerGroup()->getImageVersion());
                        } else {
                            $autoShare->setOwnerCompanyId($tOkr->getOwnerCompanyId());
                            if ($mCompany === null) {
                                $mCompanyRepos = $this->getMCompanyRepository();
                                $mCompany = $mCompanyRepos->find($auth->getCompanyId());
                            }
                            $autoShare->setOwnerCompanyName($mCompany->getCompanyName());
                            $autoShare->setOwnerCompanyImageVersion($mCompany->getImageVersion());
                        }
                        $postDTOPost->setAutoShare($autoShare);
                    }

                    // いいね数を取得
                    $likesCount = $tLikeRepos->getLikesCount($postInfo['post']->getId());
                    $postDTOPost->setLikesCount($likesCount);

                    // いいねが押されているかチェック
                    $tLike = $tLikeRepos->findOneBy(array('userId' => $auth->getUserId(), 'postId' => $postInfo['post']->getId()));
                    if (empty($tLike)) {
                        $postDTOPost->setLikedFlg(DBConstant::FLG_FALSE);
                    } else {
                        $postDTOPost->setLikedFlg(DBConstant::FLG_TRUE);
                    }

                    $postDTOPost->setDisclosureType($postInfo['post']->getDisclosureType());

                    // リプライ投稿があればセット
                    $postDTOReplyArray = array();
                    $replyInfoArray = $tPostRepos->getMyReplies($postInfo['post']->getId());
                    foreach ($replyInfoArray as $replyInfo) {
                        if (array_key_exists('reply', $replyInfo)) {
                            $postDTOReply = new PostDTO();
                            $postDTOReply->setPostId($replyInfo['reply']->getId());
                            $postDTOReply->setPosterUserId($replyInfo['reply']->getPosterUserId());
                            $postDTOReply->setPosterUserName($replyInfo['lastName'] . ' ' . $replyInfo['firstName']);
                            $postDTOReply->setPosterUserImageVersion($replyInfo['imageVersion']);
                            $postDTOReply->setPost($replyInfo['reply']->getPost());
                            $postDTOReply->setPostedDatetime($replyInfo['reply']->getPostedDatetime());
                            $postDTOReplyArray[] = $postDTOReply;
                        }
                    }
                    $postDTOPost->setReplies($postDTOReplyArray);

                    $postDTOArray[] = $postDTOPost;
                }
            }

            if (count($postDTOArray) < 5) {
                $before = $postInfoArray[count($postInfoArray) - 1]['post']->getId();

                $postInfoArray = $tPostRepos->getMyPosts($userId, $before);
                if (count($postInfoArray) === 0) {
                    break;
                }
            }
        }

        return $postDTOArray;
    }

    /**
     * タイムライン取得（グループ）
     *
     * @param Auth $auth 認証情報
     * @param integer $groupId グループID
     * @param string $before 取得基準投稿ID
     * @return array
     */
    public function getTimeline(Auth $auth, int $groupId, int $before = null): array
    {
        $tPostRepos = $this->getTPostRepository();
        $postInfoArray = $tPostRepos->getPosts($groupId, $before);

        // 返却DTO配列を生成
        $disclosureLogic = $this->getDisclosureLogic();
        $tLikeRepos = $this->getTLikeRepository();
        $postDTOArray = array();
        $mCompany = null;
        while (count($postDTOArray) < 5) {
            if (count($postInfoArray) === 0) {
                break;
            }

            foreach ($postInfoArray as  $postInfo) {
                if (array_key_exists('post', $postInfo)) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkPost($auth->getUserId(), $auth->getRoleLevel(), $postInfo['post'])) {
                        continue;
                    }

                    // DTOに詰め替える
                    $postDTOPost = new PostDTO();
                    $postDTOPost->setPostId($postInfo['post']->getId());
                    $postDTOPost->setPosterType($postInfo['post']->getPosterType());
                    if ($postInfo['post']->getPosterType() === DBConstant::POSTER_TYPE_USER) {
                        $postDTOPost->setPosterUserId($postInfo['post']->getPosterUserId());
                        $postDTOPost->setPosterUserName($postInfo['lastName'] . ' ' . $postInfo['firstName']);
                        $postDTOPost->setPosterUserImageVersion($postInfo['userImageVersion']);
                        $postDTOPost->setPosterUserRoleLevel($postInfo['roleLevel']);
                    } elseif ($postInfo['post']->getPosterType() === DBConstant::POSTER_TYPE_GROUP) {
                        $postDTOPost->setPosterGroupId($postInfo['post']->getPosterGroupId());
                        $postDTOPost->setPosterGroupName($postInfo['groupName']);
                        $postDTOPost->setPosterGroupImageVersion($postInfo['groupImageVersion']);
                        $postDTOPost->setPosterGroupType($postInfo['groupType']);
                    } else {
                        $postDTOPost->setPosterCompanyId($postInfo['post']->getPosterCompanyId());
                        $postDTOPost->setPosterCompanyName($postInfo['companyName']);
                        $postDTOPost->setPosterCompanyImageVersion($postInfo['companyImageVersion']);
                    }
                    $postDTOPost->setPost($postInfo['post']->getPost());
                    $postDTOPost->setPostedDatetime($postInfo['post']->getPostedDatetime());
                    if ($postInfo['post']->getOkrActivity() !== null) {
                        $tOkr = $postInfo['post']->getOkrActivity()->getOkr();

                        $autoShare = new AutoShareDTO();
                        $autoShare->setAutoPost($postInfo['post']->getAutoPost());
                        $autoShare->setOkrId($tOkr->getOkrId());
                        $autoShare->setOkrName($tOkr->getName());
                        $autoShare->setOwnerType($tOkr->getOwnerType());
                        if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                            $autoShare->setOwnerUserId($tOkr->getOwnerUser()->getUserId());
                            $autoShare->setOwnerUserName($tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName());
                            $autoShare->setOwnerUserImageVersion($tOkr->getOwnerUser()->getImageVersion());
                        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                            $autoShare->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                            $autoShare->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
                            $autoShare->setOwnerGroupImageVersion($tOkr->getOwnerGroup()->getImageVersion());
                        } else {
                            $autoShare->setOwnerCompanyId($tOkr->getOwnerCompanyId());
                            if ($mCompany === null) {
                                $mCompanyRepos = $this->getMCompanyRepository();
                                $mCompany = $mCompanyRepos->find($auth->getCompanyId());
                            }
                            $autoShare->setOwnerCompanyName($mCompany->getCompanyName());
                            $autoShare->setOwnerCompanyImageVersion($mCompany->getImageVersion());
                        }
                        $postDTOPost->setAutoShare($autoShare);
                    }

                    // いいね数を取得
                    $likesCount = $tLikeRepos->getLikesCount($postInfo['post']->getId());
                    $postDTOPost->setLikesCount($likesCount);

                    // いいねが押されているかチェック
                    $tLike = $tLikeRepos->findOneBy(array('userId' => $auth->getUserId(), 'postId' => $postInfo['post']->getId()));
                    if (empty($tLike)) {
                        $postDTOPost->setLikedFlg(DBConstant::FLG_FALSE);
                    } else {
                        $postDTOPost->setLikedFlg(DBConstant::FLG_TRUE);
                    }

                    $postDTOPost->setDisclosureType($postInfo['post']->getDisclosureType());

                    // リプライ投稿があればセット
                    $postDTOReplyArray = array();
                    $replyInfoArray = $tPostRepos->getReplies($postInfo['post']->getId());
                    foreach ($replyInfoArray as $replyInfo) {
                        if (array_key_exists('reply', $replyInfo)) {
                            $postDTOReply = new PostDTO();
                            $postDTOReply->setPostId($replyInfo['reply']->getId());
                            $postDTOReply->setPosterUserId($replyInfo['reply']->getPosterUserId());
                            $postDTOReply->setPosterUserName($replyInfo['lastName'] . ' ' . $replyInfo['firstName']);
                            $postDTOReply->setPosterUserImageVersion($replyInfo['imageVersion']);
                            $postDTOReply->setPost($replyInfo['reply']->getPost());
                            $postDTOReply->setPostedDatetime($replyInfo['reply']->getPostedDatetime());
                            $postDTOReplyArray[] = $postDTOReply;
                        }
                    }
                    $postDTOPost->setReplies($postDTOReplyArray);

                    $postDTOArray[] = $postDTOPost;
                }
            }

            if (count($postDTOArray) < 5) {
                $before = $postInfoArray[count($postInfoArray) - 1]['post']->getId();

                $postInfoArray = $tPostRepos->getPosts($groupId, $before);
                if (count($postInfoArray) === 0) {
                    break;
                }
            }
        }

        return $postDTOArray;
    }

    /**
     * タイムライン取得（会社）
     *
     * @param Auth $auth 認証情報
     * @param integer $companyId 会社ID
     * @param string $before 取得基準日時
     * @return array
     */
    public function getCompanyTimeline(Auth $auth, int $companyId, string $before = null): array
    {
        // 会社IDに対応するグループIDを取得
        $mGroupRepos = $this->getMGroupRepository();
        $mGroup = $mGroupRepos->findBy(array('company' => $companyId, 'groupType' => DBConstant::GROUP_TYPE_COMPANY));

        return $this->getTimeline($auth, $mGroup[0]->getGroupId(), $before);
    }

    /**
     * コメント投稿（グループ）
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return PostDTO
     */
    public function postComment(Auth $auth, array $data, int $groupId, int $companyId = null): PostDTO
    {
        $this->beginTransaction();

        try {
            // コメント登録
            $tPost = new TPost();
            $tPost->setPosterType(DBConstant::POSTER_TYPE_USER);
            $tPost->setPosterUserId($auth->getUserId());
            $tPost->setPost($data['post']);
            $tPost->setPostedDatetime(DateUtility::getCurrentDatetime());
            $tPost->setDisclosureType($data['disclosureType']);
            $this->persist($tPost);

            // 投稿先グループ指定
            $tPostTo = new TPostTo();
            $tPostTo->setPost($tPost);
            $tPostTo->setTimelineOwnerGroupId($groupId);
            $this->persist($tPostTo);

            // メール送信予約
            $mUserRepos = $this->getMUserRepository();
            $mUser = $mUserRepos->find($tPost->getPosterUserId());
            $this->reserveEmails($groupId, $data['post'], $mUser, $data['disclosureType'], $mUser->getCompany()->getSubdomain(), $companyId);

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // レスポンスデータ生成
        $postDTO = new PostDTO();
        $postDTO->setPostId($tPost->getId());
        $postDTO->setPosterType($tPost->getPosterType());
        $postDTO->setPosterUserId($tPost->getPosterUserId());
        $postDTO->setPosterUserName($mUser->getLastName() . ' ' . $mUser->getFirstName());
        $postDTO->setPosterUserImageVersion($mUser->getImageVersion());
        $postDTO->setPost($tPost->getPost());
        $postDTO->setPostedDatetime($tPost->getPostedDatetime());
        $postDTO->setLikesCount(0);
        $postDTO->setLikedFlg(0);
        $postDTO->setDisclosureType($tPost->getDisclosureType());

        return $postDTO;
    }

    /**
     * コメント投稿（会社）
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param integer $companyId 会社ID
     * @return PostDTO
     */
    public function postCompanyComment(Auth $auth, array $data, int $companyId): PostDTO
    {
        // 会社IDに対応するグループIDを取得
        $mGroupRepos = $this->getMGroupRepository();
        $mGroup = $mGroupRepos->findBy(array('company' => $companyId, 'groupType' => DBConstant::GROUP_TYPE_COMPANY));

        return $this->postComment($auth, $data, $mGroup[0]->getGroupId(), $companyId);
    }

    /**
     * タイムライン投稿通知メール送信予約
     *
     * @param integer $timelineOwnerGroupId タイムラインオーナーグループID
     * @param string $post 投稿内容
     * @param MUser $posterEntity 投稿者ユーザエンティティ
     * @param string $disclosureType 公開種別
     * @param string $subdomain サブドメイン
     * @param integer $companyId 会社ID
     * @return void
     */
    private function reserveEmails(int $timelineOwnerGroupId, string $post, MUser $posterEntity, string $disclosureType, string $subdomain, int $companyId = null)
    {
        // グループメンバーを取得
        if ($companyId === null) {
            $tGroupMemberRepos = $this->getTGroupMemberRepository();
            $mUserArray = $tGroupMemberRepos->getAllGroupMembers($timelineOwnerGroupId);
        } else {
            $mUserRepos = $this->getMUserRepository();
            $mUserArray = $mUserRepos->findBy(array('company' => $companyId));
        }

        // タイムラインのグループ名を取得
        $mGroupRepos = $this->getMGroupRepository();
        $mGroup = $mGroupRepos->find($timelineOwnerGroupId);

        $tEmailSettingsRepos = $this->getTEmailSettingsRepository();

        foreach ($mUserArray as $mUser) {
            // 閲覧権限をチェック
            if ($disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_ADMIN || $disclosureType === DBConstant::OKR_DISCLOSURE_TYPE_GROUP_ADMIN) {
                if ($mUser->getRoleAssignment()->getRoleLevel() < DBConstant::ROLE_LEVEL_ADMIN) {
                    continue;
                }
            }

            // メール本文記載変数
            $data = array();
            $data['groupName'] = $mGroup->getGroupName();
            $data['userName'] = $mUser->getLastName() . $mUser->getFirstName();
            $data['posterUserName'] = $posterEntity->getLastName() . $posterEntity->getFirstName();
            $data['post'] = $post;

            // メール配信設定取得
            $tEmailSettings = $tEmailSettingsRepos->findOneBy(array('userId' => $mUser->getUserId()));

            // メール送信予約テーブルに登録
            if ($mUser->getUserId() !== $posterEntity->getUserId() && $tEmailSettings->getOkrTimeline() === DBConstant::EMAIL_OKR_TIMELINE) {
                $tEmailReservation = new TEmailReservation();
                $tEmailReservation->setToEmailAddress($mUser->getEmailAddress());
                $tEmailReservation->setTitle($this->getParameter('post_notice'));
                $tEmailReservation->setBody($this->renderView('mail/post_notice.txt.twig', ['data' => $data, 'subdomain' => $subdomain]));
                $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
                $tEmailReservation->setSendingReservationDatetime(DateUtility::getCurrentDatetime());
                $this->persist($tEmailReservation);
            }
        }
    }

    /**
     * リプライ投稿
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param TPost $tPost 投稿エンティティ
     * @return PostDTO
     */
    public function postReply(Auth $auth, array $data, TPost $tPost): PostDTO
    {
        $this->beginTransaction();

        try {
            // リプライ登録
            $tPostReply = new TPost();
            $tPostReply->setPosterType(DBConstant::OKR_OWNER_TYPE_USER);
            $tPostReply->setPosterUserId($auth->getUserId());
            $tPostReply->setPost($data['post']);
            $tPostReply->setPostedDatetime(DateUtility::getCurrentDatetime());
            $tPostReply->setParent($tPost);
            $this->persist($tPostReply);

            // メール送信予約
            $mUserRepos = $this->getMUserRepository();
            $mUser = $mUserRepos->find($auth->getUserId());
            $originalPosterEntity = $mUserRepos->find($tPost->getPosterUserId());
            $this->reserveEmailsForReply($mUser, $data['post'], $originalPosterEntity, $tPost, $mUser->getCompany()->getSubdomain());

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // レスポンスデータ生成
        $postDTO = new PostDTO();
        $postDTO->setPostId($tPostReply->getId());
        $postDTO->setPosterUserId($tPostReply->getPosterUserId());
        $postDTO->setPosterUserName($mUser->getLastName() . ' ' . $mUser->getFirstName());
        $postDTO->setPosterUserImageVersion($mUser->getImageVersion());
        $postDTO->setPost($tPostReply->getPost());
        $postDTO->setPostedDatetime($tPostReply->getPostedDatetime());

        return $postDTO;
    }

    /**
     * タイムラインリプライ投稿通知メール送信予約
     *
     * @param MUser $mUser リプライ投稿者ユーザエンティティ
     * @param string $post 投稿内容
     * @param MUser $originalPosterEntity リプライ対象の投稿者ユーザエンティティ
     * @param TPost $tPost 投稿エンティティ
     * @param string $subdomain サブドメイン
     * @return void
     */
    private function reserveEmailsForReply(MUser $mUser, string $post, MUser $originalPosterEntity, TPost $tPost, string $subdomain)
    {
        // Eメール宛先メンバーを取得
        $tPostRepos = $this->getTPostRepository();
        $mUserRepos = $this->getMUserRepository();
        $replyInfoArray = $tPostRepos->getReplies($tPost->getId());
        $userIdArray = array();
        foreach ($replyInfoArray as $replyInfo) {
            if (array_key_exists('reply', $replyInfo)) {
                $userIdArray[] = $replyInfo['reply']->getPosterUserId();
            }
        }
        $userIdArray = array_unique($userIdArray);

        $mUserArray = array($originalPosterEntity);
        foreach ($userIdArray as $userId) {
            $replyUserEnity = $mUserRepos->find($userId);

            $mUserArray[] = $replyUserEnity;
        }

        $tEmailSettingsRepos = $this->getTEmailSettingsRepository();

        foreach ($mUserArray as $userEntity) {
            // メール本文記載変数
            $data = array();
            $data['userId'] = $userEntity->getUserId();
            $data['userName'] = $userEntity->getLastName() . $userEntity->getFirstName();
            $data['posterId'] = $mUser->getUserId();
            $data['posterUserName'] = $mUser->getLastName() . $mUser->getFirstName();
            $data['originalPosterId'] = $originalPosterEntity->getUserId();
            $data['originalPosterUserName'] = $originalPosterEntity->getLastName() . $originalPosterEntity->getFirstName();
            $data['post'] = $post;

            // メール配信設定取得
            $tEmailSettings = $tEmailSettingsRepos->findOneBy(array('userId' => $userEntity->getUserId()));

            // メール送信予約テーブルに登録
            if ($userEntity->getUserId() !== $mUser->getUserId() && $tEmailSettings->getOkrTimeline() === DBConstant::EMAIL_OKR_TIMELINE) {
                $tEmailReservation = new TEmailReservation();
                $tEmailReservation->setToEmailAddress($userEntity->getEmailAddress());
                $tEmailReservation->setTitle($this->getParameter('post_notice'));
                $tEmailReservation->setBody($this->renderView('mail/post_reply_notice.txt.twig', ['data' => $data, 'subdomain' => $subdomain]));
                $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
                $tEmailReservation->setSendingReservationDatetime(DateUtility::getCurrentDatetime());
                $this->persist($tEmailReservation);
            }
        }
    }

    /**
     * いいね
     *
     * @param integer $userId ユーザID
     * @param TPost $tPost 投稿エンティティ
     * @return void
     */
    public function like(int $userId, TPost $tPost)
    {
        // リプライにはいいね不可
        if ($tPost->getParent() !== null) {
            throw new ApplicationException('リプライ投稿にはいいねできません');
        }

        // いいねが既に押されていないかチェック
        $tLikeRepos = $this->getTLikeRepository();
        $likeEntity = $tLikeRepos->findOneBy(array('userId' => $userId, 'postId' => $tPost->getId()));
        if (!empty($likeEntity)) {
            throw new DoubleOperationException('既にいいねが押されています');
        }

        $this->beginTransaction();

        try {
            // いいね登録
            $tLike = new TLike();
            $tLike->setUserId($userId);
            $tLike->setPostId($tPost->getId());
            $this->persist($tLike);

            // メール送信予約
            $mUserRepos = $this->getMUserRepository();
            $mUser = $mUserRepos->find($userId);
            $originalPosterEntity = $mUserRepos->find($tPost->getPosterUserId());
            $this->reserveEmailsForLike($mUser, $tPost->getPost(), $tPost->getAutoPost(), $originalPosterEntity, $mUser->getCompany()->getSubdomain());

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * タイムラインいいね通知メール送信予約
     *
     * @param MUser $mUser リプライ投稿者ユーザエンティティ
     * @param string $post 投稿内容
     * @param string $autoPost 自動投稿内容
     * @param MUser $originalPosterEntity リプライ対象の投稿者ユーザエンティティ
     * @param string $subdomain サブドメイン
     * @return void
     */
    private function reserveEmailsForLike(MUser $mUser, string $post = null, string $autoPost = null, MUser $originalPosterEntity, string $subdomain)
    {
        $tEmailSettingsRepos = $this->getTEmailSettingsRepository();

        // メール本文記載変数
        $data = array();
        $data['userName'] = $originalPosterEntity->getLastName() . $originalPosterEntity->getFirstName();
        $data['posterUserName'] = $mUser->getLastName() . $mUser->getFirstName();
        $data['post'] = $post;
        $data['autoPost'] = $autoPost;

        // メール配信設定取得
        $tEmailSettings = $tEmailSettingsRepos->findOneBy(array('userId' => $originalPosterEntity->getUserId()));

        // メール送信予約テーブルに登録
        if ($originalPosterEntity->getUserId() !== $mUser->getUserId() && $tEmailSettings->getOkrTimeline() === DBConstant::EMAIL_OKR_TIMELINE) {
            $tEmailReservation = new TEmailReservation();
            $tEmailReservation->setToEmailAddress($originalPosterEntity->getEmailAddress());
            $tEmailReservation->setTitle($this->getParameter('post_notice'));
            $tEmailReservation->setBody($this->renderView('mail/post_like_notice.txt.twig', ['data' => $data, 'subdomain' => $subdomain]));
            $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
            $tEmailReservation->setSendingReservationDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tEmailReservation);
        }
    }

    /**
     * いいね解除
     *
     * @param integer $userId ユーザID
     * @param integer $postId 投稿ID
     * @return void
     */
    public function detachLike(int $userId, int $postId)
    {
        // いいねが既に解除されている場合、更新処理を行わない
        $tLikeRepos = $this->getTLikeRepository();
        $tLike = $tLikeRepos->findOneBy(array('userId' => $userId, 'postId' => $postId));
        if (empty($tLike)) {
            return;
        }

        // いいね削除
        try {
            $this->remove($tLike);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 投稿削除
     *
     * @param TPost $tPost 削除対象投稿エンティティ
     * @return void
     */
    public function deletePost(TPost $tPost)
    {
        // 削除対象投稿に紐付くリプライを全て取得
        $tPostRepos = $this->getTPostRepository();
        $replyEntityArray = $tPostRepos->findBy(array('parent' => $tPost->getId()));

        $this->beginTransaction();

        // 投稿削除
        try {
            // 該当する宛先を全て削除
            $tPostToRepos = $this->getTPostToRepository();
            $tPostToRepos->deletePostTo($tPost->getId());

            foreach ($replyEntityArray as $replyEntity) {
                $this->remove($replyEntity);
            }

            $this->remove($tPost);

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 投稿公開設定変更
     *
     * @param TPost $tPost 投稿エンティティ
     * @param string $disclosureType 公開種別
     * @return void
     */
    public function changeDisclosure(TPost $tPost, string $disclosureType)
    {
        // 更新対象投稿の公開種別とリクエストJSONで指定された公開種別が一致する場合、更新処理を行わない
        if ($tPost->getDisclosureType() === $disclosureType) {
            return;
        }

        // 投稿エンティティ更新
        $tPost->setDisclosureType($disclosureType);

        try {
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
