<?php

namespace AppBundle\Exception;

/**
 * システム汎用エラークラス
 *
 * @author naoharu.tazawa
 */
class SystemException extends \Exception
{
    /**
     * アラートログ出力フラグ
     *
     * @boolean
     */
    protected $alert = false;

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
    protected $responseMessage = 'システムエラーが発生しました';

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'systemException';

    /**
     * コンストラクタ
     *
	 * @param $message
	 * @param $code
	 * @param $code
	 * @param $previous
	 */
    public function __construct($message, $code = 0, $alert = false, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->alert = $alert;
    }

    /**
     * アラートログ出力フラグを取得
     *
     * @return Boolean
     */
    public function isAlert()
    {
        return $this->alert;
    }

    /**
     * HTTPステータスコードを取得
     *
     * @return integer
     */
    public function getResponseStatusCode()
    {
        return $this->responseStatusCode;
    }

    /**
     * エラーメッセージを取得
     *
     * @return string
     */
    public function getResponseMessage()
    {
        return $this->responseMessage;
    }

    /**
     * エラー事由を取得
     *
     * @return string
     */
    public function getResponseReason()
    {
        return $this->responseReason;
    }
}
