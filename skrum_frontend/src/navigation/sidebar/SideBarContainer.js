import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import { sectionPropTypes, sectionsPropTypes } from './propTypes';
import SideBar from './SideBar';
import { fetchUserBasics } from '../action';

class SideBarContainer extends Component {

  static propTypes = {
    userSection: sectionPropTypes,
    groupSections: sectionsPropTypes,
    companyId: PropTypes.number,
    companyName: PropTypes.string,
    dispatchLoadEntity: PropTypes.func,
    pathname: PropTypes.string,
  };

  state = {
    isOpen: true,
  };

  toggleSideBar() {
    this.setState({ isOpen: !this.state.isOpen });
  }

  render() {
    const { userSection, groupSections, companyId, companyName, dispatchLoadEntity } = this.props;
    return (
      <SideBar
        isOpen={this.state.isOpen}
        onClickToggle={this.toggleSideBar}
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
