import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import UserInfoContainer from '../UserInfo/UserInfoContainer';
import { UserOKRListContainer, GroupOKRListContainer, CompanyOKRListContainer } from '../OKRList/OKRListContainer';
import styles from './OKRContainer.css';
import { imgSrc } from '../../util/ResourceUtil';

const spinnerStyle = {
  height: '100%',
  width: '100%',
  background: `url("${imgSrc('./rolling.svg')}") center no-repeat`,
};

class OKRContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    subject: PropTypes.string,
  };

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
      return <div style={spinnerStyle} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.userInfo}>
          <UserInfoContainer />
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
