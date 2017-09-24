import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty, find } from 'lodash';
import SearchDropdown from '../../editors/SearchDropdown';
import EntitySubject from '../../components/EntitySubject';
import { entityPropTypes, EntityType } from '../../util/EntityUtil';
import { GroupType } from '../../util/GroupUtil';
import { mapOwner } from '../../util/OwnerUtil';
import { searchOwner } from './action';

class OwnerSearch extends PureComponent {

  static propTypes = {
    defaultOwners: PropTypes.arrayOf(entityPropTypes),
    keyword: PropTypes.string,
    exclude: PropTypes.arrayOf(entityPropTypes),
    ownersFound: PropTypes.arrayOf(entityPropTypes),
    value: PropTypes.oneOfType([entityPropTypes, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func.isRequired,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    dispatchSearchOwner: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { defaultOwners, keyword, ownersFound, value, onChange, onFocus, onBlur, tabIndex,
      dispatchSearchOwner, isSearching } = this.props;
    const currentName = (value || {}).name; // || (find(defaultOwners, value) || {}).name
    const { currentInput = currentName || '' } = this.state || {};
    return (
      <SearchDropdown
        items={(keyword !== currentInput ? defaultOwners : ownersFound) || []}
        labelPropName="name"
        renderItem={owner => <EntitySubject entity={owner} local plain avatarSize={20} />}
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={q => !isEmpty(q) && dispatchSearchOwner(q)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && { value: { name: currentName, ...value } })}
        {...{ onFocus, onBlur }}
        tabIndex={`${tabIndex}`}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state, { exclude = [] }) => {
  const { users = [], teams = [], departments: depts = [], company = {} } = state.top.data || {};
  const userType = { type: EntityType.USER };
  const teamType = { type: EntityType.GROUP, groupType: GroupType.TEAM };
  const deptType = { type: EntityType.GROUP, groupType: GroupType.DEPARTMENT };
  const defaultOwners = [
    ...users.map(({ userId: id, name, roleLevel }) => ({ id, name, ...userType, roleLevel })),
    ...teams.map(({ groupId: id, groupName: name }) => ({ id, name, ...teamType })),
    ...depts.map(({ groupId: id, groupName: name }) => ({ id, name, ...deptType })),
    ...[{ id: company.companyId, name: company.name, type: EntityType.COMPANY }],
  ].filter(({ type, id }) => !find(exclude, { type, id }));
  const { isSearching, keyword, data = [] } = state.ownersFound || {};
  const ownersFound = data.map(mapOwner).filter(({ type, id }) => !find(exclude, { type, id }));
  return { defaultOwners, keyword, ownersFound, isSearching };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSearchOwner = keyword => dispatch(searchOwner(keyword));
  return { dispatchSearchOwner };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(OwnerSearch);
