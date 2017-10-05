<?php

namespace AppBundle\Repository;

/**
 * TOneOnOneリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TOneOnOneToRepository extends BaseRepository
{
    /**
     * 指定1on1IDの全ての宛先を取得
     *
     * @param integer $oneOnOneId 1on1ID
     * @return array
     */
    public function getAllToUsers(int $oneOnOneId): array
    {
        $qb = $this->createQueryBuilder('tooot');
        $qb->select('mu')
            ->innerJoin('AppBundle:MUser', 'mu', 'WITH', 'tooot.userId = mu.userId')
            ->where('tooot.oneOnOne = :oneOnOneId')
            ->setParameter('oneOnOneId', $oneOnOneId);

        return $qb->getQuery()->getResult();
    }
}
