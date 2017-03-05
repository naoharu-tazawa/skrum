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
            ->from('AppBundle\Entity\User', 'u')
            ->where('u.userId = :userId')
            ->orderBy('u.userNameL', 'ASC')
            ->setParameter('userId', 2);
        return $qb->getQuery()->getSingleResult();
    }
}
