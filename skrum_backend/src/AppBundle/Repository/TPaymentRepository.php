<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DateUtility;

/**
 * TPaymentリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TPaymentRepository extends BaseRepository
{
    /**
     * 指定会社IDの請求情報を取得
     *
     * @param $companyId 会社ID
     * @return array
     */
    public function getPayments($companyId)
    {
        $qb = $this->createQueryBuilder('tp');
        $qb->select('tp')
            ->innerJoin('AppBundle:TContract', 'tc', 'WITH', 'tp.contract = tc.contractId')
            ->where('tc.companyId = :companyId')
            ->andWhere('tc.planStartDate <= :now')
            ->andWhere('tc.planEndDate >= :now')
            ->setParameter('companyId', $companyId)
            ->setParameter('now', DateUtility::getCurrentDatetimeString())
            ->addOrderBy('tp.paymentDate', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
