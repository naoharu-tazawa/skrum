<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * メンテナンスエラークラス
 *
 * @author naoharu.tazawa
 */
class MaintenanceException extends ApplicationException
{
    /**
     * HTTPステータスコード
     *
     * @integer
     */
    protected $responseStatusCode = 503;

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'underMaintenance';

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
