<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TAuthorization;

/**
 * TAuthorizationリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TAuthorizationRepository extends BaseRepository
{
    /**
     * 有効な認可情報を取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getValidAuthorization(int $companyId): array
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->select('ta')
            ->where('ta.companyId = :companyId')
            ->andWhere('ta.authorizationStartDatetime <= :authorizationStartDatetime')
            ->andWhere('ta.authorizationEndDatetime > :authorizationEndDatetime')
            ->andWhere('ta.authorizationStopFlg = :authorizationStopFlg')
            ->setParameter('companyId', $companyId)
            ->setParameter('authorizationStartDatetime', DateUtility::getCurrentDatetimeString())
            ->setParameter('authorizationEndDatetime', DateUtility::getCurrentDatetimeString())
            ->setParameter('authorizationStopFlg', DBConstant::FLG_FALSE);

        return $qb->getQuery()->getResult();
    }

    /**
     * 有効な認可情報を取得
     *
     * @param integer $companyId 会社ID
     * @return TAuthorization
     */
    public function getValidAuthorizationIncludingStoped(int $companyId): TAuthorization
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->select('ta')
            ->where('ta.companyId = :companyId')
            ->andWhere('ta.authorizationStartDatetime <= :authorizationStartDatetime')
            ->andWhere('ta.authorizationEndDatetime > :authorizationEndDatetime')
            ->setParameter('companyId', $companyId)
            ->setParameter('authorizationStartDatetime', DateUtility::getCurrentDatetimeString())
            ->setParameter('authorizationEndDatetime', DateUtility::getCurrentDatetimeString());

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * 有効な認可情報を取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getAuthorizations(): array
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->select('ta.authorizationStopFlg', 'tc AS tContract', 'mc.companyId', 'mc.companyName', 'mp.name AS planName')
            ->leftJoin('AppBundle:TContract', 'tc', 'WITH', 'ta.contractId = tc.contractId')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'ta.companyId = mc.companyId')
            ->innerJoin('AppBundle:MPlan', 'mp', 'WITH', 'ta.planId = mp.planId')
            ->where('ta.authorizationStartDatetime <= :authorizationStartDatetime')
            ->andWhere('ta.authorizationEndDatetime > :authorizationEndDatetime')
            ->setParameter('authorizationStartDatetime', DateUtility::getCurrentDatetimeString())
            ->setParameter('authorizationEndDatetime', DateUtility::getCurrentDatetimeString())
            ->addOrderBy('ta.companyId', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * 有効な認可情報を取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getAuthorization(int $companyId): array
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->select('ta.authorizationStopFlg', 'tc AS tContract', 'mc.companyId', 'mc.companyName', 'mp.planId AS planId', 'mp.name AS planName')
            ->leftJoin('AppBundle:TContract', 'tc', 'WITH', 'ta.contractId = tc.contractId')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'ta.companyId = mc.companyId')
            ->innerJoin('AppBundle:MPlan', 'mp', 'WITH', 'ta.planId = mp.planId')
            ->where('ta.companyId = :companyId')
            ->setParameter('companyId', $companyId)
            ->addOrderBy('ta.authorizationEndDatetime', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
