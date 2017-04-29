<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;

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
     * @param string $urltoken URLトークン
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getSignupPreUserToken(string $urltoken)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p')
            ->where('p.urltoken = :urltoken')
            ->andWhere('p.initialUserFlg = :initialUserFlg')
            ->andWhere('p.invalidFlg = :invalidFlg')
            ->andWhere('p.createdAt > :createdAt')
            ->setParameter('urltoken', $urltoken)
            ->setParameter('initialUserFlg', DBConstant::FLG_TRUE)
            ->setParameter('invalidFlg', DBConstant::FLG_FALSE)
            ->setParameter('createdAt', date(DateUtility::DATETIME_FORMAT, strtotime("-1 hour")));

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * 追加登録ユーザの有効なURLトークンの存在チェック
     *
     * @param string $urltoken URLトークン
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getAdditionalPreUserToken(string $urltoken)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p')
            ->where('p.urltoken = :urltoken')
            ->andWhere('p.initialUserFlg = :initialUserFlg')
            ->andWhere('p.invalidFlg = :invalidFlg')
            ->andWhere('p.createdAt > :createdAt')
            ->setParameter('urltoken', $urltoken)
            ->setParameter('initialUserFlg', DBConstant::FLG_FALSE)
            ->setParameter('invalidFlg', DBConstant::FLG_FALSE)
            ->setParameter('createdAt', date(DateUtility::DATETIME_FORMAT, strtotime("-72 hour")));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
