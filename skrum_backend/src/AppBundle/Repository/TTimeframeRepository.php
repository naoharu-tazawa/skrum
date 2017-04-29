<?php

namespace AppBundle\Repository;

/**
 * TTimeframeリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TTimeframeRepository extends BaseRepository
{
    /**
     * 指定タイムフレームIDのレコードを取得
     *
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getTimeframe(int $timeframeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('tt');
        $qb->select('tt')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'tt.company = mc.companyId')
            ->where('tt.timeframeId = :timeframeId')
            ->andWhere('tt.company = :companyId')
            ->setParameter('timeframeId', $timeframeId)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }
}
