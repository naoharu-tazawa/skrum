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
     * 3世代OKRを取得
     *
     * @param $okrId OKRID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getThreeGensOkrs($okrId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as selectedOkr', 'to2 as parentOkr', 'to3 as childrenOkr')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.parentOkr = to2.okrId AND to2.type <> :type2')
            ->leftJoin('AppBundle:TOkr', 'to3', 'WITH', 'to1.okrId = to3.parentOkr')
            ->where('to1.okrId = :okrId1')
            ->andWhere('to1.timeframe = :timeframeId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('okrId1', $okrId)
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('companyId1', $companyId)
            ->setParameter('type2', DBConstant::OKR_TYPE_ROOT_NODE);

        return $qb->getQuery()->getResult();
    }

    /**
     * ユーザの目標とキーリザルトの一覧を取得
     *
     * @param $userId ユーザID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getUserObjectivesAndKeyResults($userId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as objective', 'to2 as keyResult', 'mc1.companyName')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerUser = :ownerUserId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_USER)
            ->setParameter('ownerUserId1', $userId)
            ->setParameter('companyId1', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * グループの目標とキーリザルトの一覧を取得
     *
     * @param $groupId グループID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getGroupObjectivesAndKeyResults($groupId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as objective', 'to2 as keyResult', 'mc1.companyName')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerGroup = :ownerGroupId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_GROUP)
            ->setParameter('ownerGroupId1', $groupId)
            ->setParameter('companyId1', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 会社の目標とキーリザルトの一覧を取得
     *
     * @param $companyId 会社ID
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function getCompanyObjectivesAndKeyResults($companyId, $timeframeId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as objective', 'to2 as keyResult', 'mc1.companyName')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerCompanyId = :ownerCompanyId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_COMPANY)
            ->setParameter('ownerCompanyId1', $companyId)
            ->setParameter('companyId1', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * ユーザの目標一覧を取得
     *
     * @param $userId ユーザID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getUserObjectives($userId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerUser = :ownerUserId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_USER)
            ->setParameter('ownerUserId1', $userId)
            ->setParameter('companyId1', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * グループの目標一覧を取得
     *
     * @param $groupId グループID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getGroupObjectives($groupId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerGroup = :ownerGroupId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_GROUP)
            ->setParameter('ownerGroupId1', $groupId)
            ->setParameter('companyId1', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 会社の目標一覧を取得
     *
     * @param $companyId 会社ID
     * @param $timeframeId タイムフレームID
     * @return array
     */
    public function getCompanyObjectives($companyId, $timeframeId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerCompanyId = :ownerCompanyId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_COMPANY)
            ->setParameter('ownerCompanyId1', $companyId)
            ->setParameter('companyId1', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * ユーザの目標紐付け先（ユーザ）情報を取得
     *
     * @param $userId ユーザID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getUserAlignmentsInfoForUser($userId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('IDENTITY(to2.ownerUser) AS userId', 'mu1.lastName', 'mu1.firstName', 'COUNT(to2.ownerUser) AS numberOfOkrs')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.parentOkr = to2.okrId')
            ->innerJoin('AppBundle:MUser', 'mu1', 'WITH', 'to2.ownerUser = mu1.userId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerUser = :ownerUserId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_USER)
            ->setParameter('ownerUserId1', $userId)
            ->setParameter('companyId1', $companyId)
            ->groupBy('to2.ownerUser');

        return $qb->getQuery()->getResult();
    }

    /**
     * ユーザの目標紐付け先（グループ）情報を取得
     *
     * @param $userId ユーザID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getGroupAlignmentsInfoForUser($userId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('IDENTITY(to2.ownerGroup) AS groupId', 'mg1.groupName', 'COUNT(to2.ownerGroup) AS numberOfOkrs')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.parentOkr = to2.okrId')
            ->innerJoin('AppBundle:MGroup', 'mg1', 'WITH', 'to2.ownerGroup = mg1.groupId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerUser = :ownerUserId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_USER)
            ->setParameter('ownerUserId1', $userId)
            ->setParameter('companyId1', $companyId)
            ->groupBy('to2.ownerGroup');

        return $qb->getQuery()->getResult();
    }

    /**
     * ユーザの目標紐付け先（会社）情報を取得
     *
     * @param $userId ユーザID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getCompanyAlignmentsInfoForUser($userId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to2.ownerCompanyId AS companyId', 'mc2.companyName', 'COUNT(to2.ownerCompanyId) AS numberOfOkrs')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.parentOkr = to2.okrId')
            ->innerJoin('AppBundle:MCompany', 'mc2', 'WITH', 'to2.ownerCompanyId = mc2.companyId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerUser = :ownerUserId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_USER)
            ->setParameter('ownerUserId1', $userId)
            ->setParameter('companyId1', $companyId)
            ->groupBy('to2.ownerCompanyId');

        return $qb->getQuery()->getResult();
    }

    /**
     * グループの目標紐付け先（ユーザ）情報を取得
     *
     * @param $groupId グループID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getUserAlignmentsInfoForGroup($groupId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('IDENTITY(to2.ownerUser) AS userId', 'mu1.lastName', 'mu1.firstName', 'COUNT(to2.ownerUser) AS numberOfOkrs')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.parentOkr = to2.okrId')
            ->innerJoin('AppBundle:MUser', 'mu1', 'WITH', 'to2.ownerUser = mu1.userId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerGroup = :ownerGroupId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_GROUP)
            ->setParameter('ownerGroupId1', $groupId)
            ->setParameter('companyId1', $companyId)
            ->groupBy('to2.ownerUser');

        return $qb->getQuery()->getResult();
    }

    /**
     * グループの目標紐付け先（グループ）情報を取得
     *
     * @param $groupId グループID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getGroupAlignmentsInfoForGroup($groupId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('IDENTITY(to2.ownerGroup) AS groupId', 'mg1.groupName', 'COUNT(to2.ownerGroup) AS numberOfOkrs')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.parentOkr = to2.okrId')
            ->innerJoin('AppBundle:MGroup', 'mg1', 'WITH', 'to2.ownerGroup = mg1.groupId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerGroup = :ownerGroupId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_GROUP)
            ->setParameter('ownerGroupId1', $groupId)
            ->setParameter('companyId1', $companyId)
            ->groupBy('to2.ownerGroup');

        return $qb->getQuery()->getResult();
    }

    /**
     * グループの目標紐付け先（会社）情報を取得
     *
     * @param $groupId グループID
     * @param $timeframeId タイムフレームID
     * @param $companyId 会社ID
     * @return array
     */
    public function getCompanyAlignmentsInfoForGroup($groupId, $timeframeId, $companyId)
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to2.ownerCompanyId AS companyId', 'mc2.companyName', 'COUNT(to2.ownerCompanyId) AS numberOfOkrs')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.parentOkr = to2.okrId')
            ->innerJoin('AppBundle:MCompany', 'mc2', 'WITH', 'to2.ownerCompanyId = mc2.companyId')
            ->where('to1.timeframe = :timeframeId1')
            ->andWhere('to1.type = :type1')
            ->andWhere('to1.ownerType = :ownerType1')
            ->andWhere('to1.ownerGroup = :ownerGroupId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('type1', DBConstant::OKR_TYPE_OBJECTIVE)
            ->setParameter('ownerType1', DBConstant::OKR_OWNER_TYPE_GROUP)
            ->setParameter('ownerGroupId1', $groupId)
            ->setParameter('companyId1', $companyId)
            ->groupBy('to2.ownerCompanyId');

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
