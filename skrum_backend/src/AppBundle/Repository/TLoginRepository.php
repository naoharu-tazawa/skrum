<?php

namespace AppBundle\Repository;

/**
 * TLoginリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TLoginRepository extends BaseRepository
{
    /**
     * 最終ログイン日時を取得
     *
     * @param integer $userId ユーザID
     * @return mixed
     */
    public function getLastLogin(int $userId)
    {
        $qb = $this->createQueryBuilder('tl');
        $qb->select('tl.loginDatetime')
            ->where('tl.userId = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('tl.loginDatetime', 'DESC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getResult();

        if (empty($result)) {
            return null;
        } else {
            return $result[0]['loginDatetime'];
        }
    }
}
