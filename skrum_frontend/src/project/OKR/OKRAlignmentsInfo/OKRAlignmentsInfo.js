import React, { Component } from 'react';
import { Surface, Pie } from 'recharts';
import { AlignmentsInfoPropTypes } from './propTypes';
import styles from './OKRAlignmentsInfo.css';

const nameColumnWidth = 16;
const countColumnWidth = 4;
const chartRadius = 60;
const widthFactor = 8;
const heightFactor = 3.5;

const colStyle = {
  name: {
    minWidth: `${nameColumnWidth}em`,
  },
  count: {
    minWidth: `${countColumnWidth}em`,
    textAlign: 'right',
  },
};

export default class OKRAlignmentsInfo extends Component {

  static propTypes = {
    alignments: AlignmentsInfoPropTypes.isRequired,
  };

  render() {
    const { alignments = [] } = this.props;
    const details = alignments.map(({ group, company }) => group || company);
    const fills = ['#626A7E', '#D8DFE5', '#F0F4F5', 'gray', 'silver'];
    const data = details.map(({ name, numberOfOkrs: value }, index) => ({
      name,
      value,
      fill: fills[index],
    }));
    const renderLabel = props => (
      <text {...props} fill="#626A7F" fontSize="x-small">
        {props.name}
      </text>);
    return (
      <div className={styles.component}>
        <div className={styles.header}>
          <div style={{ width: chartRadius * widthFactor }} />
          <div style={{ ...colStyle.name }}>紐付け先</div>
          <div style={{ ...colStyle.count }}>目標数</div>
        </div>
        <div className={styles.content}>
          <Surface
            className={styles.okrAlignChart}
            width={chartRadius * widthFactor}
            height={chartRadius * heightFactor}
          >
            <Pie
              isAnimationActive={false}
              startAngle={90}
              endAngle={-270}
              cx={chartRadius * (widthFactor / 2)}
              cy={chartRadius * (heightFactor / 2)}
              outerRadius={chartRadius}
              innerRadius={chartRadius * 0.88}
              data={data}
              paddingAngle={0}
              dataKey="name"
              label={renderLabel}
            />
          </Surface>
          <div className={styles.list}>
            {details.map(({ companyId, groupId, name, numberOfOkrs }) => (
              <div key={`${companyId}${groupId}`} className={styles.detail}>
                <div className={styles.nameBox} style={colStyle.name}>
                  <div className={styles.nameImage} />
                  <div className={styles.name}>{name}</div>
                </div>
                <div className={styles.count} style={colStyle.count}>{numberOfOkrs}</div>
              </div>))}
          </div>
          <div style={{ clear: 'both' }} />
        </div>
      </div>);
  }
}
