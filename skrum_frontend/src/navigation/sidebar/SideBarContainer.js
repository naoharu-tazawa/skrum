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
  const { teams = [], departments = [], user = {}, company = {} } = state.user.data || {};
  const teamSection = {
    title: 'チーム',
    items: teams.map(({ groupId: id, groupName: title }) => ({ id, title })),
  };
  const depSection = {
    title: 'グループ',
    items: departments.map(({ groupId: id, groupName: title }) => ({ id, title })),
  };

  return {
    userName: user.name,
    companyName: company.name,
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
