import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty } from 'lodash';
import SearchDropdown from '../../components/SearchDropdown';
import { searchGroupUsers } from './action';

const userPropType = PropTypes.shape({
  userId: PropTypes.number.isRequired,
  userName: PropTypes.string.isRequired,
});

class GroupUserSearch extends PureComponent {

  static propTypes = {
    groupId: PropTypes.number.isRequired,
    users: PropTypes.arrayOf(userPropType),
    value: PropTypes.oneOfType([userPropType, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    disabled: PropTypes.bool,
    dispatchSearchGroupUsers: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { groupId, users = [], value, onChange, onFocus, onBlur, disabled,
      dispatchSearchGroupUsers, isSearching } = this.props;
    const { currentInput = (value || {}).name } = this.state || {};
    return (
      <SearchDropdown
        items={isEmpty(currentInput) ? [] : users}
        labelPropName="userName"
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={keyword => !isEmpty(keyword) && dispatchSearchGroupUsers(groupId, keyword)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && value)}
        {...{ onFocus, onBlur, disabled }}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state) => {
  const { isSearching, data = [] } = state.groupUsersFound || {};
  return { users: data, isSearching };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSearchGroupUsers = (groupId, keyword) =>
    dispatch(searchGroupUsers(groupId, keyword));
  return { dispatchSearchGroupUsers };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(GroupUserSearch);
