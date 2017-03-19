<?php

namespace AppBundle\Exception;

use AppBundle\Exception\ApplicationException;

/**
 * パラメータ不正エラークラス
 *
 * @author naoharu.tazawa
 */
class JsonSchemaException extends ApplicationException
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
    protected $responseReason = 'invalidJsonSchema';

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
