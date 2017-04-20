<?php

namespace AppBundle\Logic;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Utils\LoggerManager;

/**
 * ベースロジック（被継承クラス）
 *
 * @author naoharu.tazawa
 */
class BaseLogic
{
    /**
     * サービスコンテナ
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Doctrineエンティティマネージャ
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * コンストラクタ
     *
     * @param ContainerInterface $container サービスコンテナ
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }
        $this->entityManager = $container->get('doctrine')->getManager();
    }

    /**
     * サービスコンテナ取得
     *
     * @return ContainerInterface サービスコンテナ
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * YAML定義パラメータ取得
     *
     * @param string $name The parameter name
     * @return mixed
     */
    protected function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    /**
     * ロガー取得
     *
     * @return \Monolog\Logger monologロガーインスタンス
     */
    private function getLogger()
    {
        return LoggerManager::getInstance()->getLogger();
    }

    /**
     * DEBUGログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logDebug($message, array $context = array())
    {
        return $this->getLogger()->addDebug($message, $context);
    }

    /**
     * INFOログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logInfo($message, array $context = array())
    {
        return $this->getLogger()->addInfo($message, $context);
    }

    /**
     * WARNログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logWarning($message, array $context = array())
    {
        return $this->getLogger()->addWarning($message, $context);
    }

    /**
     * ERRORログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logError($message, array $context = array())
    {
        return $this->getLogger()->addError($message, $context);
    }

    /**
     * CRITICALログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logCritical($message, array $context = array())
    {
        return $this->getLogger()->addCritical($message, $context);
    }

    /**
     * ALERTログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logAlert($message, array $context = array())
    {
        return $this->getLogger()->addAlert($message, $context);
    }

    /**
     * Doctrineエンティティマネージャの取得
     *
     * @return \Doctrine\ORM\EntityManager Doctrineエンティティマネージャ
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * トランザクション開始
     *
     * @return void
     */
    protected function beginTransaction()
    {
        if (!$this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->beginTransaction();
        }
    }

    /**
     * トランザクション確定
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function commit()
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->commit();
        }
    }

    /**
     * ロールバック
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function rollback()
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }
    }

    /**
     * データの永続化（新規登録）
     *
     * @param object $entity The instance to make managed and persistent.
     * @return void
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function persist($entity)
    {
        $this->entityManager->persist($entity);
    }

    /**
     * データの同期
     *
     * @param object $entity The detached entity to merge into the persistence context.
     * @return object The managed copy of the entity.
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function merge($entity)
    {
        $this->entityManager->merge($entity);
    }

    /**
     * データの削除
     *
     * @param object $entity The entity instance to remove.
     * @return void
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function remove($entity)
    {
        $this->entityManager->remove($entity);
    }

    /**
     * データベースに操作を反映
     * @param null|object|array $entity
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException If a version check on an entity that
     *         makes use of optimistic locking fails.
     */
    public function flush($entity = null)
    {
        $this->entityManager->flush($entity);
    }

    //----------------------------------------------
    //ここからリポジトリクラスの取得メソッド
    //----------------------------------------------

    protected function getMCompanyRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MCompany');
    }

    protected function getMGroupRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MGroup');
    }

    protected function getMNormalTimeframeRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MNormalTimeframe');
    }

    protected function getMNormalTimeframeDetailRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MNormalTimeframeDetail');
    }

    protected function getMPermissionSettingsRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MPermissionSettings');
    }

    protected function getMPlanRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MPlan');
    }

    protected function getMRoleAssignmentRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MRoleAssignment');
    }

    protected function getMRolePermissionRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MRolePermission');
    }

    protected function getMRoleRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MRole');
    }

    protected function getMUserRepository()
    {
        return $this->entityManager->getRepository('AppBundle:MUser');
    }

    protected function getSGroupTreePathIdRepository()
    {
        return $this->entityManager->getRepository('AppBundle:SGroupTreePathId');
    }

    protected function getTAuthorizationRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TAuthorization');
    }

    protected function getTContractRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TContract');
    }

    protected function getTGroupMemberRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TGroupMember');
    }

    protected function getTGroupTreeRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TGroupTree');
    }

    protected function getTMailReservationRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TMailReservation');
    }

    protected function getTOkrActivityRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TOkrActivity');
    }

    protected function getTOkrRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TOkr');
    }

    protected function getTPaymentRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TPayment');
    }

    protected function getTPostRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TPost');
    }

    protected function getTPreUserRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TPreUser');
    }

    protected function getTTimeframeRepository()
    {
        return $this->entityManager->getRepository('AppBundle:TTimeframe');
    }
}
