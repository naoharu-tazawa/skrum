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
     * 目標とキーリザルトの一覧を取得
     *
     * @param $userId ユーザID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getObjectivesAndKeyResults($userId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as objective', 'to2 as keyResult')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->innerJoin('AppBundle:TTimeframe', 'tt2', 'WITH', 'to2.timeframe = tt2.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc2', 'WITH', 'tt2.company = mc2.companyId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerUser = :ownerUserId1')
            ->andWhere('tt1.company = :companyId1')
            ->andWhere('to2.timeframe = :timeframeId2')
            ->andWhere('tt2.company = :companyId2')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_USER)
            ->setParameter('ownerUserId1', $userId)
            ->setParameter('companyId1', $companyId)
            ->setParameter('timeframeId2', $timeframeId)
            ->setParameter('companyId2', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定した親ノードの直下に挿入するノードの左値・右値を取得（最左ノード）
     *
     * @param $parentOkrId 親ノードのOKRID
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function getLeftRightOfLeftestInsertionNode($parentOkrId, $timeframeId)
    {
        $sql = <<<SQL
        SELECT (t3_.left_value * 2 + t3_.right_value) / 3 AS tree_left, (t3_.left_value + t3_.right_value * 2) / 3 AS tree_right
        FROM (
            SELECT t0_.tree_left AS left_value, CASE WHEN t1_.tree_left IS NULL THEN t0_.tree_right ELSE MIN(t1_.tree_left) END AS right_value
            FROM t_okr AS t0_ LEFT OUTER JOIN t_okr AS t1_
            ON (t0_.tree_right = (
                SELECT MIN(t2_.tree_right)
                FROM t_okr AS t2_
                WHERE (t1_.tree_left > t2_.tree_left AND t1_.tree_left < t2_.tree_right AND t2_.timeframe_id = :timeframeIdT2)
                    AND (t2_.deleted_at IS NULL)
                )
                AND t1_.timeframe_id = :timeframeIdT1)
            AND (t1_.deleted_at IS NULL)
            WHERE (t0_.okr_id = :parentOkrId AND t0_.timeframe_id = :timeframeIdT0) AND (t0_.deleted_at IS NULL)
        ) AS t3_;
SQL;

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
     * 指定した親ノードの直下に挿入するノードの左値・右値を取得（最右ノード）
     *
     * @param $parentOkrId 親ノードのOKRID
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function getLeftRightOfRightestInsertionNode($parentOkrId, $timeframeId)
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

    /**
     * 指定OKRとそれに紐づくOKRを全て削除
     *
     * @param $treeLeft 入れ子区間モデルの左値
     * @param $treeRight 入れ子区間モデルの右値
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function deleteOkrAndAllAlignmentOkrs($treeLeft, $treeRight, $timeframeId)
    {
        $sql = <<<SQL
        UPDATE t_okr AS t0_
        LEFT OUTER JOIN t_okr_activity AS t1_ ON (t0_.okr_id = t1_.okr_id) AND (t1_.deleted_at IS NULL)
        SET t0_.deleted_at = NOW(), t1_.deleted_at = NOW()
        WHERE (t0_.tree_left >= :treeLeft
                AND t0_.tree_left <= :treeRight
                AND t0_.timeframe_id = :timeframeId)
                AND (t0_.deleted_at IS NULL);
SQL;

        $params['treeLeft'] = $treeLeft;
        $params['treeRight'] = $treeRight;
        $params['timeframeId'] = $timeframeId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }
}
