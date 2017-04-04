<?php

namespace AppBundle\Utils;

/**
 * 汎用日付クラス
 *
 * @author naoharu.tazawa
 */
class DateUtility
{
    /**
     * 最大年月日時分秒
     */
    const MAX_DATETIME = '9999-12-31 23:59:59';

    /**
     * 年月日時分秒フォーマット
     */
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * 現在時刻（DateTime型）を取得
     *
     * @return \DateTime 現在時刻
     */
    public static function getCurrentDatetime()
    {
        return new \DateTime(date(self::DATETIME_FORMAT));
    }

    /**
     * 現在時刻（string型）を取得
     *
     * @return string 現在時刻
     */
    public static function getCurrentDatetimeString()
    {
        return date(self::DATETIME_FORMAT);
    }

    /**
     * 最大年月日時分秒（DateTime型）を取得
     *
     * @return \DateTime 最大年月日時分秒
     */
    public static function getMaxDatetime()
    {
        return new \DateTime(self::MAX_DATETIME);
    }

    /**
     * 最大年月日時分秒（string型）を取得
     *
     * @return string 最大年月日時分秒
     */
    public static function getMaxDatetimeString()
    {
        return self::MAX_DATETIME;
    }

    /**
     * X日後の23時59分59秒（DateTime型）を取得
     *
     * @param $days X日
     * @return \DateTime X日後の年月日23時59分59秒
     */
    public static function getXDaysAfter($days)
    {
        $dateString = date(self::DATETIME_FORMAT, mktime(23, 59, 59, date('m'), date('d') + $days, date('Y')));
        return new \DateTime($dateString);
    }
}
