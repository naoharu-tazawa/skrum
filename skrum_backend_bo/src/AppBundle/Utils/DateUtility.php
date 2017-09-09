<?php

namespace AppBundle\Utils;

use AppBundle\Exception\ApplicationException;

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
     * 最大年月日
     */
    const MAX_DATE = '9999-12-31';

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
    public static function getCurrentDatetime(): \DateTime
    {
        return new \DateTime(date(self::DATETIME_FORMAT));
    }

    /**
     * 現在時刻（string型）を取得
     *
     * @return string 現在時刻
     */
    public static function getCurrentDatetimeString(): string
    {
        return date(self::DATETIME_FORMAT);
    }

    /**
     * 本日日付（DateTime型）を取得
     *
     * @return \DateTime 本日日付
     */
    public static function getCurrentDate(): \DateTime
    {
        return new \DateTime(date(self::DATE_FORMAT));
    }

    /**
     * 本日日付（string型）を取得
     *
     * @return string 本日日付
     */
    public static function getCurrentDateString(): string
    {
        return date(self::DATE_FORMAT);
    }

    /**
     * 最大年月日時分秒（DateTime型）を取得
     *
     * @return \DateTime 最大年月日時分秒
     */
    public static function getMaxDatetime(): \DateTime
    {
        return new \DateTime(self::MAX_DATETIME);
    }

    /**
     * 最大年月日時分秒（string型）を取得
     *
     * @return string 最大年月日時分秒
     */
    public static function getMaxDatetimeString(): string
    {
        return self::MAX_DATETIME;
    }

    /**
     * 最大年月日（DateTime型）を取得
     *
     * @return \DateTime 最大年月日
     */
    public static function getMaxDate(): \DateTime
    {
        return new \DateTime(self::MAX_DATE);
    }

    /**
     * 最大年月日（string型）を取得
     *
     * @return string 最大年月日
     */
    public static function getMaxDateString(): string
    {
        return self::MAX_DATE;
    }

    /**
     * 今月１日を取得
     *
     * @return string 今月１日
     */
    public static function get1stOfThisMonthString(): string
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, date('m'), 1, date('Y')));
    }

    /**
     * 今月１日時分秒を取得
     *
     * @return string 今月１日時分秒
     */
    public static function get1stOfThisMonthDatetimeString(): string
    {
        return date(self::DATETIME_FORMAT, mktime(0, 0, 0, date('m'), 1, date('Y')));
    }

    /**
     * 今月末日を取得
     *
     * @return string 今月末日
     */
    public static function getEndOfThisMonthString(): string
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, date('m') + 1, 0, date('Y')));
    }

    /**
     * 今月末日時分秒を取得
     *
     * @return string 今月末日時分秒
     */
    public static function getEndOfThisMonthDatetimeString(): string
    {
        return date(self::DATETIME_FORMAT, mktime(23, 59, 59, date('m') + 1, 0, date('Y')));
    }

    /**
     * 指定月末日を取得
     *
     * @param string $year 指定年
     * @param string $month 指定月
     * @return string 指定月末日
     */
    public static function getEndOfMonthString(int $year, int $month): string
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, $month + 1, 0, $year));
    }

    /**
     * 指定月末日時分秒を取得
     *
     * @param integer $year 指定年
     * @param integer $month 指定月
     * @return string 指定月末日時分秒
     */
    public static function getEndOfMonthDatetimeString(int $year, int $month): string
    {
        return date(self::DATETIME_FORMAT, mktime(23, 59, 59, $month + 1, 0, $year));
    }

    /**
     * 翌日の0時0分0秒を取得
     *
     * @return string 翌日0時0分0秒
     */
    public static function getTomorrowDatetimeString(): string
    {
        return date(self::DATETIME_FORMAT, mktime(0, 0, 0, date('m'), date('d') + 1, date('y')));
    }

    /**
     * 当日の0時0分0秒を取得
     *
     * @return string 当日0時0分0秒
     */
    public static function getTodayDatetimeString(): string
    {
        return date(self::DATETIME_FORMAT, mktime(0, 0, 0, date('m'), date('d'), date('y')));
    }

    /**
     * 前日の0時0分0秒を取得
     *
     * @return string 前日0時0分0秒
     */
    public static function getYesterdayDatetimeString(): string
    {
        return date(self::DATETIME_FORMAT, mktime(0, 0, 0, date('m'), date('d') - 1, date('y')));
    }

    /**
     * 当日のX時Y分0秒を取得
     *
     * @param integer $hour 指定時
     * @param integer $minute 指定分
     * @return string 当日X時Y分0秒
     */
    public static function getTodayXYTimeDatetimeString(int $hour, int $minute): string
    {
        return date(self::DATETIME_FORMAT, mktime($hour, $minute, 0, date('m'), date('d'), date('y')));
    }

    /**
     * 指定年月のXヶ月後の１日を取得
     *
     * @param integer $year 指定年
     * @param integer $month 指定月
     * @param integer $plus Xヶ月後
     * @return string 指定年月のXヶ月後の１日
     */
    public static function get1stOfXMonthDateString(int $year, int $month, int $plus = 0): string
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, $month + $plus, 1, $year));
    }

    /**
     * 指定年月のXヶ月後の月末日を取得
     *
     * @param integer $year 指定年
     * @param integer $month 指定月
     * @param integer $plus Xヶ月後
     * @return string 指定年月のXヶ月後の月末日
     */
    public static function getEndOfXMonthDateString(int $year, int $month, int $plus = 0): string
    {
        return date(self::DATE_FORMAT, mktime(0, 0, 0, $month + 1 + $plus, 0, $year));
    }

    /**
     * string型の年月日をDateTime型に変換
     *
     * @param string $dateString 年月日時分秒
     * @return \DateTime
     */
    public static function transIntoDatetime(string $dateString): \DateTime
    {
        return new \DateTime($dateString);
    }

    /**
     * DateTime型をstring型（年月日時分秒）に変換
     *
     * @param \DateTime $datetime 年月日時分秒
     * @return string
     */
    public static function transIntoDatetimeString(\DateTime $datetime): string
    {
        return date(self::DATETIME_FORMAT, $datetime->format('U'));
    }

    /**
     * DateTime型をstring型（年月日）に変換
     *
     * @param \DateTime $datetime 年月日時分秒
     * @return string
     */
    public static function transIntoDateString(\DateTime $datetime): string
    {
        return date(self::DATE_FORMAT, $datetime->format('U'));
    }

    /**
     * X日前の0時0分0秒（string型）を取得
     *
     * @param $days X日
     * @return string X日前の年月日0時0分0秒
     */
    public static function getXDaysBeforeString(int $days): string
    {
        return date(self::DATETIME_FORMAT, mktime(0, 0, 0, date('m'), date('d') - $days, date('y')));
    }

    /**
     * X日後の23時59分59秒（DateTime型）を取得
     *
     * @param $days X日
     * @return \DateTime X日後の年月日23時59分59秒
     */
    public static function getXDaysAfter(int $days): \DateTime
    {
        $dateString = date(self::DATETIME_FORMAT, mktime(23, 59, 59, date('m'), date('d') + $days, date('Y')));
        return new \DateTime($dateString);
    }

    /**
     * string型年月日（yyyy-MM-dd）の要素を配列に分解（array('yyyy', 'MM', 'dd')）
     *
     * @param string $dateString 年月日（yyyy-MM-dd）
     * @return array array('yyyy', 'MM', 'dd')
     */
    public static function analyzeDate(string $dateString): array
    {
        return explode('-', $dateString);
    }

    /**
     * 開始日と終了日の妥当性チェック
     *
     * @param string $startDate 年月日時分秒（yyyy-MM-ddThh:mm:ssZ または yyyy-MM-dd hh:mm:ss）
     * @param string $endDate 年月日時分秒（yyyy-MM-ddThh:mm:ssZ または yyyy-MM-dd hh:mm:ss）
     * @return void
     */
    public static function checkStartDateAndEndDate(string $startDate, string $endDate)
    {
        // 開始日と終了日を分解し配列に格納
        $startDateArray = preg_split("(-|T| )", $startDate);
        $endDateArray = preg_split("(-|T| )", $endDate);

        // 開始日妥当性チェック
        if (!checkdate($startDateArray[1], $startDateArray[2], $startDateArray[0])) {
            throw new ApplicationException('開始日が不正です');
        }

        // 終了日妥当性チェック
        if (!checkdate($endDateArray[1], $endDateArray[2], $endDateArray[0])) {
            throw new ApplicationException('終了日が不正です');
        }

        // 「開始日 <= 終了日」であるかチェック
        if ($startDate > $endDate) {
            throw new ApplicationException('終了日は開始日以降に設定してください');
        }
    }
}
