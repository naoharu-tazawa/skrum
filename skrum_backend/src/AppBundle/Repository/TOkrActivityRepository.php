<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkrActivity;

/**
 * TOkrActivityリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TOkrActivityRepository extends BaseRepository
{
    /**
     * 指定OKRIDの達成率チャート情報を取得
     *
     * @param integer $okrId OKRID
     * @return array
     */
    public function getAchievementRateChart(int $okrId): array
    {
        $qb = $this->createQueryBuilder('toa');
        $qb->select('toa.activityDatetime AS datetime', 'toa.achievementRate AS achievementRate')
            ->where('toa.okr = :okrId')
            ->andWhere('toa.type IN (:typeGenerate, :typeAchievement)')
            ->setParameter('okrId', $okrId)
            ->setParameter('typeGenerate', DBConstant::OKR_OPERATION_TYPE_GENERATE)
            ->setParameter('typeAchievement', DBConstant::OKR_OPERATION_TYPE_ACHIEVEMENT)
            ->orderBy('toa.activityDatetime', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 最新の進捗登録時のエンティティを取得（バッチ）
     *
     * @param integer $userId ユーザID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    public function getLatestEntityInAchievementRegistration(int $userId, int $timeframeId): array
    {
        $qb = $this->createQueryBuilder('toa');
        $qb->select('toa.activityDatetime')
            ->innerJoin('AppBundle:TOkr', 'to', 'WITH', 'toa.okr = to.okrId')
            ->where('toa.type = :type')
            ->andWhere('to.timeframe = :timeframeId')
            ->andWhere('to.ownerUser = :ownerUserId')
            ->setParameter('type', DBConstant::OKR_OPERATION_TYPE_ACHIEVEMENT)
            ->setParameter('timeframeId', $timeframeId)
            ->setParameter('ownerUserId', $userId)
            ->orderBy('toa.activityDatetime', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getSingleResult();
    }
}
