<?php

namespace AppBundle\Repository;

/**
 * MRolePermissionリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class MRolePermissionRepository extends BaseRepository
{
    /**
     * 権限情報を取得
     *
     * @param string $roleId ロールID
     * @return array
     */
    public function getPermissions(string $roleId): array
    {
        $qb = $this->createQueryBuilder('mrp');
        $qb->select('mps.name')
            ->innerJoin('AppBundle:MPermissionSettings', 'mps', 'WITH', 'mrp.permissionId = mps.permissionId')
            ->where('mrp.roleId = :roleId')
            ->setParameter('roleId', $roleId);

        return $qb->getQuery()->getResult();
    }
}
