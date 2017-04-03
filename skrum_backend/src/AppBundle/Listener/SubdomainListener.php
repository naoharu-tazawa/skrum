<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AppBundle\Controller\Api\LoginController;
use AppBundle\Exception\PermissionException;

/**
 * サブドメインリスナークラス
 *
 * @author naoharu.tazawa
 */
class SubdomainListener
{
    /**
     * イベント処理
     *
     * @param FilterControllerEvent $event イベント
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        // コントローラ取得
        if (!is_array($controller = $event->getController())) {
            return;
        }

        // サブドメインチェック
        if (!($controller[0] instanceof LoginController)) {
            // リクエストオブジェクトを取得
            $request = $event->getRequest();

            // 認証情報を取得
            $auth = $request->get('auth_token');

            // リクエストURLのサブドメインを取得
            $subdomain = strstr($request->getHost(), '.', true);

            // サブドメインが正しいかチェック
            if ($subdomain !== $auth->getSubdomain()) {
                throw new PermissionException('サブドメインが不正です');
            }
        }
    }
}
