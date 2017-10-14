<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

/**
 * MCompanyリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class MCompanyRepository extends BaseRepository
{
    /**
     * オーナー検索
     *
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchCompany(string $keyword, int $companyId): array
    {
        $qb = $this->createQueryBuilder('mc');
        $qb->select('mc.companyId', 'mc.companyName', 'mc.imageVersion')
            ->where('mc.companyId = :companyId')
            ->andWhere('mc.companyName LIKE :companyName')
            ->setParameter('companyId', $companyId)
            ->setParameter('companyName', $keyword . '%');

        return $qb->getQuery()->getResult();
    }

    /**
     * 全ての会社をそれに紐づくOKR達成率と共に全レコードを取得（バッチ）
     *
     * @return array
     */
    public function getAllCompaniesWithAchievementRate(): array
    {
        $qb = $this->createQueryBuilder('mc');
        $qb->select('mc.companyId', 'IDENTITY(to.timeframe) AS timeframeId', 'to.achievementRate')
        ->innerJoin('AppBundle:TTimeframe', 'tt', 'WITH', 'mc.companyId = tt.company AND tt.defaultFlg = :defaultFlg')
        ->innerJoin('AppBundle:TOkr', 'to', 'WITH', 'mc.companyId = to.ownerCompanyId AND tt.timeframeId = to.timeframe')
        ->where('to.ownerType = :ownerType')
        ->andWhere('to.type = :type')
        ->setParameter('defaultFlg', DBConstant::FLG_TRUE)
        ->setParameter('ownerType', DBConstant::OKR_OWNER_TYPE_COMPANY)
        ->setParameter('type', DBConstant::OKR_TYPE_OBJECTIVE)
        ->orderBy('mc.companyId', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
