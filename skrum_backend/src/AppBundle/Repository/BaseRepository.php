<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ベースリポジトリ（被継承クラス）
 *
 * @author naoharu.tazawa
 */
class BaseRepository extends EntityRepository
{
    /**
     * データの永続化（新規登録）
     *
     * @param object $entity The instance to make managed and persistent.
     * @return void
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    protected function persist($entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * データの同期
     *
     * @param object $entity The detached entity to merge into the persistence context.
     * @return object The managed copy of the entity.
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    protected function merge($entity)
    {
        $this->getEntityManager()->merge($entity);
    }

    /**
     * データの削除
     *
     * @param object $entity The entity instance to remove.
     * @return void
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    protected function remove($entity)
    {
        $this->getEntityManager()->remove($entity);
    }
}
