<?php

namespace AppBundle\Repository;

/**
 * TGroupMemberリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TGroupMemberRepository extends BaseRepository
{
    /**
     * 指定グループID、ユーザIDのレコードを取得
     *
     * @param $groupId グループID
     * @param $userId ユーザID
     * @return array
     */
    public function getGroupMember($groupId, $userId)
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('tgm')
            ->where('tgm.group = :groupId')
            ->andWhere('tgm.user = :userId')
            ->setParameter('groupId', $groupId)
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDの全レコードを取得
     *
     * @param $groupId グループID
     * @return array
     */
    public function getAllGroupMembers($groupId)
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('mu')
            ->innerJoin('AppBundle:MUser', 'mu', 'WITH', 'tgm.user = mu.userId')
            ->where('tgm.group = :groupId')
            ->setParameter('groupId', $groupId);

        return $qb->getQuery()->getResult();
    }
}
