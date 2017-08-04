<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DateUtility;

/**
 * TAchievementRateLogリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TAchievementRateLogRepository extends BaseRepository
{
    /**
     * 本日分の登録済みログカウントを取得
     *
     * @return int
     */
    public function getTodayLogCount(): int
    {
        $qb = $this->createQueryBuilder('tarl');
        $qb->select('COUNT(tarl.id)')
            ->where('tarl.createdAt >= :startDatetime AND tarl.createdAt < :endDatetime')
            ->setParameter('startDatetime', DateUtility::getTodayDatetimeString())
            ->setParameter('endDatetime', DateUtility::getTomorrowDatetimeString());

        return $qb->getQuery()->getSingleScalarResult();
    }
}
