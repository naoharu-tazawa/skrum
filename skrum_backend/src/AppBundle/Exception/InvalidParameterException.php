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
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'invalidParameter';

    /**
     * APIレスポンス：details
     *
     * @array
     */
    protected $responseValidationErrors = array();

    /**
     * コンストラクタ
     *
	 * @param $message ログ出力メッセージ
	 * @param $validationErrors バリデーションエラー詳細
	 */
    public function __construct($message, $validationErrors)
    {
        parent::__construct($message, 0, false, null);
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
