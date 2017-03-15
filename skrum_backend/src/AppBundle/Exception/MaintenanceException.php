<?php

namespace AppBundle\Exception;

/**
 * メンテナンス例外クラス
 *
 * @author naoharu.tazawa
 */
class MaintenanceException extends \Exception
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
    const STATUS_CODE = 503;

    /**
     * APIレスポンス：message
     *
     * @string
     */
    const MESSAGE = 'メンテナンス中のため利用できません';

    /**
     * APIレスポンス：reason
     *
     * @string
     */
    const REASON = 'backendError';

    /**
     * コンストラクタ
     *
	 * @param $message
	 * @param $code
	 * @param $previous
	 */
    public function __construct ($message, $code = 0, \Exception $previous = null)
    {
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
