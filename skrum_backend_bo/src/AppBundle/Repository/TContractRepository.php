<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DateUtility;
use AppBundle\Entity\TContract;

/**
 * TContractリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TContractRepository extends BaseRepository
{
    /**
     * 有効な契約情報を取得
     *
     * @param integer $companyId 会社ID
     * @return TContract
     */
    public function getValidContract(int $companyId): TContract
    {
        $qb = $this->createQueryBuilder('tc');
        $qb->select('tc')
            ->where('tc.companyId = :companyId')
            ->andWhere('tc.planStartDate <= :planStartDate')
            ->andWhere('tc.planEndDate > :planEndDate')
            ->setParameter('companyId', $companyId)
            ->setParameter('planStartDate', DateUtility::getCurrentDateString())
            ->setParameter('planEndDate', DateUtility::getCurrentDateString());

        return $qb->getQuery()->getSingleResult();
    }
}
