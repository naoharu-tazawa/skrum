import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import UserInfoContainer from './UserInfo/UserInfoContainer';
import GroupInfoContainer from './GroupInfo/GroupInfoContainer';
import CompanyInfoContainer from './CompanyInfo/CompanyInfoContainer';
import { UserOKRListContainer, GroupOKRListContainer, CompanyOKRListContainer } from './OKRList/OKRListContainer';
import styles from './OKRContainer.css';

class OKRContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    subject: PropTypes.string,
  };

  renderInfoContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserInfoContainer />;
      case 'group':
        return <GroupInfoContainer />;
      case 'company':
        return <CompanyInfoContainer />;
      default:
        return null;
    }
  }

  renderOKRListContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserOKRListContainer />;
      case 'group':
        return <GroupOKRListContainer />;
      case 'company':
        return <CompanyOKRListContainer />;
      default:
        return null;
    }
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.userInfo}>
          {this.renderInfoContainer()}
        </div>
        <div className={styles.okrList}>
          {this.renderOKRListContainer()}
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching = false } = state.basics || {};
  return { isFetching };
};

export default connect(
  mapStateToProps,
)(OKRContainer);
