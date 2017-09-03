import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { join } from 'lodash';
import ProgressPercentage from '../../../components/ProgressPercentage';
import EntityLink from '../../../components/EntityLink';
import DropdownMenu from '../../../components/DropdownMenu';
import { replacePath } from '../../../util/RouteUtil';
import { keyResultPropTypes } from './propTypes';
import styles from './KRBar.css';

export default class KRBar extends Component {

  static propTypes = {
    display: PropTypes.oneOf(['expanded', 'collapsed']).isRequired,
    keyResult: keyResultPropTypes.isRequired,
    onAddParentedOkr: PropTypes.func.isRequired,
  };

  getBaseStyles = (display) => {
    const baseStyles = [
      styles.component,
      ...[display === 'collapsed' ? [styles.collapsed] : []],
    ];
    return join(baseStyles, ' ');
  };

  render() {
    const { display, keyResult, onAddParentedOkr } = this.props;
    const { id, name, unit, targetValue, achievedValue, achievementRate, owner } = keyResult;
    return (
      <div className={this.getBaseStyles(display)}>
        <div className={styles.name}>
          {name}
        </div>
        <ProgressPercentage
          componentClassName={styles.progressColumn}
          {...{ unit, targetValue, achievedValue, achievementRate }}
        />
        <EntityLink componentClassName={styles.ownerBox} entity={owner} />
        <div className={styles.action}>
          <Link
            className={styles.circle}
            to={replacePath({ tab: 'map', aspect: 'o', aspectId: id })}
          >
            <img src="/img/common/inc_organization.png" alt="Map" />
          </Link>
          <DropdownMenu
            options={[
              { caption: 'この目標に紐付ける', onClick: () => onAddParentedOkr(keyResult) },
            ]}
          />
        </div>
      </div>);
  }
}
