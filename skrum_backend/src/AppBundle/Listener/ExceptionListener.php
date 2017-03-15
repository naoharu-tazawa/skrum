<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use AppBundle\Utils\LoggerManager;

/**
 * 例外リスナークラス
 *
 * @author naoharu.tazawa
 */
class ExceptionListener
{
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
            $response->headers->replace($exception->getHeaders());
            $responseBody['code'] = $exception->getStatusCode();
            if ($exception->getStatusCode() == Response::HTTP_NOT_FOUND) {
                $responseBody['message'] = 'URIが不正です';
                $responseBody['reason'] = 'noRoute';
            } else {
                $responseBody['message'] = 'HTTPメソッドが不正です';
                $responseBody['reason'] = 'invalidHttpMethod';
            }
        } else {
            $response->setStatusCode($exception->getStatusCode());
            $responseBody['code'] = $exception->getStatusCode();
            $responseBody['message'] = $exception->getExceptionMessage();
            $responseBody['reason'] = $exception->getExceptionReason();
        }

        $response->setContent(json_encode($responseBody));

        // レスポンスオブジェクトを上書き
        $event->setResponse($response);
    }
}
