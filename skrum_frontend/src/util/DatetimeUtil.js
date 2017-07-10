import moment from 'moment';
import { toNumber } from 'lodash';

export const TimeUnit = {
  SECOND: 'second',
  MINUTE: 'minute',
  HOUR: 'hour',
  YEAR: 'year',
};

export const DateFormat = {
  HM: 'LT',       // HourMinute               午後3時10分
  YMD: 'll',      // YearMonthDate            2016年3月10日
  YMDHM: 'lll',   // YearMonthDateHourMinute  2016年3月10日午後4時37分
  YMDHMD: 'LLLL', // YearMonthDateHourMinuteD 2016年3月10日午後4時37分 水曜日
};

// export const convertToTimestamp = date =>
// toNumber(moment(date, 'YYYY-MM-DD HH:mm:ss').format('X'));

export const toTimestamp = date => toNumber(moment(date).format('X'));

export const toDate = dateString => moment(dateString).locale('ja');

export const formatDate = (date, { format = DateFormat.YMDHM } = {}) =>
  moment(date).format(format);

const businessHour = 8;

/*
* 日付を文字列に変換します。
* 差分に応じて
* ~ 30秒 : たった今
* ~ 60秒 : 数秒前
* ~ 60分 : N分前
* ~ 8時間 : N時間前
* それ以外 : 2016年3月10日午後4時37分
*/
export const toRelativeTimeText = (date, options = {}) => {
  const dateMoment = moment(date).locale('ja');
  const nowMoment = moment();
  // seconds
  const secDiff = nowMoment.diff(dateMoment, TimeUnit.SECOND);
  if (secDiff < 0) {
    return 'Oh gosh！';
  }
  if (secDiff <= 30) {
    return 'たった今';
  }
  if (secDiff < 60) {
    return dateMoment.startOf(TimeUnit.SECOND).fromNow();
  }
  // minutes
  const minDiff = nowMoment.diff(dateMoment, TimeUnit.MINUTE);
  if (minDiff < 60) {
    return dateMoment.startOf(TimeUnit.MINUTE).fromNow();
  }
  // hours
  const hourDiff = nowMoment.diff(dateMoment, TimeUnit.HOUR);
  if (hourDiff < businessHour) {
    return dateMoment.startOf(TimeUnit.HOUR).fromNow();
  }
  // over day
  return formatDate(date, options);
};

export const toUtcDate = date =>
  moment(new Date(date)).local().format();

export const isValidDate = date =>
  date && new Date(date) !== 'Invalid Date' && !isNaN(new Date(date));
