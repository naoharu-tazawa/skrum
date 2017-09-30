import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Surface, Pie } from 'recharts';
import { AlignmentsInfoPropTypes } from './propTypes';
import EntityLink from '../../../components/EntityLink';
import styles from './OKRAlignmentsInfo.css';

const chartRadius = 60;
const widthFactor = 8;
const heightFactor = 3.5;
const lineInset = 0.82; // fraction

export default class OKRAlignmentsInfo extends Component {

  static propTypes = {
    display: PropTypes.oneOf(['header', 'normal']),
    alignments: AlignmentsInfoPropTypes,
  };

  static defaultProps = {
    display: 'normal',
  };

  render() {
    const { display, alignments = [] } = this.props;
    if (display === 'header') {
      return (
        <div className={styles.header}>
          <div>
            <div className={styles.chart} />
            <div className={styles.group}>紐付け先</div>
            <div className={styles.numberOfOkrs}>目標数</div>
          </div>
        </div>
      );
    }
    const details = alignments.map(({ ownerType, user, group, company }) =>
      ({ ownerType, ...(user || group || company) }));
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
      <div className={styles.content}>
        <div>
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
              innerRadius={chartRadius * lineInset}
              data={data}
              paddingAngle={0}
              dataKey="name"
              label={renderLabel}
            />
          </Surface>
          <div className={styles.list}>
            {details.map(({ ownerType, name, userId, groupId, companyId, numberOfOkrs }, index) => (
              <div key={index} className={styles.detail}>
                <EntityLink
                  className={styles.group}
                  entity={{ id: userId || groupId || companyId, name, type: ownerType }}
                />
                <div className={styles.numberOfOkrs}>{numberOfOkrs}</div>
              </div>))}
          </div>
        </div>
      </div>);
  }
}
