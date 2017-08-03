<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DateUtility;

/**
 * TEmailReservationリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TEmailReservationRepository extends BaseRepository
{
    /**
     * 送信対象メールを取得（バッチ）
     *
     * @param integer $bulkSize バルクサイズ
     * @return array
     */
    public function getSendingEmails(int $bulkSize): array
    {
        $qb = $this->createQueryBuilder('tmr');
        $qb->select('tmr')
            ->where('tmr.sendingReservationDatetime <= :currentDatetime')
            ->andWhere('tmr.sendingDatetime is NULL')
            ->setParameter('currentDatetime', DateUtility::getCurrentDatetimeString())
            ->setMaxResults($bulkSize);

        return $qb->getQuery()->getResult();
    }
}
