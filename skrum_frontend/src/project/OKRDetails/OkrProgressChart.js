import React, { Component } from 'react';
import { XYPlot, XAxis, YAxis, HorizontalGridLines, makeWidthFlexible, LineMarkSeries, Hint } from 'react-vis';
import { toNumber } from 'lodash';
import { ProgressSeriesPropTypes } from './propTypes';
import ChartHighlightOverlay from '../../components/ChartHighlightOverlay';
import { formatDate, DateFormat } from '../../util/DatetimeUtil';
import styles from './OkrProgressChart.css';

const FlexibleXYPlot = makeWidthFlexible(XYPlot);

export default class OkrProgressChart extends Component {

  static propTypes = {
    progressSeries: ProgressSeriesPropTypes.isRequired,
  };

  render() {
    const { progressSeries = [] } = this.props;
    const progressData = progressSeries.map(({ datetime, achievementRate }) =>
      ({ x: new Date(datetime), y: toNumber(achievementRate) }));
    const { lastDrawLocation, hint } = this.state || {};
    return (
      <div className={styles.component}>
        <FlexibleXYPlot
          className={styles.chart}
          animation
          xDomain={lastDrawLocation && [lastDrawLocation.left, lastDrawLocation.right]}
          xType="time"
          height={330}
          margin={{ bottom: 70, left: 60, right: 20 }}
          onMouseLeave={() => this.setState({ hint: null })}
        >
          <HorizontalGridLines />
          <YAxis
            tickFormat={value => `${value}%`}
            tickSizeOuter={0}
          />
          <XAxis
            tickFormat={value => formatDate(value, { format: DateFormat.YMD })}
            tickLabelAngle={-45}
            tickTotal={8}
          />
          <LineMarkSeries
            data={progressData}
            curve={'curveMonotoneX'}
            onNearestXY={value => this.setState({ hint: value })}
          />
          {hint && (
            <Hint
              value={hint}
              format={({x, y}) => [
                { title: '登録日', value: formatDate(x) },
                { title: '達成値', value: `${y}%` },
              ]}
            />)}
          <ChartHighlightOverlay onBrushEnd={area => this.setState({ lastDrawLocation: area })} />
        </FlexibleXYPlot>
      </div>);
  }
}
