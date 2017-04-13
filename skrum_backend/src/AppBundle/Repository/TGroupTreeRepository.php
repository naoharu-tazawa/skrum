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

    /**
     * 指定グループIDの全レコードを削除
     *
     * @param $groupId グループID
     * @return array
     */
    public function deleteAllPaths($groupId)
    {
        $sql = <<<SQL
        UPDATE t_group_tree AS t0_
        SET t0_.deleted_at = NOW()
        WHERE (t0_.group_id = :groupId) AND (t0_.deleted_at IS NULL);
SQL;

        $params['groupId'] = $groupId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }
}
