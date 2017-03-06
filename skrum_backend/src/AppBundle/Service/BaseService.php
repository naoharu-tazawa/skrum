<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Utils\LoggerManager;

/**
 * ベースサービス（被継承クラス）
 *
 * @author naoharu.tazawa
 */
class BaseService
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
     * @var Doctrine\ORM\EntityManager
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
        $this->entityManager = $container->get('doctrine');
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
     * @return Monolog\Logger monologロガーインスタンス
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
     * @return Doctrine\ORM\EntityManager Doctrineエンティティマネージャ
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
        $this->entityManager->beginTransaction();
    }

    /**
     * コミット
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function commit()
    {
        $this->entityManager->commit();
    }

    /**
     * ロールバック
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function rollback()
    {
        $this->entityManager->rollback();
    }


    //----------------------------------------------
    //ここからリポジトリクラスの取得メソッド
    //----------------------------------------------

    protected function getCompanyRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Company');
    }

    protected function getGroupRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Group');
    }

    protected function getGroupTagRepository()
    {
        return $this->entityManager->getRepository('AppBundle:GroupTag');
    }
    protected function getObjectiveOwnerRepository()
    {
        return $this->entityManager->getRepository('AppBundle:ObjectiveOwner');
    }

    protected function getOkrStructureRepository()
    {
        return $this->entityManager->getRepository('AppBundle:OkrStructure');
    }

    protected function getPaymentRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Payment');
    }

    protected function getPeriodRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Period');
    }

    protected function getRestrictionInfoRepository()
    {
        return $this->entityManager->getRepository('AppBundle:RestrictionInfo');
    }

    protected function getRoleMasterRepository()
    {
        return $this->entityManager->getRepository('AppBundle:RoleMaster');
    }

    protected function getTreeRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Tree');
    }

    protected function getUserRepository()
    {
        return $this->entityManager->getRepository('AppBundle:User');
    }

    protected function getUserGroupRepository()
    {
        return $this->entityManager->getRepository('AppBundle:UserGroup');
    }

    protected function getUserRoleRepository()
    {
        return $this->entityManager->getRepository('AppBundle:UserRole');
    }
}
