import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import { sections } from './propTypes';
import SideBar from './SideBar';

class SideBarContainer extends Component {

  static propTypes = {
    userName: PropTypes.string,
    companyName: PropTypes.string,
    sections,
  };

  static defaultProps = {
    isOpen: true,
  };

  state = {
    isOpen: true,
  };

  toggleSideBar() {
    this.setState({ isOpen: !this.state.isOpen });
  }

  render() {
    return (
      <SideBar
        isOpen={this.state.isOpen}
        onClickToggle={this.toggleSideBar}
        userName={this.props.userName}
        companyName={this.props.companyName}
        sections={this.props.sections}
      />);
  }
}

const mapStateToProps = (state) => {
  const { teams = [], departments = [], user = {} } = state.user.data || {};
  const teamItems = teams.map((team) => {
    const { group_id, group_name } = team;
    return {
      title: group_name,
      id: group_id,
    };
  });
  const teamSection = {
    title: 'チーム',
    items: teamItems,
  };
  const depItems = departments.map((dep) => {
    const { group_id, group_name } = dep;
    return {
      title: group_name,
      id: group_id,
    };
  });
  const depSection = {
    title: 'グループ',
    items: depItems,
  };

  return {
    userName: user.name,
    companyName: '株式会社◯◯◯◯',
    sections: [teamSection, depSection],
  };
};

const mapDispatchToProps = () => {
  const handleSubmit = () => console.log('hogehoge');
  return { handleSubmit };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(SideBarContainer);
