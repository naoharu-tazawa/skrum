import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import { sectionPropTypes, sectionsPropTypes } from './propTypes';
import SideBar from './SideBar';
import { fetchUserBasics } from '../action';

class SideBarContainer extends Component {

  static propTypes = {
    top: PropTypes.string.isRequired,
    pathname: PropTypes.string.isRequired,
    currentUserId: PropTypes.number.isRequired,
    roleLevel: PropTypes.number.isRequired,
    userSection: sectionPropTypes,
    groupSections: sectionsPropTypes,
    companyId: PropTypes.number,
    companyName: PropTypes.string,
    dispatchLoadEntity: PropTypes.func,
  };

  render() {
    const { top, pathname, roleLevel, userSection, groupSections, companyId, companyName,
      dispatchLoadEntity } = this.props;
    return (
      <SideBar
        top={top}
        pathname={pathname}
        roleLevel={roleLevel}
        handleClick={dispatchLoadEntity}
        {...{ userSection, groupSections, companyId, companyName }}
      />);
  }
}

const mapStateToProps = (state) => {
  const { users = [], teams = [], departments = [], company = {} } = state.top.data || {};
  const userSection = {
    title: '',
    items: users.map(({ userId: id, name: title }) => ({ id, title })),
  };
  const teamSection = {
    title: 'チーム',
    items: teams.map(({ groupId: id, groupName: title }) => ({ id, title })),
  };
  const depSection = {
    title: '部署',
    items: departments.map(({ groupId: id, groupName: title }) => ({ id, title })),
  };
  return {
    userSection,
    groupSections: [teamSection, depSection],
    companyId: company.companyId,
    companyName: company.name,
  };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchLoadEntity = id =>
    dispatch(fetchUserBasics(id));
  return { dispatchLoadEntity };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(SideBarContainer);
