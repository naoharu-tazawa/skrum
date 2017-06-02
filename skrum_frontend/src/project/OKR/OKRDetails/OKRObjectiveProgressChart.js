import React, { Component } from 'react';
import { LineChart, CartesianGrid, XAxis, YAxis, Tooltip, Line } from 'recharts';
import _ from 'lodash';
import { ProgressSeriesPropTypes } from './propTypes';
import styles from './OKRObjectiveProgressChart.css';

export default class OKRObjectiveProgressChart extends Component {

  static propTypes = {
    progressSeries: ProgressSeriesPropTypes.isRequired,
  };

  render() {
    const { progressSeries = [] } = this.props;
    const progressData = progressSeries.map(({ datetime, achievementRate }) =>
      ({ datetime, progress: _.toNumber(achievementRate) }));
    const dateFormatter = date => `${date}`;
    const percentFormatter = percent => (percent ? `${percent}%` : '');
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
          <Tooltip />
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
