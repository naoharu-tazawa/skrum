<?php

namespace AppBundle\Listener;

use \Firebase\JWT\JWT;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AppBundle\Exception\AuthenticationException;
use AppBundle\Controller\Api\LoginController;
use AppBundle\Utils\Auth;

/**
 * SSOリスナークラス
 *
 * @author naoharu.tazawa
 */
class SSOListener
{
    /**
     * シークレットキー
     *
     * @var string
     */
    private $secretKey;

    /**
     * コンストラクタ
     *
     * @param string $secretKey シークレットキー
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
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

        // JWT認証
        if (!($controller[0] instanceof LoginController)) {
            $jwt = $event->getRequest()->headers->get('Authorization');
            try {
                $decoded = JWT::decode($jwt, $this->secretKey, array('HS256'));
            } catch(\Exception $e) {
                throw new AuthenticationException($e->getMessage());
            }
        }

        // JWTをAuthクラスにマッピング
        $decoded_array = (array) $decoded;
        $auth = new Auth(
                    $decoded_array['sdm'],
                    $decoded_array['uid'],
                    $decoded_array['cid'],
                    $decoded_array['rid'],
                    $decoded_array['rlv'],
                    $decoded_array['permissions']
                );

        // Authをリクエストオブジェクトにセット
        $event->getRequest()->attributes->set('auth_token', $auth);
    }
}
