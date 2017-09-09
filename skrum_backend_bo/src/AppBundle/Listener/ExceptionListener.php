<?php

namespace AppBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\TwigBundle\TwigEngine;
use AppBundle\Utils\LoggerManager;

/**
 * 例外リスナークラス
 *
 * @author naoharu.tazawa
 */
class ExceptionListener
{
    /**
     * サービスコンテナ
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Twigエンジン
     *
     * @var TwigEngine
     */
    private $templating;

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
    public function __construct(ContainerInterface $container, TwigEngine $templating)
    {
        $this->container = $container;
        $this->templating = $templating;

        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }
        $this->entityManager = $container->get('doctrine')->getManager();
    }

    /**
     * 例外イベント処理
     *
     * @param GetResponseForExceptionEvent $event 発生例外
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        static $handling;

        // 発生イベントから例外オブジェクトを取得
        $exception = $event->getException();

        if ($handling === true) {
            return;
        }

        // ログ出力
        if (method_exists($exception, 'isAlert')) {
            if ($exception->isAlert()) {
                LoggerManager::getInstance()->getLogger()->addAlert($exception->getMessage());
            } else {
                LoggerManager::getInstance()->getLogger()->addError($exception->getMessage());
            }
        } else {
            LoggerManager::getInstance()->getLogger()->addError($exception->getMessage());
        }

        // ロールバック
        $this->rollback();

        // 共通エラー画面を表示
        $message = $this->templating->render('error/index.html.twig', array(
                'message' => $exception->getMessage()
        ));

        $response = new Response($message, 500);
        $event->setResponse($response);

        $handling = true;
    }

    /**
     * ロールバック処理
     *
     * @return void
     */
    private function rollback()
    {
        try {
            if ($this->entityManager->isOpen()) {
                $this->entityManager->close();
                if ($this->entityManager->getConnection()->isTransactionActive()) {
                    $this->entityManager->rollback();
                }
            }
        } catch (\Exception $e) {
            LoggerManager::getInstance()->getLogger()->addError($e->getMessage());
        }
    }
}
