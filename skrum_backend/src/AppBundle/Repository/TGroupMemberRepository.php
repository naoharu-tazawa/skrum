<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;

/**
 * TGroupMemberリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TGroupMemberRepository extends BaseRepository
{
    /**
     * 指定グループID、ユーザIDのレコードを取得
     *
     * @param integer $groupId グループID
     * @param integer $userId ユーザID
     * @return array
     */
    public function getGroupMember(int $groupId, int $userId): array
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('tgm')
            ->where('tgm.group = :groupId')
            ->andWhere('tgm.user = :userId')
            ->setParameter('groupId', $groupId)
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDの全レコードを取得
     *
     * @param integer $groupId グループID
     * @return array
     */
    public function getAllGroupMembers(int $groupId): array
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('mu')
            ->innerJoin('AppBundle:MUser', 'mu', 'WITH', 'tgm.user = mu.userId')
            ->where('tgm.group = :groupId')
            ->setParameter('groupId', $groupId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDに紐付くユーザをそれに紐づくOKR達成率と共に全レコードを取得
     *
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getAllGroupMembersWithAchievementRate(int $groupId, int $timeframeId): array
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('mu.userId', 'mu.lastName', 'mu.firstName', 'mu.position', 'to.achievementRate')
            ->innerJoin('AppBundle:MUser', 'mu', 'WITH', 'tgm.user = mu.userId')
            ->leftJoin('AppBundle:TOkr', 'to', 'WITH', '(mu.userId = to.ownerUser) AND (to.timeframe = :timeframeId OR to.timeframe is NULL)')
            ->where('tgm.group = :groupId')
            ->andWhere('to.ownerType = :ownerType OR to.ownerType is NULL')
            ->andWhere('to.type = :type OR to.type is NULL')
            ->setParameter('groupId', $groupId)
            ->setParameter('timeframeId', $timeframeId)
            ->setParameter('ownerType', DBConstant::OKR_OWNER_TYPE_USER)
            ->setParameter('type', DBConstant::OKR_TYPE_OBJECTIVE)
            ->orderBy('mu.userId', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定ユーザIDに紐付くグループ（部門のみ）レコードを取得
     *
     * @param integer $userId ユーザID
     * @return array
     */
    public function getDepartments(int $userId): array
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('mg')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tgm.group = mg.groupId')
            ->where('tgm.user = :userId')
            ->andWhere('mg.groupType = :groupType')
            ->setParameter('userId', $userId)
            ->setParameter('groupType', DBConstant::GROUP_TYPE_DEPARTMENT)
            ->orderBy('mg.groupId', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定ユーザIDに紐付くグループを全レコード取得
     *
     * @param integer $userId ユーザID
     * @return array
     */
    public function getAllGroups(int $userId): array
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('mg.groupId', 'mg.groupName', 'mg.groupType')
        ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tgm.group = mg.groupId')
        ->where('tgm.user = :userId')
        ->andWhere('mg.groupType <> :groupType')
        ->setParameter('userId', $userId)
        ->setParameter('groupType', DBConstant::GROUP_TYPE_COMPANY)
        ->orderBy('mg.groupId', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定ユーザIDに紐付くグループをそれに紐づくOKR達成率と共に全レコード取得
     *
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getAllGroupsWithAchievementRate(int $userId, int $timeframeId): array
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('mg.groupId', 'mg.groupName', 'to.achievementRate')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tgm.group = mg.groupId')
            ->leftJoin('AppBundle:TOkr', 'to', 'WITH', '(mg.groupId = to.ownerGroup) AND (to.timeframe = :timeframeId OR to.timeframe is NULL)')
            ->where('tgm.user = :userId')
            ->andWhere('mg.groupType <> :groupType')
            ->andWhere('to.ownerType = :ownerType OR to.ownerType is NULL')
            ->andWhere('to.type = :type OR to.type is NULL')
            ->setParameter('userId', $userId)
            ->setParameter('groupType', DBConstant::GROUP_TYPE_COMPANY)
            ->setParameter('timeframeId', $timeframeId)
            ->setParameter('ownerType', DBConstant::OKR_OWNER_TYPE_GROUP)
            ->setParameter('type', DBConstant::OKR_TYPE_OBJECTIVE)
            ->orderBy('mg.groupId', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 2ユーザが所属している同一グループを取得
     *
     * @param integer $userId1 チェック対象ユーザID（１人目）
     * @param integer $userId2 チェック対象ユーザID（２人目）
     * @return array
     */
    public function getTheSameGroups(int $userId1, int $userId2): array
    {
        $qb = $this->createQueryBuilder('tgm1');
        $qb->select('IDENTITY(tgm1.group)')
            ->innerJoin('AppBundle:TGroupMember', 'tgm2', 'WITH', 'tgm1.group = tgm2.group')
            ->where('tgm1.user = :userId1')
            ->andWhere('tgm2.user = :userId2')
            ->setParameter('userId1', $userId1)
            ->setParameter('userId2', $userId2);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDの全レコードを削除
     *
     * @param integer $groupId グループID
     * @return void
     */
    public function deleteAllGroupMembers(int $groupId)
    {
        $sql = <<<SQL
        UPDATE t_group_member AS t0_
        SET t0_.deleted_at = NOW()
        WHERE (t0_.group_id = :groupId) AND (t0_.deleted_at IS NULL);
SQL;

        $params['groupId'] = $groupId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * 指定ユーザIDの全レコードを削除
     *
     * @param integer $userId ユーザID
     * @return void
     */
    public function deleteAllUserGroups(int $userId)
    {
        $sql = <<<SQL
        UPDATE t_group_member AS t0_
        SET t0_.deleted_at = NOW()
        WHERE (t0_.user_id = :userId) AND (t0_.deleted_at IS NULL);
SQL;

        $params['userId'] = $userId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * 所属グループの進捗率（前日・前々日）を取得
     *
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getGroupAchievementRateLog(int $userId, int $timeframeId): array
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('mg.groupId', 'mg.groupName', 'tarl1.achievementRate AS achievementRateToday', 'tarl2.achievementRate AS achievementRateYesterday')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tgm.group = mg.groupId')
            ->leftJoin('AppBundle:TAchievementRateLog', 'tarl1', 'WITH', 'tgm.group = tarl1.ownerGroupId AND (tarl1.createdAt >= :startDatetime1 AND tarl1.createdAt < :endDatetime1)')
            ->leftJoin('AppBundle:TAchievementRateLog', 'tarl2', 'WITH', 'tgm.group = tarl2.ownerGroupId AND (tarl2.createdAt >= :startDatetime2 AND tarl2.createdAt < :endDatetime2)')
            ->where('tgm.user = :userId')
            ->andWhere('tarl1.timeframeId = :timeframeId1')
            ->andWhere('tarl2.timeframeId = :timeframeId2')
            ->setParameter('userId', $userId)
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('timeframeId2', $timeframeId)
            ->setParameter('startDatetime1', DateUtility::getTodayDatetimeString())
            ->setParameter('endDatetime1', DateUtility::getTomorrowDatetimeString())
            ->setParameter('startDatetime2', DateUtility::getYesterdayDatetimeString())
            ->setParameter('endDatetime2', DateUtility::getTodayDatetimeString())
            ->orderBy('tgm.group', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 所属メンバーの進捗率（前日・前々日）を取得
     *
     * @param integer $groupId グループID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getMemberAchievementRateLog(int $groupId, int $timeframeId): array
    {
        $qb = $this->createQueryBuilder('tgm');
        $qb->select('mu.lastName', 'mu.firstName', 'tarl1.achievementRate AS achievementRateToday', 'tarl2.achievementRate AS achievementRateYesterday')
            ->innerJoin('AppBundle:MUser', 'mu', 'WITH', 'tgm.user = mu.userId')
            ->leftJoin('AppBundle:TAchievementRateLog', 'tarl1', 'WITH', 'tgm.user = tarl1.ownerUserId AND (tarl1.createdAt >= :startDatetime1 AND tarl1.createdAt < :endDatetime1)')
            ->leftJoin('AppBundle:TAchievementRateLog', 'tarl2', 'WITH', 'tgm.user = tarl2.ownerUserId AND (tarl2.createdAt >= :startDatetime2 AND tarl2.createdAt < :endDatetime2)')
            ->where('tgm.group = :groupId')
            ->andWhere('tarl1.timeframeId = :timeframeId1')
            ->andWhere('tarl2.timeframeId = :timeframeId2')
            ->setParameter('groupId', $groupId)
            ->setParameter('timeframeId1', $timeframeId)
            ->setParameter('timeframeId2', $timeframeId)
            ->setParameter('startDatetime1', DateUtility::getTodayDatetimeString())
            ->setParameter('endDatetime1', DateUtility::getTomorrowDatetimeString())
            ->setParameter('startDatetime2', DateUtility::getYesterdayDatetimeString())
            ->setParameter('endDatetime2', DateUtility::getTodayDatetimeString())
            ->orderBy('tgm.user', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
