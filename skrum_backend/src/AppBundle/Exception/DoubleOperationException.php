<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * 排他処理エラークラス
 *
 * @author naoharu.tazawa
 */
class DoubleOperationException extends ApplicationException
{
    /**
     * HTTPステータスコード
     *
     * @integer
     */
    protected $responseStatusCode = 409;

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'doubleOperation';

    /**
     * コンストラクタ
     *
	 * @param $message ログ出力メッセージ
	 */
    public function __construct($message)
    {
        parent::__construct($message, 0, false, null);
    }
}
