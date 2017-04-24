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
     * APIレスポンス：reason
     *
     * @string
     */
    protected $responseReason = 'systemException';

    /**
     * コンストラクタ
     *
	 * @param string $message ログ出力メッセージ
	 * @param boolean $alert アラートログ出力フラグ
	 */
    public function __construct(string $message, bool $alert = false)
    {
        parent::__construct($message, 0, null);
        $this->alert = $alert;
    }

    /**
     * アラートログ出力フラグを取得
     *
     * @return boolean
     */
    public function isAlert(): bool
    {
        return $this->alert;
    }

    /**
     * HTTPステータスコードを取得
     *
     * @return integer
     */
    public function getResponseStatusCode(): int
    {
        return $this->responseStatusCode;
    }

    /**
     * エラー事由を取得
     *
     * @return string
     */
    public function getResponseReason(): string
    {
        return $this->responseReason;
    }
}
