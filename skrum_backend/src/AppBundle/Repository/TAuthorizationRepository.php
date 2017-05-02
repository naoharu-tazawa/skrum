<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;

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
}
