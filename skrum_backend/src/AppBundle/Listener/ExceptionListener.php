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
use AppBundle\Exception\JsonSchemaException;

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
        } else {
            LoggerManager::getInstance()->getLogger()->addError($exception->getMessage());
        }

        // ロールバック
        $this->rollback();

        $response = new Response();

        // エラーレスポンスを設定
        if ($exception instanceof HttpExceptionInterface) {
            if ($exception->getStatusCode() == Response::HTTP_NOT_FOUND) {
                $response->setStatusCode($exception->getStatusCode());
                $data['code'] = $exception->getStatusCode();
                $data['reason'] = 'noResource';
            } elseif ($exception->getStatusCode() == Response::HTTP_METHOD_NOT_ALLOWED) {
                $response->setStatusCode($exception->getStatusCode());
                $data['code'] = $exception->getStatusCode();
                $data['reason'] = 'methodNotAllowed';
            } elseif ($exception->getStatusCode() == Response::HTTP_BAD_REQUEST) {
                $response->setStatusCode($exception->getStatusCode());
                $data['code'] = $exception->getStatusCode();
                $data['reason'] = 'badRequest';
            } elseif ($exception->getStatusCode() == Response::HTTP_HTTP_SERVICE_UNAVAILABLE) {
                $response->setStatusCode($exception->getStatusCode());
                $data['code'] = $exception->getStatusCode();
                $data['reason'] = 'backendError';
            } else {
                $response->setStatusCode($exception->getStatusCode());
                $data['code'] = $exception->getStatusCode();
                $data['reason'] = 'someError';
            }
        } elseif ($exception instanceof InvalidParameterException) {
            $response->setStatusCode($exception->getResponseStatusCode());
            $data['code'] = $exception->getResponseStatusCode();
            $data['reason'] = $exception->getResponseReason();
            if ($exception->getResponseValidationErrors()) {
                $data['details'] = $exception->getResponseValidationErrors();
            } else {
                $data['details'] = array(array('field' => '', 'message' => 'Parameter in URL is invalid'));
            }
        } elseif ($exception instanceof JsonSchemaException) {
            $response->setStatusCode($exception->getResponseStatusCode());
            $data['code'] = $exception->getResponseStatusCode();
            $data['reason'] = $exception->getResponseReason();
            if ($exception->getResponseValidationErrors()) {
                $data['details'] = $exception->getResponseValidationErrors();
            } else {
                $data['details'] = array(array('field' => '', 'message' => 'No payload'));
            }
        } elseif ($exception instanceof ApplicationException || $exception instanceof SystemException) {
            $response->setStatusCode($exception->getResponseStatusCode());
            $data['code'] = $exception->getResponseStatusCode();
            $data['reason'] = $exception->getResponseReason();
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $data['code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
            $data['reason'] = 'systemError';
        }

        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

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
            if ($this->entityManager->isOpen()) {
                $this->entityManager->close();
                if ($this->entityManager->getConnection()->isTransactionActive())
                {
                    $this->entityManager->rollback();
                }
            }
        } catch (\Exception $e) {
            LoggerManager::getInstance()->getLogger()->addError($e->getMessage());
        }
    }
}
