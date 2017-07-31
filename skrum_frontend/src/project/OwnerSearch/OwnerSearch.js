import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty } from 'lodash';
import SearchDropdown from '../../components/SearchDropdown';
import { EntityType } from '../../util/EntityUtil';
import { mapOwner } from '../../util/OwnerUtil';
import { searchOwner } from './action';

export const ownerPropType = PropTypes.shape({
  id: PropTypes.number, // fixme .isRequired,
  name: PropTypes.string,
  type: PropTypes.string, // fixme .isRequired,
});

class OwnerSearch extends PureComponent {

  static propTypes = {
    defaultOwners: PropTypes.arrayOf(ownerPropType),
    keyword: PropTypes.string,
    ownersFound: PropTypes.arrayOf(ownerPropType),
    value: PropTypes.oneOfType([ownerPropType, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func.isRequired,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    dispatchSearchOwner: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { defaultOwners, keyword, ownersFound, value, onChange, onFocus, onBlur,
      dispatchSearchOwner, isSearching } = this.props;
    const currentName = (value || {}).name; // || (find(defaultOwners, value) || {}).name
    const { currentInput = currentName || '' } = this.state || {};
    return (
      <SearchDropdown
        items={(keyword !== currentInput ? defaultOwners : ownersFound) || []}
        labelPropName="name"
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={q => !isEmpty(q) && dispatchSearchOwner(q)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && { value: { name: currentName, ...value } })}
        {...{ onFocus, onBlur }}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state) => {
  const { users = [], teams = [], departments: depts = [], company = {} } = state.top.data || {};
  const defaultOwners = [
    ...users.map(({ userId: id, name }) => ({ id, name, type: EntityType.USER })),
    ...teams.map(({ groupId: id, groupName: name }) => ({ id, name, type: EntityType.GROUP })),
    ...depts.map(({ groupId: id, groupName: name }) => ({ id, name, type: EntityType.GROUP })),
    ...[{ id: company.companyId, name: company.name, type: EntityType.COMPANY }],
  ];
  const { isSearching, keyword, data = [] } = state.ownersFound || {};
  return { defaultOwners, keyword, ownersFound: data.map(mapOwner), isSearching };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSearchOwner = keyword => dispatch(searchOwner(keyword));
  return { dispatchSearchOwner };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(OwnerSearch);
