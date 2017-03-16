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
     * APIレスポンス：message
     *
     * @string
     */
    protected $responseMessage = 'データ不整合が発生しました';

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'dataInconsistency';

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
