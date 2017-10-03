import React, { Component } from 'react';
import { Surface, Pie } from 'recharts';
import { mean, isNaN, floor, toPairs, countBy, orderBy } from 'lodash';
import { okrsPropTypes } from './propTypes';
import styles from './OKROverallCharts.css';

export default class OKROverallCharts extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
  };

  render() {
    const { okrs = [] } = this.props;
    const rates = okrs.map(({ achievementRate }) => achievementRate);
    const overallProgress = mean(rates);
    const progressData = [
      { value: overallProgress, fill: '#626A7E' },
      { value: 100 - overallProgress, fill: '#D8DFE5' },
    ];
    const ratesGroupCount = toPairs(countBy(rates, (rate) => {
      if (rate >= 70) return '#42BBF8';
      if (rate >= 30) return '#AFDB56';
      return '#EFB04C';
    }));
    const ratesGroupCountData = ratesGroupCount.map(([fill, value]) => ({ value, fill }));
    const chartRadius = 60;
    const lineInset = 0.82; // fraction
    return (
      <div className={styles.content}>
        <div className={styles.charts}>
          <Surface
            className={styles.progressChart}
            width={chartRadius * 2}
            height={chartRadius * 2}
          >
            <text x={chartRadius} y={chartRadius - 10} textAnchor="middle" dominantBaseline="middle">
              全体進捗
            </text>
            <text x={chartRadius} y={chartRadius + 10} textAnchor="middle" dominantBaseline="middle">
              {isNaN(overallProgress) ? '－' : `${floor(overallProgress)}%`}
            </text>
            <Pie
              isAnimationActive={false}
              startAngle={90}
              endAngle={-270}
              cx={chartRadius}
              cy={chartRadius}
              outerRadius={chartRadius}
              innerRadius={chartRadius * lineInset}
              data={progressData}
              paddingAngle={0}
            />
          </Surface>
          <Surface
            className={styles.okrCountChart}
            width={chartRadius * 2}
            height={chartRadius * 2}
          >
            <text x={chartRadius} y={chartRadius - 10} textAnchor="middle" dominantBaseline="middle">
              目標数
            </text>
            <text x={chartRadius} y={chartRadius + 10} textAnchor="middle" dominantBaseline="middle">
              {rates.length}
            </text>
            <Pie
              isAnimationActive={false}
              startAngle={90}
              endAngle={-270}
              cx={chartRadius}
              cy={chartRadius}
              outerRadius={chartRadius}
              innerRadius={chartRadius * lineInset}
              data={orderBy(ratesGroupCountData, 'fill', 'desc')}
              paddingAngle={0}
            />
          </Surface>
        </div>
      </div>);
  }
}
