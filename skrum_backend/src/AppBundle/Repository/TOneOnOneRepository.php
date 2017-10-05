<?php

namespace AppBundle\Repository;

use AppBundle\Utils\Auth;
use AppBundle\Utils\DBConstant;

/**
 * TOneOnOneリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TOneOnOneRepository extends BaseRepository
{
    /**
     * 指定1on1IDのレコードを取得
     *
     * @param integer $oneOnOneId 1on1ID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getOneOnOne(int $oneOnOneId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('tooo');
        $qb->select('tooo')
            ->innerJoin('AppBundle:MUser', 'mu', 'WITH', 'tooo.senderUserId = mu.userId')
            ->where('tooo.id = :oneOnOneId')
            ->andWhere('mu.company = :companyId')
            ->setParameter('oneOnOneId', $oneOnOneId)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 1on1新着履歴を取得
     *
     * @param Auth $auth 認証情報
     * @param string $startDatetime 開始日（検索）
     * @param string $endDatetime 終了日（検索）
     * @param string $before 取得基準日時（検索）
     * @param string $keyword 検索ワード
     * @return array
     */
    public function getNewArrivalOneOnOne(Auth $auth, string $startDatetime, string $endDatetime, string $before, string $keyword = null): array
    {
        $sql = <<<SQL
        (
        SELECT t0_.id, t0_.one_on_one_type, t0_.sender_user_id, t0_.target_date, t0_.due_date, t0_.feedback_type, t0_.interviewee_user_id, t0_.okr_id, t0_.new_arrival_datetime, m2_.user_id, m2_.last_name, m2_.first_name, m2_.image_version
        FROM t_one_on_one t0_
        INNER JOIN m_user m2_
        ON (t0_.sender_user_id = m2_.user_id) AND (m2_.deleted_at IS NULL)
        LEFT OUTER JOIN t_one_on_one_to t1_
        ON (t0_.id = t1_.one_on_one_id) AND (t1_.deleted_at IS NULL)
        INNER JOIN (
            SELECT m0_.user_id, m0_.last_name, m0_.first_name, CONCAT(m0_.last_name, m0_.first_name) AS name, m0_.image_version
            FROM m_user m0_
            WHERE (m0_.company_id = :companyId AND m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)) AS m1_
        ON (t0_.sender_user_id = m1_.user_id AND m1_.user_id = :userId)
        WHERE (
            (t0_.new_arrival_datetime >= :startDatetime AND t0_.new_arrival_datetime < :endDatetime)
            AND t0_.new_arrival_datetime < :before
            AND t0_.parent_id IS NULL
            AND m1_.name LIKE :keyword)
            AND (t0_.deleted_at IS NULL)
        )

        UNION

        (
        SELECT t0_.id, t0_.one_on_one_type, t0_.sender_user_id, t0_.target_date, t0_.due_date, t0_.feedback_type, t0_.interviewee_user_id, t0_.okr_id, t0_.new_arrival_datetime, m2_.user_id, m2_.last_name, m2_.first_name, m2_.image_version
        FROM t_one_on_one t0_
        INNER JOIN m_user m2_
        ON (t0_.sender_user_id = m2_.user_id) AND (m2_.deleted_at IS NULL)
        LEFT OUTER JOIN t_one_on_one_to t1_
        ON (t0_.id = t1_.one_on_one_id) AND (t1_.deleted_at IS NULL)
        INNER JOIN (
            SELECT m0_.user_id, m0_.last_name, m0_.first_name, CONCAT(m0_.last_name, m0_.first_name) AS name, m0_.image_version
            FROM m_user m0_
            WHERE (m0_.company_id = :companyId AND m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)) AS m1_
        ON (t1_.user_id = m1_.user_id AND m1_.user_id = :userId)
        WHERE (
            (t0_.new_arrival_datetime >= :startDatetime AND t0_.new_arrival_datetime < :endDatetime)
            AND t0_.new_arrival_datetime < :before
            AND t0_.parent_id IS NULL
            AND m1_.name LIKE :keyword)
            AND (t0_.deleted_at IS NULL)
        )

        ORDER BY new_arrival_datetime DESC
        LIMIT 50;
SQL;

        $params['companyId'] = $auth->getCompanyId();
        $params['archivedFlg'] = DBConstant::FLG_FALSE;
        $params['userId'] = $auth->getUserId();
        $params['startDatetime'] = $startDatetime;
        $params['endDatetime'] = $endDatetime;
        $params['before'] = $before;
        $params['keyword'] = $keyword . '%';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * 指定1on1とその全てのリプライを取得
     *
     * @param integer $oneOnOneId 1on1ID
     * @return array
     */
    public function getOneOnOneStream(int $oneOnOneId): array
    {
        $qb = $this->createQueryBuilder('tooo1');
        $qb->select('tooo1, tooo2')
            ->leftJoin('AppBundle:TOneOnOne', 'tooo2', 'WITH', 'tooo1.id = tooo2.parent')
            ->where('tooo1.id = :oneOnOneId')
            ->setParameter('oneOnOneId', $oneOnOneId)
            ->addOrderBy('tooo1.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
