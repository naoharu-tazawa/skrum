<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * パラメータ不正エラークラス
 *
 * @author naoharu.tazawa
 */
class InvalidParameterException extends ApplicationException
{
    /**
     * HTTPステータスコード
     *
     * @integer
     */
    protected $responseStatusCode = 400;

    /**
     * APIレスポンス：message
     *
     * @string
     */
    protected $responseMessage = '入力値が正しくありません';

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'invalidParameter';

    /**
     * APIレスポンス：reason
     *
     * @array
     */
    protected $responseValidationErrors = array();

    /**
     * コンストラクタ
     *
	 * @param $message エラーメッセージ
	 * @param $code エラーコード
	 * @param $validationErrors バリデーションエラー詳細
	 */
    public function __construct($message, $validationErrors, $code = 0)
    {
        parent::__construct($message, $code, false, null);
        $this->responseValidationErrors = $validationErrors;
    }

    /**
     * バリデーションエラー詳細を取得
     *
     * @return array
     */
    public function getResponseValidationErrors()
    {
        return $this->responseValidationErrors;
    }
}
