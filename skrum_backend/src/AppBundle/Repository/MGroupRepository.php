<?php

namespace AppBundle\Repository;

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
     * @param $groupId グループID
     * @param $companyId 会社ID
     * @return array
     */
    public function getGroup($groupId, $companyId)
    {
        $qb = $this->createQueryBuilder('mg');
        $qb->select('mg')
            ->innerJoin('AppBundle:MCompany', 'mc', 'WITH', 'mg.company = mc.companyId')
            ->where('mg.groupId = :groupId')
            ->andWhere('mg.company = :companyId')
            ->setParameter('groupId', $groupId)
            ->setParameter('companyId', $companyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * 指定グループIDのレコードとそれに紐付くリーダーユーザのレコードを取得
     *
     * @param $groupId グループID
     * @param $companyId 会社ID
     * @return array
     */
    public function getGroupWithLeaderUser($groupId, $companyId)
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
}
