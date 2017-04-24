<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * 権限エラークラス
 *
 * @author naoharu.tazawa
 */
class NoDataException extends ApplicationException
{
    /**
     * HTTPステータスコード
     *
     * @integer
     */
    protected $responseStatusCode = 500;

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'dataInconsistency';

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
