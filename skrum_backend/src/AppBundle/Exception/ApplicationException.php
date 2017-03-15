<?php

namespace AppBundle\Exception;

/**
 * アプリケーション例外クラス
 *
 * @author naoharu.tazawa
 */
class ApplicationException extends \Exception
{
    /**
     * アラートログ出力フラグ
     *
     * @Boolean
     */
    protected $alert = false;

    /**
     * HTTPステータスコード
     *
     * @integer
     */
    const STATUS_CODE = 500;

    /**
     * APIレスポンス：message
     *
     * @string
     */
    const MESSAGE = 'アプリケーション例外が発生しました';

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    const REASON = 'applicationException';

    /**
     * コンストラクタ
     *
	 * @param $message
	 * @param $alert
	 * @param $code
	 * @param $previous
	 */
    public function __construct ($message, $alert = false, $code = 0, \Exception $previous = null)
    {
        $this->alert = $alert;
        parent::__construct($message, $code, $previous);
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
    public function getStatusCode()
    {
        return self::STATUS_CODE;
    }

    /**
     * 例外メッセージを取得
     *
     * @return string
     */
    public function getExceptionMessage()
    {
        return self::MESSAGE;
    }

    /**
     * 例外理由を取得
     *
     * @return string
     */
    public function getExceptionReason()
    {
        return self::REASON;
    }
}
