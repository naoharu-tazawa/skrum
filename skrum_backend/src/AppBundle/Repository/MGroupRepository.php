<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

/**
 * MGroupリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class MGroupRepository extends BaseRepository
{
    /**
     * 指定グループIDのレコードを取得
     *
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @param boolean $includingArchivedGroups アーカイブ済グループ取得フラグ
     * @return array
     */
    public function getGroup(int $groupId, int $companyId, bool $includingArchivedGroups = false): array
    {
        $qb = $this->createQueryBuilder('mg');
        $qb->select('mg')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'mg.company = mc.companyId')
            ->where('mg.groupId = :groupId')
            ->andWhere('mg.companyFlg = :companyFlg')
            ->andWhere('mg.company = :companyId')
            ->setParameter('groupId', $groupId)
            ->setParameter('companyFlg', DBConstant::FLG_FALSE)
            ->setParameter('companyId', $companyId);

        if (!$includingArchivedGroups) {
            $qb->andWhere('mg.archivedFlg = :archivedFlg')
                ->setParameter('archivedFlg', DBConstant::FLG_FALSE);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDのレコードとそれに紐付くリーダーユーザのレコードを取得
     *
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getGroupWithLeaderUser(int $groupId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('mg');
        $qb->select('mg', 'mu')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'mg.company = mc.companyId')
            ->leftJoin('AppBundle:MUser', 'mu', 'WITH', 'mg.leaderUserId = mu.userId')
            ->where('mg.groupId = :groupId')
            ->andWhere('mg.company = :companyId')
            ->setParameter('groupId', $groupId)
            ->setParameter('companyId', $companyId);

        $resultArray = $qb->getQuery()->getResult();
        if (count($resultArray) === 0) {
            return array();
        }

        return array('mGroup' => $resultArray[0], 'mUser' => $resultArray[1]);
    }

    /**
     * グループ検索
     *
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchGroup(string $keyword, int $companyId): array
    {
        $qb = $this->createQueryBuilder('mg');
        $qb->select('mg.groupId', 'mg.groupName')
            ->where('mg.company = :companyId')
            ->andWhere('mg.companyFlg = :companyFlg')
            ->andWhere('mg.archivedFlg = :archivedFlg')
            ->andWhere('mg.groupName LIKE :groupName')
            ->setParameter('companyId', $companyId)
            ->setParameter('companyFlg', DBConstant::FLG_FALSE)
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE)
            ->setParameter('groupName', $keyword . '%');

        return $qb->getQuery()->getResult();
    }

    /**
     * グループ検索（ページング）検索結果数を取得
     *
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return mixed
     * @throws NonUniqueResultException If the query result is not unique.
     * @throws NoResultException        If the query returned no result.
     */
    public function getPagesearchCount(string $keyword, int $companyId)
    {
        $qb = $this->createQueryBuilder('mg');
        $qb->select('COUNT(mg.groupId)')
            ->where('mg.company = :companyId')
            ->andWhere('mg.companyFlg = :companyFlg')
            ->andWhere('mg.archivedFlg = :archivedFlg')
            ->andWhere('mg.groupName LIKE :groupName')
            ->setParameter('companyId', $companyId)
            ->setParameter('companyFlg', DBConstant::FLG_FALSE)
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE)
            ->setParameter('groupName', $keyword . '%');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * グループ検索（ページング）
     *
     * @param string $keyword 検索ワード
     * @param integer $page 要求ページ
     * @param integer $perPage １ページあたり表示数
     * @param integer $companyId 会社ID
     * @return array
     */
    public function pagesearchGroup(string $keyword, int $page, int $perPage, int $companyId): array
    {
        $qb = $this->createQueryBuilder('mg');
        $qb->select('mg.groupId', 'mg.groupType', 'mg.groupName')
            ->where('mg.company = :companyId')
            ->andWhere('mg.companyFlg = :companyFlg')
            ->andWhere('mg.archivedFlg = :archivedFlg')
            ->andWhere('mg.groupName LIKE :groupName')
            ->setParameter('companyId', $companyId)
            ->setParameter('companyFlg', DBConstant::FLG_FALSE)
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE)
            ->setParameter('groupName', $keyword . '%')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    /**
     * グループツリーパス検索
     *
     * @param string $keyword 検索ワード
     * @param integer $companyId 会社ID
     * @return array
     */
    public function searchGroupTree(string $keyword, int $companyId): array
    {
        $qb = $this->createQueryBuilder('mg');
        $qb->select('tgt.id', 'tgt.groupTreePathName')
            ->innerJoin('AppBundle:TGroupTree', 'tgt', 'WITH', 'mg.groupId = tgt.group')
            ->where('mg.company = :companyId')
            ->andWhere('mg.companyFlg = :companyFlg')
            ->andWhere('mg.archivedFlg = :archivedFlg')
            ->andWhere('mg.groupName LIKE :groupName')
            ->setParameter('companyId', $companyId)
            ->setParameter('companyFlg', DBConstant::FLG_FALSE)
            ->setParameter('archivedFlg', DBConstant::FLG_FALSE)
            ->setParameter('groupName', $keyword . '%');

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定リーダーユーザIDをNULLに更新
     *
     * @param integer $userId グループID
     * @param integer $companyId 会社ID
     * @return void
     */
    public function setNullOnLeaderUserId(int $userId, int $companyId)
    {
        $sql = <<<SQL
        UPDATE m_group AS m0_
        SET m0_.leader_user_id = null, m0_.updated_at = NOW()
        WHERE (m0_.company_id = :companyId AND m0_.leader_user_id = :leaderUserId) AND (m0_.deleted_at IS NULL);
SQL;

        $params['companyId'] = $companyId;
        $params['leaderUserId'] = $userId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }
}
