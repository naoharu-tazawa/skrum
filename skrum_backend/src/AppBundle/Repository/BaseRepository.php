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
     * Tells the EntityManager to make an instance managed and persistent.
     *
     * The entity will be entered into the database at or before transaction
     * commit or as a result of the flush operation.
     *
     * @param object $entity The instance to make managed and persistent.
     * @return void
     * @throws ORMInvalidArgumentException
     */
    protected function persist($entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * Merges the state of a detached entity into the persistence context
     * of this EntityManager and returns the managed copy of the entity.
     * The entity passed to merge will not become associated/managed with this EntityManager.
     *
     * @param object $entity The detached entity to merge into the persistence context.
     * @return object The managed copy of the entity.
     * @throws ORMInvalidArgumentException
     */
    protected function merge($entity)
    {
        $this->getEntityManager()->merge($entity);
    }

    /**
     * Removes an entity instance.
     *
     * A removed entity will be removed from the database at or before transaction commit
     * or as a result of the flush operation.
     *
     * @param object $entity The entity instance to remove.
     * @return void
     * @throws ORMInvalidArgumentException
     */
    protected function remove($entity)
    {
        $this->getEntityManager()->remove($entity);
    }
}
