<?php

namespace AppBundle\Repository;

/**
 * Userリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class UserRepository extends BaseRepository
{
    public function getUsers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->where('u.USER_ID = :userId')
            ->orderBy('u.USER_NAME_L', 'ASC')
            ->setParameter('userId', 2);
        return $qb->getQuery()->getResult();
    }
}
