import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty } from 'lodash';
import SearchDropdown from '../../components/SearchDropdown';
import { searchUserGroups } from './action';

const userGroupPropType = PropTypes.shape({
  groupId: PropTypes.number.isRequired,
  groupName: PropTypes.string.isRequired,
});

class UserGroupSearch extends PureComponent {

  static propTypes = {
    userId: PropTypes.number.isRequired,
    groups: PropTypes.arrayOf(userGroupPropType),
    value: PropTypes.oneOfType([userGroupPropType, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    disabled: PropTypes.bool,
    dispatchSearchUserGroups: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { userId, groups = [], value, onChange, onFocus, onBlur, disabled,
      dispatchSearchUserGroups, isSearching } = this.props;
    const { currentInput = (value || {}).name } = this.state || {};
    return (
      <SearchDropdown
        items={isEmpty(currentInput) ? [] : groups}
        labelPropName="groupName"
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={keyword => !isEmpty(keyword) && dispatchSearchUserGroups(userId, keyword)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && value)}
        {...{ onFocus, onBlur, disabled }}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state) => {
  const { isSearching, data = [] } = state.userGroupsFound || {};
  return { groups: data, isSearching };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSearchUserGroups = (userId, keyword) =>
    dispatch(searchUserGroups(userId, keyword));
  return { dispatchSearchUserGroups };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserGroupSearch);
