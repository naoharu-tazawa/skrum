<?php

namespace AppBundle\Repository;

/**
 * TContractリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TContractRepository extends BaseRepository
{
    /**
     * 指定会社IDのレコードを取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getContract(int $companyId): array
    {
        $qb = $this->createQueryBuilder('tc');
        $qb->select('tc', 'mp')
            ->innerJoin('AppBundle:MPlan', 'mp', 'WITH', 'tc.planId = mp.planId')
            ->where('tc.companyId = :companyId')
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }
}
