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
     * 年月日フォーマット
     */
    const DATE_FORMAT = 'Y-m-d';

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
     * 今月１日を取得
     *
     * @return string 今月１日
     */
    public static function get1stOfThisMonthString()
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, date('m'), 1, date('Y')));
    }

    /**
     * 今月１日時分秒を取得
     *
     * @return string 今月１日時分秒
     */
    public static function get1stOfThisMonthDatetimeString()
    {
        return date(self::DATETIME_FORMAT, mktime(0, 0, 0, date('m'), 1, date('Y')));
    }

    /**
     * 今月末日を取得
     *
     * @return string 今月末日
     */
    public static function getEndOfThisMonthString()
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, date('m') + 1, 0, date('Y')));
    }

    /**
     * 今月末日時分秒を取得
     *
     * @return string 今月末日時分秒
     */
    public static function getEndOfThisMonthDatetimeString()
    {
        return date(self::DATETIME_FORMAT, mktime(23, 59, 59, date('m') + 1, 0, date('Y')));
    }

    /**
     * 指定月末日を取得
     *
     * @param string $year
     * @param string $month
     * @return string 指定月末日
     */
    public static function getEndOfMonthString($year, $month)
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, $month + 1, 0, $year));
    }

    /**
     * 指定月末日時分秒を取得
     *
     * @param string $year
     * @param string $month
     * @return string 指定月末日時分秒
     */
    public static function getEndOfMonthDatetimeString($year, $month)
    {
        return date(self::DATETIME_FORMAT, mktime(23, 59, 59, $month + 1, 0, $year));
    }

    /**
     * 指定年月のXヶ月後の１日を取得
     *
     * @param string $year
     * @param string $month
     * @param integer $plus
     * @return string 指定年月のXヶ月後の１日
     */
    public static function get1stOfXMonthDateString($year, $month, $plus = 0)
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, $month + $plus, 1, $year));
    }

    /**
     * 指定年月のXヶ月後の月末日を取得
     *
     * @param string $year
     * @param string $month
     * @param integer $plus
     * @return string 指定年月のXヶ月後の月末日
     */
    public static function getEndOfXMonthDateString($year, $month, $plus = 0)
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, $month + 1 + $plus, 0, $year));
    }

    /**
     * string型の年月日をDateTime型に変換
     *
     * @return $dateString 年月日時分秒
     */
    public static function transIntoDatetime($dateString)
    {
        return new \DateTime($dateString);
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

    /**
     * string型年月日（yyyy-MM-dd）の要素を配列に分解（array('yyyy', 'MM', 'dd')）
     *
     * @param $dateString string型年月日（yyyy-MM-dd）
     * @return array array('yyyy', 'MM', 'dd')
     */
    public static function analyzeDate($dateString)
    {
        return explode('-', $dateString);
    }
}
