<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\DateUtility;
use AppBundle\Entity\TPost;
use AppBundle\Api\ResponseDTO\PostDTO;

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

                $postDTOPost = new PostDTO();
                $postDTOPost->setPostId($tPostArray[$i]['post']->getId());
                $postDTOPost->setPosterId($tPostArray[$i]['post']->getPosterId());
                $postDTOPost->setPost($tPostArray[$i]['post']->getPost());
                $postDTOPost->setPostedDatetime($tPostArray[$i]['post']->getPostedDatetime());

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
}
