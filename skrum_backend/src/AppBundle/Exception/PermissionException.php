<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * 権限エラークラス
 *
 * @author naoharu.tazawa
 */
class PermissionException extends ApplicationException
{
    /**
     * HTTPステータスコード
     *
     * @integer
     */
    protected $responseStatusCode = 403;

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'insufficientPermissions';

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
