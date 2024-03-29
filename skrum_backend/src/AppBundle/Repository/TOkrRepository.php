<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;

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
     * @param integer $okrId OKRID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getOkr($okrId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to');
        $qb->select('to')
            ->innerJoin('AppBundle:TTimeframe', 'tt', 'WITH', 'to.timeframe = tt.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'tt.company = mc.companyId')
            ->where('to.okrId = :okrId')
            ->andWhere('to.type <> :type')
            ->andWhere('tt.company = :companyId')
            ->setParameter('okrId', $okrId)
            ->setParameter('type', DBConstant::OKR_TYPE_ROOT_NODE)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * OKR検索
     *
     * @param string $keyword 検索ワード
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchOkr(string $keyword, int $timeframeId = null, int $companyId): array
    {
        $sql = <<<SQL
        SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
        FROM t_okr t0_
        INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
        LEFT OUTER JOIN (
            SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
            FROM m_user m0_
            WHERE (m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
            ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
        LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
        LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
        WHERE (
                t0_.timeframe_id = :timeframeId1
                AND t1_.company_id = :companyId1
                AND t0_.type <> :type1
                AND (
                     t0_.name LIKE :okrName
                     OR m1_.userName LIKE :userName
                    )
              )
              AND (t0_.deleted_at IS NULL)
        UNION
        SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
        FROM t_okr t0_
        INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
        LEFT OUTER JOIN (
            SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
            FROM m_user m0_
            WHERE (m0_.deleted_at IS NULL)
            ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
        LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
        LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
        WHERE (
                t0_.timeframe_id = :timeframeId2
                AND t1_.company_id = :companyId2
                AND t0_.type <> :type2
                AND m2_.group_name LIKE :groupName
              )
              AND (t0_.deleted_at IS NULL)
        UNION
        SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
        FROM t_okr t0_
        INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
        LEFT OUTER JOIN (
            SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
            FROM m_user m0_
            WHERE (m0_.deleted_at IS NULL)
            ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
        LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
        LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
        WHERE (
                t0_.timeframe_id = :timeframeId3
                AND t1_.company_id = :companyId3
                AND t0_.type <> :type3
                AND m3_.company_name LIKE :companyName
              )
              AND (t0_.deleted_at IS NULL);
SQL;

        $params['timeframeId1'] = $timeframeId;
        $params['timeframeId2'] = $timeframeId;
        $params['timeframeId3'] = $timeframeId;
        $params['companyId1'] = $companyId;
        $params['companyId2'] = $companyId;
        $params['companyId3'] = $companyId;
        $params['type1'] = DBConstant::OKR_TYPE_ROOT_NODE;
        $params['type2'] = DBConstant::OKR_TYPE_ROOT_NODE;
        $params['type3'] = DBConstant::OKR_TYPE_ROOT_NODE;
        $params['archivedFlg'] = DBConstant::FLG_FALSE;
        $params['okrName'] = $keyword . '%';
        $params['userName'] = $keyword . '%';
        $params['groupName'] = $keyword . '%';
        $params['companyName'] = $keyword . '%';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * 紐付け先OKR検索（新規目標登録時・紐付け先変更時(対象:Objective)兼用）
     *
     * @param string $keyword 検索ワード
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchParentOkr(string $keyword, int $timeframeId = null, int $companyId, string $ownerType, int $ownerUserId = null, int $ownerGroupId = null, int $ownerCompanyId = null): array
    {
        if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
            $sql = <<<SQL
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId1
                    AND t1_.company_id = :companyId1
                    AND t0_.type <> :type1
                    AND (t0_.owner_user_id <> :ownerUserId1 OR t0_.owner_user_id IS null)
                    AND (
                         t0_.name LIKE :okrName
                         OR m1_.userName LIKE :userName
                        )
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId2
                    AND t1_.company_id = :companyId2
                    AND t0_.type <> :type2
                    AND (t0_.owner_user_id <> :ownerUserId2 OR t0_.owner_user_id IS null)
                    AND m2_.group_name LIKE :groupName
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId3
                    AND t1_.company_id = :companyId3
                    AND t0_.type <> :type3
                    AND (t0_.owner_user_id <> :ownerUserId3 OR t0_.owner_user_id IS null)
                    AND m3_.company_name LIKE :companyName
                  )
                  AND (t0_.deleted_at IS NULL);
SQL;
        } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
            $sql = <<<SQL
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId1
                    AND t1_.company_id = :companyId1
                    AND t0_.type <> :type1
                    AND (t0_.owner_group_id <> :ownerGroupId1 OR t0_.owner_group_id IS null)
                    AND (
                         t0_.name LIKE :okrName
                         OR m1_.userName LIKE :userName
                        )
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId2
                    AND t1_.company_id = :companyId2
                    AND t0_.type <> :type2
                    AND (t0_.owner_group_id <> :ownerGroupId2 OR t0_.owner_group_id IS null)
                    AND m2_.group_name LIKE :groupName
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId3
                    AND t1_.company_id = :companyId3
                    AND t0_.type <> :type3
                    AND (t0_.owner_group_id <> :ownerGroupId3 OR t0_.owner_group_id IS null)
                    AND m3_.company_name LIKE :companyName
                  )
                  AND (t0_.deleted_at IS NULL);
SQL;
        } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY) {
            $sql = <<<SQL
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId1
                    AND t1_.company_id = :companyId1
                    AND t0_.type <> :type1
                    AND (t0_.owner_company_id <> :ownerCompanyId1 OR t0_.owner_company_id IS null)
                    AND (
                         t0_.name LIKE :okrName
                         OR m1_.userName LIKE :userName
                        )
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId2
                    AND t1_.company_id = :companyId2
                    AND t0_.type <> :type2
                    AND (t0_.owner_company_id <> :ownerCompanyId2 OR t0_.owner_company_id IS null)
                    AND m2_.group_name LIKE :groupName
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId3
                    AND t1_.company_id = :companyId3
                    AND t0_.type <> :type3
                    AND (t0_.owner_company_id <> :ownerCompanyId3 OR t0_.owner_company_id IS null)
                    AND m3_.company_name LIKE :companyName
                  )
                  AND (t0_.deleted_at IS NULL);
SQL;
        }

        $params['timeframeId1'] = $timeframeId;
        $params['timeframeId2'] = $timeframeId;
        $params['timeframeId3'] = $timeframeId;
        $params['companyId1'] = $companyId;
        $params['companyId2'] = $companyId;
        $params['companyId3'] = $companyId;
        $params['type1'] = DBConstant::OKR_TYPE_ROOT_NODE;
        $params['type2'] = DBConstant::OKR_TYPE_ROOT_NODE;
        $params['type3'] = DBConstant::OKR_TYPE_ROOT_NODE;
        if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
            $params['ownerUserId1'] = $ownerUserId;
            $params['ownerUserId2'] = $ownerUserId;
            $params['ownerUserId3'] = $ownerUserId;
        }
        if ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
            $params['ownerGroupId1'] = $ownerGroupId;
            $params['ownerGroupId2'] = $ownerGroupId;
            $params['ownerGroupId3'] = $ownerGroupId;
        }
        if ($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY) {
            $params['ownerCompanyId1'] = $ownerCompanyId;
            $params['ownerCompanyId2'] = $ownerCompanyId;
            $params['ownerCompanyId3'] = $ownerCompanyId;
        }
        $params['archivedFlg'] = DBConstant::FLG_FALSE;
        $params['okrName'] = $keyword . '%';
        $params['userName'] = $keyword . '%';
        $params['groupName'] = $keyword . '%';
        $params['companyName'] = $keyword . '%';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * 紐付け先Objective検索（紐付け先変更時(対象:Key Result)用）
     *
     * @param string $keyword 検索ワード
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchParentObjective(string $keyword, int $timeframeId = null, int $companyId, string $ownerType, int $ownerUserId = null, int $ownerGroupId = null, int $ownerCompanyId = null): array
    {
        if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
            $sql = <<<SQL
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId1
                    AND t1_.company_id = :companyId1
                    AND t0_.type = :type1
                    AND t0_.owner_user_id = :ownerUserId1
                    AND (
                         t0_.name LIKE :okrName
                         OR m1_.userName LIKE :userName
                        )
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId2
                    AND t1_.company_id = :companyId2
                    AND t0_.type = :type2
                    AND t0_.owner_user_id = :ownerUserId2
                    AND m2_.group_name LIKE :groupName
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId3
                    AND t1_.company_id = :companyId3
                    AND t0_.type = :type3
                    AND t0_.owner_user_id = :ownerUserId3
                    AND m3_.company_name LIKE :companyName
                  )
                  AND (t0_.deleted_at IS NULL);
SQL;
        } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
            $sql = <<<SQL
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId1
                    AND t1_.company_id = :companyId1
                    AND t0_.type = :type1
                    AND t0_.owner_group_id = :ownerGroupId1
                    AND (
                         t0_.name LIKE :okrName
                         OR m1_.userName LIKE :userName
                        )
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId2
                    AND t1_.company_id = :companyId2
                    AND t0_.type = :type2
                    AND t0_.owner_group_id = :ownerGroupId2
                    AND m2_.group_name LIKE :groupName
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId3
                    AND t1_.company_id = :companyId3
                    AND t0_.type = :type3
                    AND t0_.owner_group_id = :ownerGroupId3
                    AND m3_.company_name LIKE :companyName
                  )
                  AND (t0_.deleted_at IS NULL);
SQL;
        } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY) {
            $sql = <<<SQL
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId1
                    AND t1_.company_id = :companyId1
                    AND t0_.type = :type1
                    AND t0_.owner_company_id = :ownerCompanyId1
                    AND (
                         t0_.name LIKE :okrName
                         OR m1_.userName LIKE :userName
                        )
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId2
                    AND t1_.company_id = :companyId2
                    AND t0_.type = :type2
                    AND t0_.owner_company_id = :ownerCompanyId2
                    AND m2_.group_name LIKE :groupName
                  )
                  AND (t0_.deleted_at IS NULL)
            UNION
            SELECT t0_.okr_id, t0_.name, t0_.owner_type, m1_.user_id, m1_.last_name, m1_.first_name, m1_.image_version AS userImageVersion, m2_.group_id, m2_.group_name, m2_.image_version AS groupImageVersion, m3_.company_id, m3_.company_name, m3_.image_version AS companyImageVersion
            FROM t_okr t0_
            INNER JOIN t_timeframe t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
            LEFT OUTER JOIN (
                SELECT m0_.user_id, m0_.last_name, m0_.first_name, m0_.image_version, CONCAT(m0_.last_name, m0_.first_name) AS userName
                FROM m_user m0_
                WHERE (m0_.deleted_at IS NULL)
                ) AS m1_ ON (t0_.owner_user_id = m1_.user_id)
            LEFT OUTER JOIN m_group m2_ ON (t0_.owner_group_id = m2_.group_id) AND (m2_.deleted_at IS NULL)
            LEFT OUTER JOIN m_company m3_ ON (t0_.owner_company_id = m3_.company_id) AND (m3_.deleted_at IS NULL)
            WHERE (
                    t0_.timeframe_id = :timeframeId3
                    AND t1_.company_id = :companyId3
                    AND t0_.type = :type3
                    AND t0_.owner_company_id = :ownerCompanyId3
                    AND m3_.company_name LIKE :companyName
                  )
                  AND (t0_.deleted_at IS NULL);
SQL;
            }

            $params['timeframeId1'] = $timeframeId;
            $params['timeframeId2'] = $timeframeId;
            $params['timeframeId3'] = $timeframeId;
            $params['companyId1'] = $companyId;
            $params['companyId2'] = $companyId;
            $params['companyId3'] = $companyId;
            $params['type1'] = DBConstant::OKR_TYPE_OBJECTIVE;
            $params['type2'] = DBConstant::OKR_TYPE_OBJECTIVE;
            $params['type3'] = DBConstant::OKR_TYPE_OBJECTIVE;
            if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
                $params['ownerUserId1'] = $ownerUserId;
                $params['ownerUserId2'] = $ownerUserId;
                $params['ownerUserId3'] = $ownerUserId;
            }
            if ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $params['ownerGroupId1'] = $ownerGroupId;
                $params['ownerGroupId2'] = $ownerGroupId;
                $params['ownerGroupId3'] = $ownerGroupId;
            }
            if ($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY) {
                $params['ownerCompanyId1'] = $ownerCompanyId;
                $params['ownerCompanyId2'] = $ownerCompanyId;
                $params['ownerCompanyId3'] = $ownerCompanyId;
            }
            $params['archivedFlg'] = DBConstant::FLG_FALSE;
            $params['okrName'] = $keyword . '%';
            $params['userName'] = $keyword . '%';
            $params['groupName'] = $keyword . '%';
            $params['companyName'] = $keyword . '%';

            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
    }

    /**
     * 3世代OKRを取得
     *
     * @param integer $okrId OKRID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getThreeGensOkrs(int $okrId, int $timeframeId, int $companyId): array
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
     * 親子OKRを取得（子OKRを指定）
     *
     * @param integer $okrId OKRID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getParentOkr(int $okrId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as childOkr', 'to2 as parentOkr')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.parentOkr = to2.okrId AND to2.type <> :type2')
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
     * 親子OKRを取得（親OKRを指定）
     *
     * @param integer $okrId OKRID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @param string $okrTypeOfKeyResults キーリザルトのOKR種別（OKR種別＝'1'or'2'を指定）
     * @return array
     */
    public function getObjectiveAndKeyResults(int $okrId, int $timeframeId, int $companyId, string $okrTypeOfKeyResults = null): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1', 'to2')
            ->innerJoin('AppBundle:TTimeframe', 'tt', 'WITH', 'to1.timeframe = tt.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'tt.company = mc.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->where('to1.okrId = :okrId')
            ->andWhere('to1.timeframe = :timeframeId')
            ->andWhere('tt.company = :companyId')
            ->setParameter('okrId', $okrId)
            ->setParameter('timeframeId', $timeframeId)
            ->setParameter('companyId', $companyId);

        if ($okrTypeOfKeyResults !== null) {
            $qb->andWhere('to2.type = :okrType OR to2.type IS NULL')
                ->setParameter('okrType', $okrTypeOfKeyResults);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * 複数の親OKRに対する全ての子OKRを取得
     *
     * @param array $parentOkrIdArray 親OKRID配列
     * @return array
     */
    public function getAllChildrenOkrsOfMultipleParentOkrs(array $parentOkrIdArray): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to2')
        ->innerJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
        ->where($qb->expr()->in('to1.okrId', $parentOkrIdArray));

        return $qb->getQuery()->getResult();
    }

    /**
     * 親子OKRを取得（親OKRを指定）（達成率再計算ロジック用）
     *
     * @param integer $okrId OKRID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getChildrenOkrsForRecalc(int $okrId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as parentOkr', 'to2 as childOkr')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->where('to1.okrId = :okrId1')
            ->andWhere('to1.timeframe = :timeframeId1')
            ->andWhere('tt1.company = :companyId1')
            ->setParameter('okrId1', $okrId)
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('companyId1', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 子OKRの加重平均比率の合計値と持分比率ロックフラグが立っている数を取得（達成率再計算ロジック用）
     *
     * @param integer $okrId OKRID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getRecalcItems(int $okrId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('SUM(to2.weightedAverageRatio) as summedWeightedAverageRatio', 'SUM(to2.ratioLockedFlg) as lockedRatioCount')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->where('to1.okrId = :okrId1')
            ->andWhere('to1.timeframe = :timeframeId1')
            ->andWhere('tt1.company = :companyId1')
            ->andWhere('to2.ratioLockedFlg = :ratioLockedFlg')
            ->setParameter('okrId1', $okrId)
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('companyId1', $companyId)
            ->setParameter('ratioLockedFlg', DBConstant::FLG_TRUE);

        return $qb->getQuery()->getResult();
    }

    /**
     * ユーザの目標とキーリザルトの一覧を取得
     *
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getUserObjectivesAndKeyResults(int $userId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as objective', 'to2 as keyResult', 'mc1.companyName')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->leftJoin('AppBundle:TOkr', 'to3', 'WITH', 'to1.parentOkr = to3.okrId')
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
            ->orderBy('to3.ownerType', 'DESC')
            ->addOrderBy('to3.ownerGroup', 'ASC')
            ->addOrderBy('to3.ownerUser', 'ASC')
            ->addOrderBy('to3.okrId', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * グループの目標とキーリザルトの一覧を取得
     *
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getGroupObjectivesAndKeyResults(int $groupId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as objective', 'to2 as keyResult', 'mc1.companyName')
            ->innerJoin('AppBundle:TTimeframe', 'tt1', 'WITH', 'to1.timeframe = tt1.timeframeId')
            ->innerJoin('AppBundle:MCompany', 'mc1', 'WITH', 'tt1.company = mc1.companyId')
            ->leftJoin('AppBundle:TOkr', 'to2', 'WITH', 'to1.okrId = to2.parentOkr')
            ->leftJoin('AppBundle:TOkr', 'to3', 'WITH', 'to1.parentOkr = to3.okrId')
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
            ->orderBy('to3.ownerType', 'DESC')
            ->addOrderBy('to3.ownerGroup', 'ASC')
            ->addOrderBy('to3.ownerUser', 'ASC')
            ->addOrderBy('to1.okrId', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 会社の目標とキーリザルトの一覧を取得
     *
     * @param integer $companyId 会社ID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getCompanyObjectivesAndKeyResults(int $companyId, int $timeframeId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to1 as objective', 'to2 as keyResult', 'mc1.companyName', 'mc1.imageVersion')
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
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getUserObjectives(int $userId, int $timeframeId, int $companyId): array
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
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getGroupObjectives(int $groupId, int $timeframeId, int $companyId): array
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
     * @param integer $companyId 会社ID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getCompanyObjectives(int $companyId, int $timeframeId): array
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
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getUserAlignmentsInfoForUser(int $userId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('IDENTITY(to2.ownerUser) AS userId', 'mu1.lastName', 'mu1.firstName', 'mu1.imageVersion', 'COUNT(to2.ownerUser) AS numberOfOkrs')
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
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getGroupAlignmentsInfoForUser(int $userId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('IDENTITY(to2.ownerGroup) AS groupId', 'mg1.groupName', 'mg1.imageVersion', 'COUNT(to2.ownerGroup) AS numberOfOkrs')
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
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getCompanyAlignmentsInfoForUser(int $userId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to2.ownerCompanyId AS companyId', 'mc2.companyName', 'mc2.imageVersion', 'COUNT(to2.ownerCompanyId) AS numberOfOkrs')
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
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getUserAlignmentsInfoForGroup(int $groupId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('IDENTITY(to2.ownerUser) AS userId', 'mu1.lastName', 'mu1.firstName', 'mu1.imageVersion', 'COUNT(to2.ownerUser) AS numberOfOkrs')
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
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getGroupAlignmentsInfoForGroup(int $groupId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('IDENTITY(to2.ownerGroup) AS groupId', 'mg1.groupName', 'mg1.imageVersion', 'COUNT(to2.ownerGroup) AS numberOfOkrs')
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
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getCompanyAlignmentsInfoForGroup(int $groupId, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to1');
        $qb->select('to2.ownerCompanyId AS companyId', 'mc2.companyName', 'mc2.imageVersion', 'COUNT(to2.ownerCompanyId) AS numberOfOkrs')
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
     * 指定OKRとそれに紐づくOKRを全て取得
     *
     * @param $treeLeft 入れ子区間モデルの左値
     * @param $treeRight 入れ子区間モデルの右値
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getOkrAndAllAlignmentOkrs($treeLeft, $treeRight, int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('to');
        $qb->select('to')
            ->innerJoin('AppBundle:TTimeframe', 'tt', 'WITH', 'to.timeframe = tt.timeframeId')
            ->where('tt.company = :companyId')
            ->andWhere('to.timeframe = :timeframeId')
            ->andWhere('to.treeLeft >= :treeLeft')
            ->andWhere('to.treeLeft <= :treeRight')
            ->setParameter('companyId', $companyId)
            ->setParameter('timeframeId', $timeframeId)
            ->setParameter('treeLeft', $treeLeft)
            ->setParameter('treeRight', $treeRight)
            ->orderBy('to.treeLeft', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定した親ノードの直下に挿入するノードの左値・右値を取得（最左ノード）
     *
     * @param integer $parentOkrId 親ノードのOKRID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getLeftRightOfLeftestInsertionNode(int $parentOkrId, int $timeframeId)
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
     * @param integer $parentOkrId 親ノードのOKRID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getLeftRightOfRightestInsertionNode(int $parentOkrId, int $timeframeId)
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
     * @param integer $timeframeId タイムフレームID
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getRootNode(int $timeframeId)
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
     * 指定OKRの全てのサブ目標の持分比率ロックフラグをリセット
     *
     * @param integer $okrId OKRID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return void
     */
    public function resetRatioLockedFlg(int $okrId, int $timeframeId, int $companyId)
    {
        $sql = <<<SQL
        UPDATE t_okr AS t0_
        INNER JOIN t_timeframe AS t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
        SET t0_.ratio_locked_flg = :ratioLockedFlg, t0_.updated_at = NOW()
        WHERE (t1_.company_id = :companyId
                AND t0_.timeframe_id = :timeframeId
                AND t0_.parent_okr_id = :parentOkrId)
                AND (t0_.deleted_at IS NULL);
SQL;

        $params['ratioLockedFlg'] = DBConstant::FLG_FALSE;
        $params['companyId'] = $companyId;
        $params['timeframeId'] = $timeframeId;
        $params['parentOkrId'] = $okrId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * 指定OKRとそれに紐づく全てのOKRの左値・右値にnullをセット
     *
     * @param $treeLeft 入れ子区間モデルの左値
     * @param $treeRight 入れ子区間モデルの右値
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return void
     */
    public function resetAllLeftRightValues($treeLeft, $treeRight, int $timeframeId, int $companyId)
    {
        $sql = <<<SQL
        UPDATE t_okr AS t0_
        INNER JOIN t_timeframe AS t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
        SET t0_.tree_left = NULL, t0_.tree_right = NULL, t0_.updated_at = NOW()
        WHERE (t1_.company_id = :companyId
                AND t0_.timeframe_id = :timeframeId
                AND t0_.tree_left >= :treeLeft
                AND t0_.tree_left <= :treeRight)
                AND (t0_.deleted_at IS NULL);
SQL;

        $params['companyId'] = $companyId;
        $params['timeframeId'] = $timeframeId;
        $params['treeLeft'] = $treeLeft;
        $params['treeRight'] = $treeRight;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * 指定OKRとそれに紐づくOKR、OKRアクティビティ、投稿を全て削除
     *
     * @param $treeLeft 入れ子区間モデルの左値
     * @param $treeRight 入れ子区間モデルの右値
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return void
     */
    public function deleteOkrAndAllAlignmentOkrs($treeLeft, $treeRight, int $timeframeId, int $companyId)
    {
        $sql = <<<SQL
        UPDATE t_okr AS t0_
        INNER JOIN t_timeframe AS t1_ ON (t0_.timeframe_id = t1_.timeframe_id) AND (t1_.deleted_at IS NULL)
        LEFT OUTER JOIN t_okr_activity AS t2_ ON (t0_.okr_id = t2_.okr_id) AND (t2_.deleted_at IS NULL)
        LEFT OUTER JOIN t_post AS t3_ ON (t2_.id = t3_.okr_activity_id) AND (t3_.deleted_at IS NULL)
        LEFT OUTER JOIN t_post_to AS t4_ ON (t3_.id = t4_.post_id) AND (t4_.deleted_at IS NULL)
        LEFT OUTER JOIN t_like AS t5_ ON (t3_.id = t5_.post_id) AND (t5_.deleted_at IS NULL)
        SET t0_.deleted_at = NOW(), t2_.deleted_at = NOW(), t3_.deleted_at = NOW(), t4_.deleted_at = NOW(), t5_.deleted_at = NOW()
        WHERE (t1_.company_id = :companyId
                AND t0_.timeframe_id = :timeframeId
                AND t0_.tree_left >= :treeLeft
                AND t0_.tree_left <= :treeRight)
                AND (t0_.deleted_at IS NULL);
SQL;

        $params['companyId'] = $companyId;
        $params['timeframeId'] = $timeframeId;
        $params['treeLeft'] = $treeLeft;
        $params['treeRight'] = $treeRight;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }
}
