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
     * 新規登録ユーザの有効なURLトークンの存在チェック
     *
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getSignupPreUserToken($urltoken)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p')
            ->where('p.urltoken = :urltoken')
            ->andWhere('p.initialUserFlg = :initialUserFlg')
            ->andWhere('p.invalidFlg = :invalidFlg')
            ->andWhere('p.createdAt > :createdAt')
            ->setParameter('urltoken', $urltoken)
            ->setParameter('initialUserFlg', 1)
            ->setParameter('invalidFlg', 0)
            ->setParameter('createdAt', date("Y-m-d H:i:s",strtotime("-1 hour")));

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * 追加登録ユーザの有効なURLトークンの存在チェック
     *
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getAdditionalPreUserToken($urltoken)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p')
        ->where('p.urltoken = :urltoken')
        ->andWhere('p.initialUserFlg = :initialUserFlg')
        ->andWhere('p.invalidFlg = :invalidFlg')
        ->andWhere('p.createdAt > :createdAt')
        ->setParameter('urltoken', $urltoken)
        ->setParameter('initialUserFlg', 0)
        ->setParameter('invalidFlg', 0)
        ->setParameter('createdAt', date("Y-m-d H:i:s",strtotime("-1 hour")));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
