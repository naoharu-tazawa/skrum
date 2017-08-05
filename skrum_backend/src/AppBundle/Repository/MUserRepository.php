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
     * 追加ユーザ検索
     *
     * @param integer $groupId グループID
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchAdditionalUser(int $groupId, string $keyword, int $companyId): array
    {
        $sql = <<<SQL
        SELECT m1_.userId, m1_.lastName, m1_.firstName
        FROM (
            SELECT m0_.user_id AS userId, m0_.last_name AS lastName, m0_.first_name AS firstName, CONCAT(m0_.last_name, m0_.first_name) AS name
            FROM m_user m0_
            WHERE (m0_.company_id = :companyId AND m0_.archived_flg = :archivedFlg) AND (m0_.deleted_at IS NULL)
            ) AS m1_
        LEFT OUTER JOIN (
            SELECT t0_.user_id, t0_.deleted_at
            FROM t_group_member t0_
            WHERE t0_.group_id = :groupId
            ) t1_ ON (m1_.userId = t1_.user_id) AND (t1_.deleted_at IS NULL)
        WHERE t1_.user_id is NULL AND m1_.name LIKE :userName;
SQL;

        $params['companyId'] = $companyId;
        $params['archivedFlg'] = DBConstant::FLG_FALSE;
        $params['groupId'] = $groupId;
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

    /**
     * 全てのユーザをそれに紐づくOKR達成率と共に全レコードを取得
     *
     * @return array
     */
    public function getAllUsersWithAchievementRate(): array
    {
        $qb = $this->createQueryBuilder('mu');
        $qb->select('mu.userId', 'IDENTITY(to.timeframe) AS timeframeId', 'to.achievementRate')
            ->innerJoin('AppBundle:TOkr', 'to', 'WITH', 'mu.userId = to.ownerUser')
            ->innerJoin('AppBundle:TTimeframe', 'tt', 'WITH', 'to.timeframe = tt.timeframeId AND tt.defaultFlg = :defaultFlg')
            ->where('mu.archivedFlg = :archivedFlg')
            ->andWhere('to.ownerType = :ownerType')
            ->andWhere('to.type = :type')
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE)
            ->setParameter('defaultFlg', DBConstant::FLG_TRUE)
            ->setParameter('ownerType', DBConstant::OKR_OWNER_TYPE_USER)
            ->setParameter('type', DBConstant::OKR_TYPE_OBJECTIVE)
            ->orderBy('mu.userId', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * メンバー進捗状況レポートメール対象者を取得（バッチ）
     *
     * @return array
     */
    public function getUsersForMemberAchievementReportEmail(): array
    {
        $qb = $this->createQueryBuilder('mu');
        $qb->select('mu')
            ->innerJoin('AppBundle:MRoleAssignment', 'mra', 'WITH', 'mu.roleAssignment = mra.roleAssignmentId')
            ->innerJoin('AppBundle:TEmailSettings', 'tes', 'WITH', 'mu.userId = tes.userId')
            ->where('mu.archivedFlg = :archivedFlg')
            ->andWhere('mra.roleLevel >= :roleLevel')
            ->andWhere('tes.reportMemberAchievement = :reportMemberAchievement')
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE)
            ->setParameter('roleLevel', DBConstant::ROLE_LEVEL_ADMIN)
            ->setParameter('reportMemberAchievement', DBConstant::EMAIL_REPORT_MEMBER_ACHIEVEMENT);

        return $qb->getQuery()->getResult();
    }

    /**
     * フィードバック対象者報告メール対象者を取得（バッチ）
     *
     * @return array
     */
    public function getUsersForFeedbackTargetReportEmail(): array
    {
        $qb = $this->createQueryBuilder('mu');
        $qb->select('mu')
            ->innerJoin('AppBundle:MRoleAssignment', 'mra', 'WITH', 'mu.roleAssignment = mra.roleAssignmentId')
            ->innerJoin('AppBundle:TEmailSettings', 'tes', 'WITH', 'mu.userId = tes.userId')
            ->where('mu.archivedFlg = :archivedFlg')
            ->andWhere('mra.roleLevel >= :roleLevel')
            ->andWhere('tes.reportFeedbackTarget = :reportFeedbackTarget')
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE)
            ->setParameter('roleLevel', DBConstant::ROLE_LEVEL_ADMIN)
            ->setParameter('reportFeedbackTarget', DBConstant::EMAIL_REPORT_FEEDBACK_TARGET);

        return $qb->getQuery()->getResult();
    }

    /**
     * 進捗登録リマインダーメール対象者を取得（バッチ）
     *
     * @return array
     */
    public function getUsersForAchievementRegistrationReminderEmail($mailSendingConditionDatetimeString): array
    {
        $sql = <<<SQL
        SELECT m0_.last_name, m0_.first_name, m0_.email_address
        FROM m_user m0_
        INNER JOIN t_email_settings t0_
        ON (m0_.user_id = t0_.user_id) AND (t0_.deleted_at IS NULL)
        LEFT OUTER JOIN t_okr_activity t1_
        ON t1_.id = (
            SELECT t2_.id
            FROM t_okr_activity t2_
            INNER JOIN t_okr t3_
            ON (t2_.okr_id = t3_.okr_id) AND (t3_.deleted_at IS NULL)
            WHERE (t3_.owner_type = :ownerType AND m0_.user_id = t3_.owner_user_id AND t2_.type = :okrOperationType) AND (t2_.deleted_at IS NULL)
            ORDER BY t2_.activity_datetime DESC LIMIT 1
            ) AND (t1_.deleted_at IS NULL)
        WHERE (m0_.archived_flg = :archivedFlg AND t0_.okr_reminder = :okrReminder
                AND t1_.activity_datetime < :activityDatetime) AND (m0_.deleted_at IS NULL);
SQL;

        $params['ownerType'] = DBConstant::OKR_OWNER_TYPE_USER;
        $params['okrOperationType'] = DBConstant::OKR_OPERATION_TYPE_ACHIEVEMENT;
        $params['archivedFlg'] = DBConstant::FLG_FALSE;
        $params['okrReminder'] = DBConstant::EMAIL_OKR_REMINDER;
        $params['activityDatetime'] = $mailSendingConditionDatetimeString;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
