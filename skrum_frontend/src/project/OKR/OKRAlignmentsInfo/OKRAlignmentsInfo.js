import React, { Component } from 'react';
import { Surface, Pie } from 'recharts';
import { AlignmentsInfoPropTypes } from './propTypes';
import styles from './OKRAlignmentsInfo.css';

const chartRadius = 60;
const widthFactor = 8;
const heightFactor = 3.5;

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
    const renderLabel = (
      // eslint-disable-next-line no-unused-vars
      { percent, cornerRadius, innerRadius, outerRadius, startAngle, endAngle, midAngle, index,
        name, ...textProps }) => (
          <text {...textProps} fill="#626A7F" fontSize="x-small">{name}</text>);
    return (
      <div className={`${styles.content} ${styles.goal_for}`}>
        <div className={`${styles.grap} ${styles.alignC}`}>
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
                <div className={styles.group}>
                  <div className={`${styles.user_info} ${styles.cf}`}>
                    <div className={`${styles.avatar} ${styles.circle} ${styles.circle_gray} ${styles.floatL}`}><img src="/img/common/icn_user.png" alt="User Name" /></div>
                    <div className={`${styles.info} ${styles.floatL}`}>
                      <p className={styles.user_name}>{name}</p>
                    </div>
                  </div>
                </div>
                <div className={styles.target_number}>{numberOfOkrs}</div>
              </div>))}
          </div>
          <div style={{ clear: 'both' }} />
        </div>
      </div>);
  }
}
