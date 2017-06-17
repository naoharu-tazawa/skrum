import React, { Component } from 'react';
import { ResponsiveContainer, LineChart, CartesianGrid, XAxis, YAxis, Tooltip, Line } from 'recharts';
import _ from 'lodash';
import { ProgressSeriesPropTypes } from './propTypes';
import { convertToRelativeTimeText, DateFormat } from '../../util/DatetimeUtil';
import styles from './OkrProgressChart.css';

export default class OkrProgressChart extends Component {

  static propTypes = {
    progressSeries: ProgressSeriesPropTypes.isRequired,
  };

  render() {
    const { progressSeries = [] } = this.props;
    const progressData = progressSeries.map(({ datetime, achievementRate }) =>
      ({ datetime, progress: _.toNumber(achievementRate) }));
    const percentFormatter = percent => (percent ? `${percent}%` : '');
    const dateFormatter = date => `${convertToRelativeTimeText(date, { format: DateFormat.YMD })}`;
    const renderTooltip = ([{ payload } = {}]) => (payload && (
      <div className={styles.tooltip} key={`tooltip-item-${payload.datetime}`}>
        <li>{dateFormatter(payload.datetime)}</li>
        <li>達成値: {payload.progress}%</li>
      </div>));
    return (
      <div className={styles.component}>
        <ResponsiveContainer width="100%" aspect={300 / 280}>
          <LineChart
            className={styles.progressChart}
            data={progressData}
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
              type="monotone"
              dataKey="progress"
              /* isAnimationActive={false} */
              stroke="#626A7E"
              strokeWidth={0.7}
              /* dot={false} */
            />
          </LineChart>
        </ResponsiveContainer>
      </div>);
  }
}
