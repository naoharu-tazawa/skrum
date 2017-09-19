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
     * @param string $before 取得基準投稿ID
     * @return array
     */
    public function getUserTimeline(Auth $auth, int $before = null): array
    {
        $tPostRepos = $this->getTPostRepository();
        $postInfoArray = $tPostRepos->getMyPosts($auth->getUserId(), $before);

        // 返却DTO配列を生成
        $disclosureLogic = $this->getDisclosureLogic();
        $tLikeRepos = $this->getTLikeRepository();
        $postDTOArray = array();
        $companyName = null;
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
                        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                            $autoShare->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                            $autoShare->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
                        } else {
                            $autoShare->setOwnerCompanyId($tOkr->getOwnerCompanyId());
                            if ($companyName === null) {
                                $mCompanyRepos = $this->getMCompanyRepository();
                                $mCompany = $mCompanyRepos->find($auth->getCompanyId());
                                $companyName = $mCompany->getCompanyName();
                            }
                            $autoShare->setOwnerCompanyName($companyName);
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
        $companyName = null;
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
                        $postDTOPost->setPosterUserRoleLevel($postInfo['roleLevel']);
                    } elseif ($postInfo['post']->getPosterType() === DBConstant::POSTER_TYPE_GROUP) {
                        $postDTOPost->setPosterGroupId($postInfo['post']->getPosterGroupId());
                        $postDTOPost->setPosterGroupName($postInfo['groupName']);
                        $postDTOPost->setPosterGroupType($postInfo['groupType']);
                    } else {
                        $postDTOPost->setPosterCompanyId($postInfo['post']->getPosterCompanyId());
                        $postDTOPost->setPosterCompanyName($postInfo['companyName']);
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
                        } elseif ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                            $autoShare->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                            $autoShare->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
                        } else {
                            $autoShare->setOwnerCompanyId($tOkr->getOwnerCompanyId());
                            if ($companyName === null) {
                                $mCompanyRepos = $this->getMCompanyRepository();
                                $mCompany = $mCompanyRepos->find($auth->getCompanyId());
                                $companyName = $mCompany->getCompanyName();
                            }
                            $autoShare->setOwnerCompanyName($companyName);
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
                    $replyInfoArray = $tPostRepos->getReplies($groupId, $postInfo['post']->getId());
                    foreach ($replyInfoArray as $replyInfo) {
                        if (array_key_exists('reply', $replyInfo)) {
                            $postDTOReply = new PostDTO();
                            $postDTOReply->setPostId($replyInfo['reply']->getId());
                            $postDTOReply->setPosterUserId($replyInfo['reply']->getPosterUserId());
                            $postDTOReply->setPosterUserName($replyInfo['lastName'] . ' ' . $replyInfo['firstName']);
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
     * @return PostDTO
     */
    public function postComment(Auth $auth, array $data, int $groupId): PostDTO
    {
        $this->beginTransaction();

        try {
            // コメント登録
            $tPost = new TPost();
            $tPost->setTimelineOwnerGroupId($groupId);
            $tPost->setPosterType(DBConstant::POSTER_TYPE_USER);
            $tPost->setPosterUserId($auth->getUserId());
            $tPost->setPost($data['post']);
            $tPost->setPostedDatetime(DateUtility::getCurrentDatetime());
            $tPost->setDisclosureType($data['disclosureType']);
            $this->persist($tPost);

            // メール送信予約
            $mUserRepos = $this->getMUserRepository();
            $mUser = $mUserRepos->find($tPost->getPosterUserId());
            $this->reserveEmails($groupId, $mUser, $mUser->getCompany()->getSubdomain());

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

        return $this->postComment($auth, $data, $mGroup[0]->getGroupId());
    }

    /**
     * タイムライン投稿通知メール送信予約
     *
     * @param integer $timelineOwnerGroupId タイムラインオーナーグループID
     * @param MUser $posterEntity 投稿者ユーザエンティティ
     * @param string $subdomain サブドメイン
     * @return void
     */
    private function reserveEmails(int $timelineOwnerGroupId, MUser $posterEntity, string $subdomain)
    {
        // 会社IDに対応するグループIDを取得
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $mUserArray = $tGroupMemberRepos->getAllGroupMembers($timelineOwnerGroupId);

        // タイムラインのグループ名を取得
        $mGroupRepos = $this->getMGroupRepository();
        $mGroup = $mGroupRepos->find($timelineOwnerGroupId);

        foreach ($mUserArray as $mUser) {
            // メール本文記載変数
            $data = array();
            $data['groupName'] = $mGroup->getGroupName();
            $data['userName'] = $mUser->getLastName() . $mUser->getFirstName();
            $data['posterUserName'] = $posterEntity->getLastName() . $posterEntity->getFirstName();

            // メール送信予約テーブルに登録
            if ($mUser->getUserId() !== $posterEntity->getUserId()) {
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
        // リプライ登録
        $tPostReply = new TPost();
        $tPostReply->setTimelineOwnerGroupId($tPost->getTimelineOwnerGroupId());
        $tPostReply->setPosterType(DBConstant::OKR_OWNER_TYPE_USER);
        $tPostReply->setPosterUserId($auth->getUserId());
        $tPostReply->setPost($data['post']);
        $tPostReply->setPostedDatetime(DateUtility::getCurrentDatetime());
        $tPostReply->setParent($tPost);

        try {
            $this->persist($tPostReply);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }

        // レスポンスデータ生成
        $mUserRepos = $this->getMUserRepository();
        $mUser = $mUserRepos->find($tPostReply->getPosterUserId());

        $postDTO = new PostDTO();
        $postDTO->setPostId($tPostReply->getId());
        $postDTO->setPosterUserId($tPostReply->getPosterUserId());
        $postDTO->setPosterUserName($mUser->getLastName() . ' ' . $mUser->getFirstName());
        $postDTO->setPost($tPostReply->getPost());
        $postDTO->setPostedDatetime($tPostReply->getPostedDatetime());

        return $postDTO;
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

        // いいね登録
        $tLike = new TLike();
        $tLike->setUserId($userId);
        $tLike->setPostId($tPost->getId());

        try {
            $this->persist($tLike);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
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
