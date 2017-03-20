<?php

namespace AppBundle\Exception;

/**
 * アプリケーション汎用エラークラス
 *
 * @author naoharu.tazawa
 */
class ApplicationException extends \Exception
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
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'applicationException';

    /**
     * コンストラクタ
     *
	 * @param $message ログ出力メッセージ
	 * @param $alert アラートログ出力フラグ
	 */
    public function __construct($message, $alert = false)
    {
        parent::__construct($message, 0, null);
        $this->alert = $alert;
    }

    /**
     * アラートログ出力フラグを取得
     *
     * @return boolean
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
     * エラー事由を取得
     *
     * @return string
     */
    public function getResponseReason()
    {
        return $this->responseReason;
    }
}
