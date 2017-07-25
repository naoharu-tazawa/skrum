import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { toPairs } from 'lodash';
import Select from 'react-select';
import styles from './DisclosureTypeOptions.css';

export const disclosureTypesForUserAndGroup = {
  1: '全体',
  2: 'グループ',
  3: '管理者',
  4: 'グループ管理者',
};

export const disclosureTypesForCompany = {
  1: '全体',
  3: '管理者',
};

export default class DisclosureTypeOptions extends PureComponent {

  static propTypes = {
    ownerType: PropTypes.oneOf(['1', '2', '3']).isRequired,
    renderer: PropTypes.func,
    disclosureType: PropTypes.string,
    value: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
  };

  render() {
    const getOptionStyle = (id, currentId) =>
      `${styles.item} ${id === currentId ? styles.current : ''}`;
    const { ownerType, renderer, disclosureType,
      value: dirtyValue, onChange, onFocus, onBlur } = this.props;
    const options = toPairs(ownerType === '3' ? disclosureTypesForCompany : disclosureTypesForUserAndGroup)
      .map(([id, label]) => ({ value: `${id}`, label }));
    if (renderer) {
      return <div className={styles.component}>{options.map(renderer)}</div>;
    }
    return (
      <Select
        className={styles.select}
        options={options}
        optionRenderer={({ value: id, label }) =>
          <div className={getOptionStyle(id, disclosureType)}>{label}</div>}
        value={dirtyValue || disclosureType}
        {...{ onChange, onFocus, onBlur }}
        placeholder=""
        clearable={false}
        searchable={false}
      />);
  }
}

export const getDisclosureTypeName = (ownerType, disclosureType) =>
  (ownerType === '3' ? disclosureTypesForCompany : disclosureTypesForUserAndGroup)[disclosureType];
