import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Select from 'react-select';
import { rolesPropTypes } from './propTypes';
import { fetchCompanyRoles } from './action';
import styles from './UserRolesDropdown.css';

class UserRolesDropdown extends PureComponent {

  static propTypes = {
    companyId: PropTypes.number.isRequired,
    roles: rolesPropTypes.isRequired,
    className: PropTypes.string,
    value: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    dispatchFetchCompanyRoles: PropTypes.func.isRequired,
  };

  componentWillReceiveProps(next) {
    const { companyId, roles = [] } = next;
    if (!roles.length) next.dispatchFetchCompanyRoles(companyId);
  }

  render() {
    const { roles, value: dirtyValue, className, onChange, onFocus, onBlur } = this.props;
    const options = roles.map(({ id: value, name: label }) => ({ value, label }));
    const getOptionStyle = id => `${styles.item} ${id === dirtyValue ? styles.current : ''}`;
    const optionRenderer = ({ value: id, label }) => (
      <div className={getOptionStyle(id)}>
        {label}
      </div>);
    return (
      <Select
        className={`${styles.select} ${className}`}
        options={options}
        optionRenderer={optionRenderer}
        value={dirtyValue}
        {...{ onChange, onFocus, onBlur }}
        placeholder="権限を選択してください"
        clearable={false}
        searchable={false}
      />
    );
  }
}

const mapStateToProps = (state) => {
  const { companyId } = state.auth || {};
  const { roles: items = [] } = state.userSetting || {};
  const roles = items.map(({ roleAssignmentId, roleName }) => ({
    id: roleAssignmentId,
    name: roleName,
  }));
  return { companyId, roles };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchCompanyRoles = companyId =>
    dispatch(fetchCompanyRoles(companyId));
  return {
    dispatchFetchCompanyRoles,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserRolesDropdown);
