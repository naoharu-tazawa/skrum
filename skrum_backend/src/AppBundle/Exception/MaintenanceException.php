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
	 * @param string $message ログ出力メッセージ
	 */
    public function __construct(string $message)
    {
        parent::__construct($message, 0, false, null);
    }
}
