<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

/**
 * TGroupTreeリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TGroupTreeRepository extends BaseRepository
{
    /**
     * 指定グループツリーIDのレコードを取得
     *
     * @param integer $groupTreeId グループツリーID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getGroupPath(int $groupTreeId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('tgt');
        $qb->select('tgt')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tgt.group = mg.groupId')
            ->where('tgt.id = :id')
            ->andWhere('mg.company = :companyId')
            ->andWhere('mg.archivedFlg = :archivedFlg')
            ->setParameter('id', $groupTreeId)
            ->setParameter('companyId', $companyId)
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE);

        return $qb->getQuery()->getResult();
    }

    /**
     * グループパスを取得
     *
     * @param integer $groupTreeId グループツリーID
     * @param integer $companyId 会社ID
     * @return mixed
     * @throws NonUniqueResultException If the query result is not unique.
     * @throws NoResultException        If the query returned no result.
     */
    public function getGroupTreePath(int $groupTreeId, int $companyId)
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
     * グループツリーパスを指定しレコードを取得
     *
     * @param string $groupTreePath グループツリーパス
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getByGroupTreePath(string $groupTreePath)
    {
        $qb = $this->createQueryBuilder('tgt');
        $qb->select('tgt')
            ->where('tgt.groupTreePath = :groupTreePath')
            ->setParameter('groupTreePath', $groupTreePath);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * 全レコードを取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getAllGroupPath(int $companyId)
    {
        $qb = $this->createQueryBuilder('tgt');
        $qb->select('tgt')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tgt.group = mg.groupId')
            ->where('mg.company = :companyId')
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * グループIDでグループパスをLIKE検索
     *
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getLikeGroupId(int $groupId, int $companyId)
    {
        $qb = $this->createQueryBuilder('tgt');
        $qb->select('tgt')
            ->innerJoin('AppBundle:MGroup', 'mg', 'WITH', 'tgt.group = mg.groupId')
            ->where('mg.company = :companyId')
            ->andWhere('tgt.groupTreePath LIKE :groupId')
            ->setParameter('companyId', $companyId)
            ->setParameter('groupId', '%' . $groupId . '%');

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDの全レコードを削除
     *
     * @param integer $groupId グループID
     * @return void
     */
    public function deleteAllPaths(int $groupId)
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
