import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty, find } from 'lodash';
import SearchDropdown from '../../editors/SearchDropdown';
import { withMultiSelect } from '../../editors/MultiSelect';
import EntitySubject from '../../components/EntitySubject';
import { entityPropTypes, EntityType } from '../../util/EntityUtil';
import { searchUser } from './action';

const labelPropName = 'name';

const UserSearch = props => class extends PureComponent {

  static propTypes = {
    defaultUsers: PropTypes.arrayOf(entityPropTypes),
    keyword: PropTypes.string,
    exclude: PropTypes.arrayOf(PropTypes.number),
    usersFound: PropTypes.arrayOf(entityPropTypes),
    value: PropTypes.oneOfType([entityPropTypes, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func.isRequired,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    dispatchSearchUser: PropTypes.func,
    isSearching: PropTypes.bool,
    inputStyle: PropTypes.shape({}),
  };

  render() {
    const { defaultUsers, keyword, usersFound, value, onChange, onFocus, onBlur, tabIndex,
      dispatchSearchUser, isSearching, inputStyle } = this.props;
    const currentName = (value || {}).name; // || (find(defaultUsers, value) || {}).name
    const { currentInput = currentName || '' } = this.state || {};
    return (
      <SearchDropdown
        {...props}
        items={(keyword !== currentInput ? defaultUsers : usersFound) || []}
        labelPropName={labelPropName}
        renderItem={user => <EntitySubject entity={user} local plain avatarSize={20} />}
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={q => !isEmpty(q) && dispatchSearchUser(q)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && { value: { name: currentName, ...value } })}
        {...{ onFocus, onBlur }}
        tabIndex={`${tabIndex}`}
        isSearching={isSearching}
        inputStyle={inputStyle}
      />
    );
  }
};

const mapStateToProps = (state, { exclude = [] }) => {
  const defaultUsers = [];
  const { isSearching, keyword, data = [] } = state.usersFound || {};
  const mapUser = ({ userId, userName }) => ({ type: EntityType.USER, id: userId, name: userName });
  const usersFound = data.map(mapUser).filter(({ id }) => !find(exclude, { id }));
  return { defaultUsers, keyword, usersFound, isSearching };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSearchUser = keyword => dispatch(searchUser(keyword));
  return { dispatchSearchUser };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserSearch());

export const MultiUserSearch = withMultiSelect(connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserSearch({ clearOnSelect: true })), { labelPropName, stripSpace: true });
