<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * 権限エラークラス
 *
 * @author naoharu.tazawa
 */
class AuthenticationException extends ApplicationException
{
    /**
     * HTTPステータスコード
     *
     * @integer
     */
    protected $responseStatusCode = 401;

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'invalidCredentials';

    /**
     * コンストラクタ
     *
	 * @param $message エラーメッセージ
	 * @param $code エラーコード
	 */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code, false, null);
    }
}
