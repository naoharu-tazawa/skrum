<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * 多重処理エラークラス
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
     * APIレスポンス：message
     *
     * @string
     */
    protected $responseMessage = '他のユーザによって更新されたため処理できませんでした';

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'doubleOperation';

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
