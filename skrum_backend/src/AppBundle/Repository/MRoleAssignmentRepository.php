<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

/**
 * MRoleAssignmentリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class MRoleAssignmentRepository extends BaseRepository
{
    /**
     * 指定ロール割当ID、会社IDのレコードを取得
     *
     * @param integer $roleAssignmentId ロール割当ID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getRoleAssignment(int $roleAssignmentId, int $companyId): array
    {
        $qb = $this->createQueryBuilder('mra');
        $qb->select('mra')
            ->where('mra.companyId = :companyId')
            ->andWhere('mra.roleAssignmentId = :roleAssignmentId')
            ->setParameter('companyId', $companyId)
            ->setParameter('roleAssignmentId', $roleAssignmentId);

        return $qb->getQuery()->getResult();
    }

    /**
     * ロール一覧を取得
     *
     * @param integer $companyId 会社ID
     * @param boolean $superAdminFlg スーパー管理者取得フラグ
     * @return array
     */
    public function getRoles(int $companyId, bool $superAdminFlg = false): array
    {
        $qb = $this->createQueryBuilder('mra');
        $qb->select('mra')
            ->where('mra.companyId = :companyId')
            ->setParameter('companyId', $companyId);

        if (!$superAdminFlg) {
            $qb->andWhere('mra.roleLevel < :roleLevel')
                ->setParameter('roleLevel', DBConstant::ROLE_LEVEL_SUPERADMIN);
        }

        $qb->addOrderBy('mra.roleAssignmentId', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
