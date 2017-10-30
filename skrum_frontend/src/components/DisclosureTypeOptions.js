import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import Options from './Options';
import { entityTypePropType, EntityType } from '../util/EntityUtil';

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
    entityType: entityTypePropType.isRequired,
    renderer: PropTypes.func,
    disclosureType: PropTypes.string,
    value: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
  };

  render() {
    const { entityType, renderer, disclosureType,
      value, onChange, onFocus, onBlur } = this.props;
    const map = entityType === EntityType.COMPANY ?
      disclosureTypesForCompany : disclosureTypesForUserAndGroup;
    return (
      <Options
        {...{ renderer, map, defaultValue: disclosureType }}
        {...{ value, onChange, onFocus, onBlur }}
      />
    );
  }
}

export const getDisclosureTypeName = (entityType, disclosureType) =>
  (entityType === EntityType.COMPANY ? disclosureTypesForCompany :
    disclosureTypesForUserAndGroup)[disclosureType];
