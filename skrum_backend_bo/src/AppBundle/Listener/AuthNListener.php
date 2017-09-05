<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AppBundle\Exception\AuthenticationException;
use AppBundle\Controller\Manage\LoginController;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\LoggerManager;

/**
 * 認証リスナークラス
 *
 * @author naoharu.tazawa
 */
class AuthNListener
{
    /**
     * イベント処理
     *
     * @param FilterControllerEvent $event イベント
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        static $handling;

        if ($handling === true) {
            return;
        }

        // コントローラ取得
        if (!is_array($controller = $event->getController())) {
            return;
        }

        // セッションチェック
        if (!(0 === strpos($event->getRequest()->getPathInfo(), '/login'))) {
            $session = $event->getRequest()->getSession()->get('session');

            if ($session === null) {
                throw new AuthenticationException('ログインしてください');
            }
        }

        $handling = true;
    }
}
