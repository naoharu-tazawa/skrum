<?php

namespace AppBundle\Repository;

/**
 * TPostリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TPostRepository extends BaseRepository
{
    /**
     * 指定投稿IDのレコードを取得
     *
     * @param integer $postId 投稿ID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getPost(int $postId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('tp');
        $qb->select('tp')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tp.timelineOwnerGroupId = mg.groupId')
            ->where('tp.id = :postId')
            ->andWhere('mg.company = :companyId')
            ->setParameter('postId', $postId)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDのタイムライン（コメント投稿のみ）を取得
     *
     * @param integer $groupId グループID
     * @param string $before 取得基準投稿ID
     * @return array
     */
    public function getPosts(int $groupId, int $before = null): array
    {
        $qb = $this->createQueryBuilder('tp');
        $qb->select('tp AS post', 'mu.lastName', 'mu.firstName', 'mg.groupName', 'mc.companyName')
            ->leftJoin('AppBundle:MUser', 'mu', 'WITH', 'tp.posterUserId = mu.userId')
            ->leftJoin('AppBundle:MGroup', 'mg', 'WITH', 'tp.posterGroupId = mg.groupId')
            ->leftJoin('AppBundle:MCompany', 'mc', 'WITH', 'tp.posterCompanyId = mc.companyId')
            ->where('tp.timelineOwnerGroupId = :timelineOwnerGroupId')
            ->andWhere('tp.parent IS NULL')
            ->setParameter('timelineOwnerGroupId', $groupId);

        if ($before !== null) {
            $qb->andWhere('tp.id < :id')
                ->setParameter('id', $before);
        }

        $qb->orderBy('tp.id', 'DESC')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDのタイムライン（リプライ投稿のみ）を取得
     *
     * @param integer $groupId グループID
     * @param integer $postId 投稿ID
     * @return array
     */
    public function getReplies(int $groupId, int $postId): array
    {
        $qb = $this->createQueryBuilder('tp');
        $qb->select('tp AS reply', 'mu.lastName', 'mu.firstName')
            ->leftJoin('AppBundle:MUser', 'mu', 'WITH', 'tp.posterUserId = mu.userId')
            ->where('tp.timelineOwnerGroupId = :timelineOwnerGroupId')
            ->andWhere('tp.parent = :parentId')
            ->setParameter('timelineOwnerGroupId', $groupId)
            ->setParameter('parentId', $postId)
            ->addOrderBy('tp.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
