<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Utils\LoggerManager;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Exception\InvalidParameterException;

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
     * 例外イベント処理
     *
     * @param GetResponseForExceptionEvent $event 発生例外
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // 発生イベントから例外オブジェクトを取得
        $exception = $event->getException();

        // ログ出力
        if (method_exists($exception, 'isAlert')) {
            if ($exception->isAlert()) {
                LoggerManager::getInstance()->getLogger()->addAlert($exception->getMessage());
            } else {
                LoggerManager::getInstance()->getLogger()->addError($exception->getMessage());
            }
        }

        $response = new Response();

        // 例外詳細を設定
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $data['code'] = $exception->getStatusCode();
            if ($exception->getStatusCode() == Response::HTTP_NOT_FOUND) {
                $data['message'] = 'URIが不正です';
                $data['reason'] = 'noResource';
            } elseif ($exception->getStatusCode() == Response::HTTP_METHOD_NOT_ALLOWED) {
                $data['message'] = 'HTTPメソッドが不正です';
                $data['reason'] = 'invalidHttpMethod';
            } elseif ($exception->getStatusCode() == Response::HTTP_BAD_REQUEST) {
                $data['message'] = 'APIクエリが無効です';
                $data['reason'] = 'badRequest';
            }
        } elseif ($exception instanceof InvalidParameterException) {
            $response->setStatusCode($exception->getResponseStatusCode());
            $data['code'] = $exception->getResponseStatusCode();
            $data['message'] = $exception->getResponseMessage();
            $data['reason'] = $exception->getResponseReason();
            $data['errors'] = $exception->getResponseValidationErrors();
        } elseif ($exception instanceof ApplicationException || $exception instanceof SystemException) {
            $response->setStatusCode($exception->getResponseStatusCode());
            $data['code'] = $exception->getResponseStatusCode();
            $data['message'] = $exception->getResponseMessage();
            $data['reason'] = $exception->getResponseReason();
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $data['code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
            $data['message'] = 'システムエラーが発生しました';
            $data['reason'] = 'systemException';
        }

        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        // ロールバック
        $this->rollback();

        // レスポンスオブジェクトを上書き
        $event->setResponse($response);
    }

    /**
     * ロールバック処理
     *
     * @return void
     */
    private function rollback()
    {
        try {
            if ($this->entityManager->getConnection()->isTransactionActive())
            {
                $this->entityManager->rollback();
            }
        } catch (\Exception $e) {
        }
    }
}
