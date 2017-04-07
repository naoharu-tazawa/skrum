<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

/**
 * TOkrリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TOkrRepository extends BaseRepository
{
    /**
     * 指定OKRIDのレコードを取得
     *
     * @param $okrId OKRID
     * @param $companyId 会社ID
     * @return array
     */
    public function getOkr($okrId, $companyId)
    {
        $qb = $this->createQueryBuilder('to');
        $qb->select('to')
            ->innerJoin('AppBundle:TTimeframe', 'tt', 'WITH', 'to.timeframe = tt.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'tt.company = mc.companyId')
            ->where('to.okrId = :okrId')
            ->andWhere('tt.company = :companyId')
            ->setParameter('okrId', $okrId)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定した親ノードの直下に挿入するノードの左値・右値を取得
     *
     * @param $parentOkrId 親ノードのOKRID
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function getLeftRightOfInsertionNode($parentOkrId, $timeframeId)
    {
        $sql = <<<SQL
        SELECT (t3_.left_value * 2 + t3_.right_value) / 3 AS tree_left, (t3_.left_value + t3_.right_value * 2) / 3 AS tree_right
        FROM (
            SELECT CASE WHEN t1_.tree_right IS NULL THEN t0_.tree_left ELSE MAX(t1_.tree_right) END AS left_value, t0_.tree_right AS right_value
            FROM t_okr AS t0_ LEFT OUTER JOIN t_okr AS t1_
            ON (t0_.tree_left = (
                SELECT MAX(t2_.tree_left)
                FROM t_okr AS t2_
                WHERE (t1_.tree_left > t2_.tree_left AND t1_.tree_left < t2_.tree_right AND t2_.timeframe_id = :timeframeIdT2)
                    AND (t2_.deleted_at IS NULL)
                )
                AND t1_.timeframe_id = :timeframeIdT1)
            AND (t1_.deleted_at IS NULL)
            WHERE (t0_.okr_id = :parentOkrId AND t0_.timeframe_id = :timeframeIdT0) AND (t0_.deleted_at IS NULL)
        ) AS t3_;
SQL;
//         $sql = <<<SQL
//         SELECT (t3_.left_value * 2 + t3_.right_value) / 3 AS tree_left, (t3_.left_value + t3_.right_value * 2) / 3 AS tree_right
//         FROM (
//             SELECT CASE WHEN t1_.tree_right IS NULL THEN t0_.tree_left ELSE MAX(t1_.tree_right) END AS left_value, t0_.tree_right AS right_value
//             FROM t_okr AS t0_ LEFT OUTER JOIN t_okr AS t1_
//             ON (t0_.tree_left = (
//                 SELECT MAX(t2_.tree_left)
//                 FROM t_okr AS t2_
//                 WHERE (t1_.tree_left > t2_.tree_left AND t1_.tree_left < t2_.tree_right)
//                 AND (t2_.deleted_at IS NULL)
//                 )
//             )
//             AND (t1_.deleted_at IS NULL)
//             WHERE (t0_.okr_id = :parentOkrId) AND (t0_.deleted_at IS NULL)
//         ) AS t3_;
// SQL;

        $params['timeframeIdT0'] = $timeframeId;
        $params['timeframeIdT1'] = $timeframeId;
        $params['timeframeIdT2'] = $timeframeId;
        $params['parentOkrId'] = $parentOkrId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
        $resultArray = $stmt->fetchAll();

        return $resultArray[0];
    }

    /**
     * ルートノードを取得
     *
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function getRootNode($timeframeId)
    {
        $qb = $this->createQueryBuilder('to');
        $qb->select('to')
            ->innerJoin('AppBundle:TTimeframe', 'tt', 'WITH', 'to.timeframe = tt.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'tt.company = mc.companyId')
            ->where('to.timeframe = :timeframeId')
            ->andWhere('to.type = :type')
            ->setParameter('timeframeId', $timeframeId)
            ->setParameter('type', DBConstant::OKR_TYPE_ROOT_NODE);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
