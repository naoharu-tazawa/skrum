import React, { Component } from 'react';
import { LineChart, CartesianGrid, XAxis, YAxis, Tooltip, Line } from 'recharts';
import _ from 'lodash';
import { ProgressSeriesPropTypes } from './propTypes';
import { convertToRelativeTimeText, DateFormat } from '../../util/DatetimeUtil';
import styles from './OKRObjectiveProgressChart.css';

export default class OKRObjectiveProgressChart extends Component {

  static propTypes = {
    progressSeries: ProgressSeriesPropTypes.isRequired,
  };

  render() {
    const { progressSeries = [] } = this.props;
    const progressData = progressSeries.map(({ datetime, achievementRate }) =>
      ({ datetime, progress: _.toNumber(achievementRate) }));
    const percentFormatter = percent => (percent ? `${percent}%` : '');
    const dateFormatter = date => `${convertToRelativeTimeText(date, { format: DateFormat.YMD })}`;
    const renderTooltip = ([{ payload } = {}]) => (!payload ? null : (
      <div className={styles.tooltip} key={`tooltip-item-${payload.datetime}`}>
        <li>{dateFormatter(payload.datetime)}</li>
        <li>達成値: {payload.progress}%</li>
      </div>));
    return (
      <div className={styles.component}>
        <LineChart
          className={styles.progressChart}
          width={300} height={280} data={progressData}
          margin={{ top: 40, right: 40, bottom: 20, left: 0 }}
        >
          <CartesianGrid vertical={false} stroke="#ECF0F3" />
          <XAxis
            dataKey="datetime"
            axisLine={false}
            stroke="#626A7E"
            tickFormatter={dateFormatter}
          />
          <YAxis
            domain={['auto', 'auto']}
            axisLine={false}
            tickLine={false}
            stroke="#626A7E"
            tickFormatter={percentFormatter}
          />
          <Tooltip content={({ payload }) => renderTooltip(payload)} />
          <Line
            isAnimationActive={false}
            dataKey="progress"
            stroke="#626A7E"
            strokeWidth={8}
            dot={false}
          />
        </LineChart>
      </div>);
  }
}
