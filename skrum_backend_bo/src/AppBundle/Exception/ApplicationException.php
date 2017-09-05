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
}
