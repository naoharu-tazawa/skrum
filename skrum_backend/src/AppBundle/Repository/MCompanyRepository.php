<?php

namespace AppBundle\Repository;

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
        $qb->select('mc.companyId', 'mc.companyName')
            ->where('mc.companyId = :companyId')
            ->andWhere('mc.companyName LIKE :companyName')
            ->setParameter('companyId', $companyId)
            ->setParameter('companyName', $keyword . '%');

        return $qb->getQuery()->getResult();
    }
}
