<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * 認証エラークラス
 *
 * @author naoharu.tazawa
 */
class AuthenticationException extends ApplicationException
{
    /**
     * コンストラクタ
     *
	 * @param string $message ログ出力メッセージ
	 */
    public function __construct(string $message)
    {
        parent::__construct($message, 0, false, null);
    }
}
