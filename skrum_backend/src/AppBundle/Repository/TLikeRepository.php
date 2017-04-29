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
     * @return mixed
     * @throws NonUniqueResultException If the query result is not unique.
     * @throws NoResultException        If the query returned no result.
     */
    public function getLikesCount(int $postId)
    {
        $qb = $this->createQueryBuilder('tl');
        $qb->select('COUNT(tl.id)')
            ->where('tl.postId = :postId')
            ->setParameter('postId', $postId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
