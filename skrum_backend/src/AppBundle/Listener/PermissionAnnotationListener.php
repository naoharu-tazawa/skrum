<?php

namespace AppBundle\Listener;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AppBundle\Exception\PermissionException;

/**
 * 権限アノテーションリスナークラス
 *
 * @author naoharu.tazawa
 */
class PermissionAnnotationListener
{
    /**
     * アノテーションリーダー
     *
     * @var Reader
     */
    private $reader;

    /**
     * コンストラクタ
     *
     * @param Reader $reader アノテーションリーダー
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

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

        // 権限チェック
        if (!($controller[0] instanceof LoginController)) {
            $object = new \ReflectionObject($controller[0]);
            $method = $object->getMethod($controller[1]);

            // リクエストコントローラのアノテーションを取得
            $annotation = $this->reader->getMethodAnnotation($method, 'AppBundle\\Utils\\Permission');
            if (empty($annotation)) return;

            // 認証情報を取得
            $auth = $event->getRequest()->get('auth_token');

            // リクエストユーザが権限を持っているかチェック
            if (!in_array($annotation->getValue(), $auth->getPermissions())) {
                throw new PermissionException('権限がありません');
            }
        }
    }
}
