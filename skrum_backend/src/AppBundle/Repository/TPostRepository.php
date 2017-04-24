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
     * 指定グループIDのタイムラインを取得
     *
     * @param integer $groupId グループID
     * @return array
     */
    public function getTimeline(int $groupId): array
    {
        $qb = $this->createQueryBuilder('tp1');
        $qb->select('tp1 AS post', 'toa AS okrActivity', 'tp2 AS reply')
            ->leftJoin('AppBundle:TOkrActivity', 'toa', 'WITH', 'tp1.okrActivity = toa.id')
            ->leftJoin('AppBundle:TPost', 'tp2', 'WITH', 'tp1.id = tp2.parent')
            ->where('tp1.timelineOwnerGroupId = :timelineOwnerGroupId')
            ->andWhere('tp1.parent IS NULL')
            ->setParameter('timelineOwnerGroupId', $groupId)
            ->orderBy('tp1.postedDatetime', 'DESC')
            ->addOrderBy('tp2.postedDatetime', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
