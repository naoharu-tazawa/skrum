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

export const getDate = () =>
  moment();

export const isValidDate = date =>
  date && moment(date, DateFormat.YMDHM).isValid();

export const toUtcDate = (date, { format = DateFormat.YMDHM } = {}) =>
  moment(date, format).local().format();

export const fromUtcDate = date =>
  moment(date);

export const toTimestamp = (date, { format = DateFormat.YMDHM } = {}) =>
  toNumber(moment(date, format).format('X'));

export const toDate = dateString => moment(dateString).locale('ja');

export const formatDate = (date, { format = DateFormat.YMD } = {}) =>
  moment(date).format(format);

export const formatTime = date =>
  moment(date).format('HH:mm');

export const formatDateTime = (date, { format = DateFormat.YMDHM } = {}) =>
  moment(date).format(format);

export const formatUtcDate = (date, { format = DateFormat.YMD } = {}) =>
  formatDate(fromUtcDate(date), { format });

export const formatUtcDateTime = (date, { format = DateFormat.YMDHM } = {}) =>
  formatDateTime(fromUtcDate(date), { format });

export const compareDates = (date1, date2) => {
  const timestamp1 = toTimestamp(date1);
  const timestamp2 = toTimestamp(date2);
  if (timestamp1 < timestamp2) return -1;
  if (timestamp1 > timestamp2) return 1;
  return 0;
};

export const toUrlDateParam = date => `${fromUtcDate(date).format('YYYY-MM-DD')} 00:00:00`;

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
export const toRelativeTimeText = (date) => {
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
  // return formatDateTime(date, options);
  return dateMoment.fromNow();
};
