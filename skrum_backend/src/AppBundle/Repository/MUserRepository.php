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

    /**
     * ユーザ検索
     *
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchUser($keyword, $companyId)
    {
        $sql = <<<SQL
        SELECT m1_.userId, m1_.lastName, m1_.firstName
        FROM (
            SELECT m0_.user_id AS userId, m0_.last_name AS lastName, m0_.first_name AS firstName, CONCAT(m0_.last_name, m0_.first_name) AS name
            FROM m_user m0_
            WHERE (m0_.company_id = :companyId) AND (m0_.deleted_at IS NULL)
            ) AS m1_
        WHERE m1_.name LIKE :keyword;
SQL;

        $params['companyId'] = $companyId;
        $params['keyword'] = $keyword . '%';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * 指定会社IDのユーザ数を取得
     *
     * @param $companyId 会社ID
     * @return array
     */
    public function getUserCount($companyId)
    {
        $qb = $this->createQueryBuilder('mu');
        $qb->select('COUNT(mu.userId)')
            ->where('mu.company = :companyId')
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
