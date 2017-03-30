<?php

namespace AppBundle\Repository;

/**
 * TPreUserリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TPreUserRepository extends BaseRepository
{
    /**
     * 有効なURLトークンの存在チェック
     *
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function checkUrltoken($urltoken)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p')
            ->where('p.urltoken = :urltoken')
            ->andWhere('p.initialUserFlg = :initialUserFlg')
            ->andWhere('p.invalidFlg = :invalidFlg')
            ->andWhere('p.createdAt > :lifetime')
            ->setParameter('urltoken', $urltoken)
            ->setParameter('initialUserFlg', 1)
            ->setParameter('invalidFlg', 0)
            ->setParameter('lifetime', date("Y-m-d H:i:s",strtotime("-1 hour")));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
