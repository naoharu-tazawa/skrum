<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

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
        $qb->select('tooot AS tOneOnOneTo', 'mu AS mUser')
            ->innerJoin('AppBundle:MUser', 'mu', 'WITH', 'tooot.userId = mu.userId')
            ->where('tooot.oneOnOne = :oneOnOneId')
            ->setParameter('oneOnOneId', $oneOnOneId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 前回送信先ユーザリストを取得
     *
     * @param integer $userId ユーザID
     * @param string $oneOnOneType 1on1種別
     * @return array
     */
    public function getPreviousDestinations(int $userId, string $oneOnOneType): array
    {
        $sql = <<<SQL
        SELECT m0_.user_id, m0_.last_name, m0_.first_name
        FROM t_one_on_one t0_
        INNER JOIN t_one_on_one_to t1_ ON (t0_.id = t1_.one_on_one_id) AND (t1_.deleted_at IS NULL)
        INNER JOIN m_user m0_ ON (t1_.user_id = m0_.user_id AND m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
        WHERE (t0_.id = (
                SELECT t2_.id
                FROM t_one_on_one t2_
                WHERE (t2_.one_on_one_type = :oneOnOneType AND t2_.sender_user_id = :userId AND t2_.parent_id IS NULL) AND (t2_.deleted_at IS NULL)
                ORDER BY t2_.id DESC
                LIMIT 1))
                AND (t0_.deleted_at IS NULL);
SQL;

        $params['oneOnOneType'] = $oneOnOneType;
        $params['userId'] = $userId;
        $params['archivedFlg'] = DBConstant::FLG_FALSE;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
