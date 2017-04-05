<?php

namespace AppBundle\Repository;

/**
 * TGroupTreeリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TGroupTreeRepository extends BaseRepository
{
    /**
     * グループパスを取得
     *
     * @param $groupTreeId グループツリーID
     * @param $companyId 会社ID
     * @return mixed
     * @throws NonUniqueResultException If the query result is not unique.
     * @throws NoResultException        If the query returned no result.
     */
    public function getGroupTreePath($groupTreeId, $companyId)
    {
        $qb = $this->createQueryBuilder('tgt');
        $qb->select('tgt.groupTreePath', 'tgt.groupTreePathName')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tgt.group = mg.groupId')
            ->where('tgt.id = :id')
            ->andWhere('mg.company = :companyId')
            ->setParameter('id', $groupTreeId)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getSingleResult();
    }
}
