<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

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
            ->innerJoin('AppBundle:TPostTo', 'tpt', 'WITH', 'tp.id = tpt.post')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tpt.timelineOwnerGroupId = mg.groupId')
            ->where('tp.id = :postId')
            ->andWhere('mg.company = :companyId')
            ->setParameter('postId', $postId)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定ユーザIDが投稿者のタイムライン（コメント投稿のみ）を取得
     *
     * @param integer $userId ユーザID
     * @param string $before 取得基準投稿ID
     * @return array
     */
    public function getMyPosts(int $userId, int $before = null): array
    {
        $qb = $this->createQueryBuilder('tp');
        $qb->select('tp AS post', 'mu.lastName', 'mu.firstName', 'mu.imageVersion', 'mra.roleLevel')
            ->leftJoin('AppBundle:MUser', 'mu', 'WITH', 'tp.posterUserId = mu.userId')
            ->leftJoin('AppBundle:MRoleAssignment', 'mra', 'WITH', 'mu.roleAssignment = mra.roleAssignmentId')
            ->where('tp.posterType = :posterType')
            ->andWhere('tp.posterUserId = :posterUserId')
            ->andWhere('tp.parent IS NULL')
            ->setParameter('posterType', DBConstant::POSTER_TYPE_USER)
            ->setParameter('posterUserId', $userId);

        if ($before !== null) {
            $qb->andWhere('tp.id < :id')
            ->setParameter('id', $before);
        }

        $qb->orderBy('tp.id', 'DESC')
        ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定投稿IDのタイムライン（リプライ投稿のみ）を取得
     *
     * @param integer $postId 投稿ID
     * @return array
     */
    public function getMyReplies(int $postId): array
    {
        $qb = $this->createQueryBuilder('tp');
        $qb->select('tp AS reply', 'mu.lastName', 'mu.firstName', 'mu.imageVersion')
            ->leftJoin('AppBundle:MUser', 'mu', 'WITH', 'tp.posterUserId = mu.userId')
            ->where('tp.parent = :parentId')
            ->setParameter('parentId', $postId)
            ->addOrderBy('tp.id', 'ASC');

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
        $qb->select('tp AS post', 'mu.lastName', 'mu.firstName', 'mu.imageVersion as userImageVersion', 'mra.roleLevel', 'mg.groupName', 'mg.imageVersion AS groupImageVersion', 'mg.groupType', 'mc.companyName', 'mc.imageVersion AS companyImageVersion')
            ->innerJoin('AppBundle:TPostTo', 'tpt', 'WITH', 'tp.id = tpt.post')
            ->leftJoin('AppBundle:MUser', 'mu', 'WITH', 'tp.posterUserId = mu.userId')
            ->leftJoin('AppBundle:MRoleAssignment', 'mra', 'WITH', 'mu.roleAssignment = mra.roleAssignmentId')
            ->leftJoin('AppBundle:MGroup', 'mg', 'WITH', 'tp.posterGroupId = mg.groupId')
            ->leftJoin('AppBundle:MCompany', 'mc', 'WITH', 'tp.posterCompanyId = mc.companyId')
            ->where('tpt.timelineOwnerGroupId = :timelineOwnerGroupId')
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
     * 指定投稿IDのタイムライン（リプライ投稿のみ）を取得
     *
     * @param integer $postId 投稿ID
     * @return array
     */
    public function getReplies(int $postId): array
    {
        $qb = $this->createQueryBuilder('tp');
        $qb->select('tp AS reply', 'mu.lastName', 'mu.firstName', 'mu.imageVersion')
            ->leftJoin('AppBundle:MUser', 'mu', 'WITH', 'tp.posterUserId = mu.userId')
            ->where('tp.parent = :parentId')
            ->setParameter('parentId', $postId)
            ->addOrderBy('tp.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
