<?php

namespace AppBundle\Repository;

/**
 * TLikeリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TLikeRepository extends BaseRepository
{
    /**
     * いいねカウントを取得
     *
     * @param integer $postId 投稿ID
     * @return array
     */
    public function getLikesCount($postId)
    {
        $qb = $this->createQueryBuilder('tl');
        $qb->select('COUNT(tl.id)')
            ->where('tl.postId = :postId')
            ->setParameter('postId', $postId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
