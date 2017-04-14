<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

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
     * @param $okrId OKRID
     * @return array
     */
    public function getAchievementRateChart($okrId)
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
}
