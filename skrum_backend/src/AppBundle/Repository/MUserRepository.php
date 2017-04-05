<?php

namespace AppBundle\Repository;

/**
 * MUserリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class MUserRepository extends BaseRepository
{
    /**
     * 引数で指定されたユーザIDのユーザ情報を取得
     *
     * @return mixed
     * @throws NonUniqueResultException If the query result is not unique.
     * @throws NoResultException        If the query returned no result.
     */
//     public function getUser($userId)
//     {
//         $qb = $this->createQueryBuilder('u'); // 'u'はエイリアス。エイリアスは何でも良い
//         $qb->select('u.userId, u.lastName, u.firstName, u.emailAddress') // 全てのカラムを取得する場合はselectメソッドは不要
//             ->where('u.userId = :userId')
//             ->orderBy('u.lastName', 'ASC')
//             ->setParameter('userId', $userId);

//         return $qb->getQuery()->getSingleResult(); // 取得レコードが複数ある場合はgetResultメソッドを使用
//     }

    /**
     * 指定ユーザIDのレコードを取得
     *
     * @param $userId ユーザID
     * @param $companyId 会社ID
     * @return array
     */
    public function getUser($userId, $companyId)
    {
        $qb = $this->createQueryBuilder('mu');
        $qb->select('mu')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'mu.company = mc.companyId')
            ->where('mu.userId = :userId')
            ->andWhere('mu.company = :companyId')
            ->setParameter('userId', $userId)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }
}
