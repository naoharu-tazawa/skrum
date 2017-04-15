import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import { sections } from './propTypes';
import SideBar from './SideBar';

class SideBarContainer extends Component {

  static propTypes = {
    userName: PropTypes.string.isRequired,
    companyName: PropTypes.string.isRequired,
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

const mapStateToProps = () => {
  return {
    userName: 'ほげほげ',
    companyName: '株式会社piyo',
    sections: [],
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
