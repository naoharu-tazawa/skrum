<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;
use \PDO;

/**
 * MUserリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class MUserRepository extends BaseRepository
{
    /**
     * 指定ユーザIDのレコードを取得
     *
     * @param integer $userId ユーザID
     * @param integer $companyId 会社ID
     * @param boolean $includingArchivedUsers アーカイブ済ユーザ取得フラグ
     * @return array
     */
    public function getUser(int $userId, int $companyId, bool $includingArchivedUsers = false): array
    {
        $qb = $this->createQueryBuilder('mu');
        $qb->select('mu')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'mu.company = mc.companyId')
            ->where('mu.userId = :userId')
            ->andWhere('mu.company = :companyId')
            ->setParameter('userId', $userId)
            ->setParameter('companyId', $companyId);

        if (!$includingArchivedUsers) {
            $qb->andWhere('mu.archivedFlg = :archivedFlg')
                ->setParameter('archivedFlg', DBConstant::FLG_FALSE);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * ユーザ検索
     *
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchUser(string $keyword, int $companyId): array
    {
        $sql = <<<SQL
        SELECT m1_.userId, m1_.lastName, m1_.firstName
        FROM (
            SELECT m0_.user_id AS userId, m0_.last_name AS lastName, m0_.first_name AS firstName, CONCAT(m0_.last_name, m0_.first_name) AS name
            FROM m_user m0_
            WHERE (m0_.company_id = :companyId AND m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
            ) AS m1_
        WHERE m1_.name LIKE :keyword;
SQL;

        $params['companyId'] = $companyId;
        $params['archivedFlg'] = DBConstant::FLG_FALSE;
        $params['keyword'] = $keyword . '%';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * 参加ユーザ検索
     *
     * @param integer $groupId グループID
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchJoiningUser(int $groupId, string $keyword, int $companyId): array
    {
        $sql = <<<SQL
        SELECT m1_.userId, m1_.lastName, m1_.firstName
        FROM (
            SELECT m0_.user_id AS userId, m0_.last_name AS lastName, m0_.first_name AS firstName, CONCAT(m0_.last_name, m0_.first_name) AS name
            FROM m_user m0_
            WHERE (m0_.company_id = :companyId AND m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
            ) AS m1_
        LEFT OUTER JOIN (
            SELECT DISTINCT t0_.user_id, t0_.deleted_at
            FROM t_group_member t0_
            WHERE t0_.group_id = 13
            ) t1_ ON (m1_.userId = t1_.user_id) AND (t1_.deleted_at IS NULL)
        WHERE t1_.user_id is NULL AND m1_.name LIKE :userName;
SQL;

        $params['companyId'] = $companyId;
        $params['archivedFlg'] = DBConstant::FLG_FALSE;
        $params['userName'] = $keyword . '%';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * ユーザ検索（ページング）検索結果数を取得
     *
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return integer
     */
    public function getPagesearchCount(string $keyword, int $companyId): int
    {
        $sql = <<<SQL
        SELECT m1_.user_id
        FROM (
            SELECT m0_.user_id, m0_.last_name, m0_.first_name, CONCAT(m0_.last_name, m0_.first_name) AS name
            FROM m_user m0_
            WHERE (m0_.company_id = :companyId AND m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
            ) AS m1_
        WHERE m1_.name LIKE :keyword;
SQL;

        $params['companyId'] = $companyId;
        $params['archivedFlg'] = DBConstant::FLG_FALSE;
        $params['keyword'] = $keyword . '%';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    /**
     * ユーザ検索（ページング）
     *
     * @param string $keyword 検索ワード
     * @param integer $page 要求ページ
     * @param integer $perPage １ページあたり表示数
     * @param integer $companyId 会社ID
     * @return array
     */
    public function pagesearchUser(string $keyword, int $page, int $perPage, int $companyId): array
    {
        $sql = <<<SQL
        SELECT m1_.user_id, m1_.last_name, m1_.first_name, m1_.role_assignment_id, m2_.role_level, m1_.last_access_datetime
        FROM (
            SELECT m0_.user_id, m0_.last_name, m0_.first_name, CONCAT(m0_.last_name, m0_.first_name) AS name, m0_.role_assignment_id, m0_.last_access_datetime
            FROM m_user m0_
            WHERE (m0_.company_id = :companyId AND m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
            ) AS m1_
        INNER JOIN m_role_assignment m2_ ON (m1_.role_assignment_id = m2_.role_assignment_id) AND (m2_.deleted_at IS NULL)
        WHERE m1_.name LIKE :keyword
        LIMIT :offset, :limit;
SQL;

        $likeKeyword = $keyword . '%';
        $offset = ($page - 1) * $perPage;
        $archivedFlg = DBConstant::FLG_FALSE;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindParam(':companyId', $companyId, PDO::PARAM_INT);
        $stmt->bindParam(':archivedFlg', $archivedFlg, PDO::PARAM_INT);
        $stmt->bindParam(':keyword', $likeKeyword, PDO::PARAM_STR);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * 指定会社IDのユーザ数を取得
     *
     * @param integer $companyId 会社ID
     * @return mixed
     * @throws NonUniqueResultException If the query result is not unique.
     * @throws NoResultException        If the query returned no result.
     */
    public function getUserCount(int $companyId)
    {
        $qb = $this->createQueryBuilder('mu');
        $qb->select('COUNT(mu.userId)')
            ->where('mu.company = :companyId')
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * スーパー管理者ユーザ数を取得
     *
     * @param integer $companyId 会社ID
     * @return mixed
     * @throws NonUniqueResultException If the query result is not unique.
     * @throws NoResultException        If the query returned no result.
     */
    public function getSuperAdminUserCount(int $companyId)
    {
        $qb = $this->createQueryBuilder('mu');
        $qb->select('COUNT(mu.userId)')
            ->innerJoin('AppBundle:MRoleAssignment', 'mra', 'WITH', 'mu.roleAssignment = mra.roleAssignmentId')
            ->where('mu.company = :companyId')
            ->andWhere('mra.roleLevel = :roleLevel')
            ->andWhere('mu.archivedFlg = :archivedFlg')
            ->setParameter('companyId', $companyId)
            ->setParameter('roleLevel', DBConstant::ROLE_LEVEL_SUPERADMIN)
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
