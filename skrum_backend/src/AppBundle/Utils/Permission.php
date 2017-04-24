<?php

namespace AppBundle\Utils;

/**
 * 権限アノテーションクラス
 *
 * @author naoharu.tazawa
 *
 * @Annotation
 */
class Permission
{
    /**
     * アノテーションパラメータ
     *
     * @var string
     */
    private $value;

    /**
     * コンストラクタ
     *
     * @param array $data アノテーション情報
     */
    public function __construct(array $data)
    {
        $this->value = $data['value'];
    }

    /**
     * アノテーションパラメータを取得
     *
     * @return string アノテーションパラメータ
     */
    public function getValue(): string
    {
        return $this->value;
    }
}