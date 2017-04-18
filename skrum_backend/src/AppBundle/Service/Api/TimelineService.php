<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\DateUtility;
use AppBundle\Entity\TLike;
use AppBundle\Entity\TPost;
use AppBundle\Api\ResponseDTO\PostDTO;
use AppBundle\Utils\DBConstant;

/**
 * タイムラインサービスクラス
 *
 * @author naoharu.tazawa
 */
class TimelineService extends BaseService
{
    /**
     * タイムライン取得
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param integer $groupId グループID
     * @return array
     */
    public function getTimeline($auth, $groupId)
    {
        $tPostRepos = $this->getTPostRepository();
        $tPostArray = $tPostRepos->getTimeline($groupId);

        // DTOに詰め替える
        $disclosureLogic = $this->getDisclosureLogic();
        $postDTOArray = array();
        $flg = false;
        for ($i = 0; $i < count($tPostArray); $i++) {
            if (array_key_exists('post', $tPostArray[$i])) {
                // 2回目のループ以降、前回ループ分のDTOを配列に入れる
                if ($i != 0) {
                    if (!$outOfDisclosureFlg) {
                        if ($flg) {
                            $postDTOPost->setReplies($postDTOReplyArray);
                        }
                        $postDTOArray[] = $postDTOPost;
                    }
                }

                // 非公開フラグをFALSEにする
                $outOfDisclosureFlg= false;

                // 閲覧権限をチェック
                if (!$disclosureLogic->checkPost($auth->getUserId(), $auth->getRoleLevel(), $tPostArray[$i]['post'])) {
                    $outOfDisclosureFlg = true;
                    $flg = false;
                    continue;
                }

                // いいね数を取得
                $tLikeRepos = $this->getTLikeRepository();
                $likesCount = $tLikeRepos->getLikesCount($tPostArray[$i]['post']->getId());

                // いいねが押されているかチェック
                $tLike = $tLikeRepos->findOneBy(array('userId' => $auth->getUserId(), 'postId' => $tPostArray[$i]['post']->getId()));

                $postDTOPost = new PostDTO();
                $postDTOPost->setPostId($tPostArray[$i]['post']->getId());
                $postDTOPost->setPosterId($tPostArray[$i]['post']->getPosterId());
                $postDTOPost->setPost($tPostArray[$i]['post']->getPost());
                $postDTOPost->setPostedDatetime($tPostArray[$i]['post']->getPostedDatetime());
                $postDTOPost->setLikesCount($likesCount);
                if (empty($tLike)) {
                    $postDTOPost->setLikedFlg(DBConstant::FLG_FALSE);
                } else {
                    $postDTOPost->setLikedFlg(DBConstant::FLG_TRUE);
                }

                $postDTOReplyArray = array();
                $flg = false;
            } elseif (array_key_exists('okrActivity', $tPostArray[$i])) {
                // 非公開の場合、スキップ
                if ($outOfDisclosureFlg) continue;

                // OKRアクティビティがnullの場合、スキップ
                if ($tPostArray[$i]['okrActivity'] == null) {
                    // 最終ループ
                    if ($i == (count($tPostArray) - 1)) {
                        $postDTOArray[] = $postDTOPost;
                    }
                    continue;
                }

                $postDTOPost->setOkrId($tPostArray[$i]['okrActivity']->getOkr()->getOkrId());
                $flg = false;
            } else {
                // 非公開の場合、スキップ
                if ($outOfDisclosureFlg) continue;

                // リプライがnullの場合、スキップ
                if ($tPostArray[$i]['reply'] == null) {
                    // 最終ループ
                    if ($i == (count($tPostArray) - 1)) {
                        $postDTOArray[] = $postDTOPost;
                    }
                    continue;
                }

                $postDTOReply = new PostDTO();
                $postDTOReply->setPostId($tPostArray[$i]['reply']->getId());
                $postDTOReply->setPosterId($tPostArray[$i]['reply']->getPosterId());
                $postDTOReply->setPost($tPostArray[$i]['reply']->getPost());
                $postDTOReply->setPostedDatetime($tPostArray[$i]['reply']->getPostedDatetime());

                $postDTOReplyArray[] = $postDTOReply;
                $flg = true;
            }

            // 最終ループ
            if ($i == (count($tPostArray) - 1)) {
                if (!$outOfDisclosureFlg) {
                    if ($flg) {
                        $postDTOPost->setReplies($postDTOReplyArray);
                    }
                    $postDTOArray[] = $postDTOPost;
                }
            }
        }

        return $postDTOArray;
    }

    /**
     * コメント投稿
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param integer $groupId グループID
     * @return array
     */
    public function postComment($auth, $data, $groupId)
    {
        // コメント登録
        $tPost = new TPost();
        $tPost->setTimelineOwnerGroupId($groupId);
        $tPost->setPosterId($auth->getUserId());
        $tPost->setPost($data['post']);
        $tPost->setPostedDatetime(DateUtility::getCurrentDatetime());
        $tPost->setDisclosureType($data['disclosureType']);

        try {
            $this->persist($tPost);
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * リプライ投稿
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param \AppBundle\Entity\TPost $tPost 投稿エンティティ
     * @return array
     */
    public function postReply($auth, $data, $tPost)
    {
        // リプライ登録
        $tPostReply = new TPost();
        $tPostReply->setTimelineOwnerGroupId($tPost->getTimelineOwnerGroupId());
        $tPostReply->setPosterId($auth->getUserId());
        $tPostReply->setPost($data['post']);
        $tPostReply->setPostedDatetime(DateUtility::getCurrentDatetime());
        $tPostReply->setParent($tPost);
        $tPostReply->setDisclosureType($tPost->getDisclosureType());

        try {
            $this->persist($tPostReply);
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * いいね
     *
     * @param integer $userId ユーザID
     * @param integer $postId 投稿ID
     * @return void
     */
    public function like($userId, $postId)
    {
        // いいねが既に押されていないかチェック
        $tLikeRepos = $this->getTLikeRepository();
        $likeEntity = $tLikeRepos->findOneBy(array('userId' => $userId, 'postId' => $postId));
        if (!empty($likeEntity)) {
            throw new ApplicationException('既にいいねが押されています');
        }

        // いいね登録
        $tLike = new TLike();
        $tLike->setUserId($userId);
        $tLike->setPostId($postId);

        try {
            $this->persist($tLike);
            $this->flush();
        } catch(\Exception $e) {
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
    public function detachLike($userId, $postId)
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
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
