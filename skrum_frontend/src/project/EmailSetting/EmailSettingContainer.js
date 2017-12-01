import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { fetchEmailSettings, changeEmailSettings } from './action';
import EmailSetting from './EmailSetting';
import styles from './EmailSettingContainer.css';

class EmailSettingContainer extends Component {

  static propTypes = {
    userId: PropTypes.number.isRequired,
    isFetching: PropTypes.bool.isRequired,
    isPutting: PropTypes.bool.isRequired,
    data: PropTypes.shape({}),
    dispatchFetchEmailSettings: PropTypes.func.isRequired,
    dispatchChangeEmailSettings: PropTypes.func.isRequired,
  };

  componentWillMount() {
    const { dispatchFetchEmailSettings, userId } = this.props;
    dispatchFetchEmailSettings(userId);
  }

  render() {
    const { isFetching, isPutting, userId, data, dispatchChangeEmailSettings } = this.props;
    if (isFetching) {
      return <span className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.title}>メール配信設定</div>
        <div>
          <EmailSetting {...{ userId, data, isPutting, dispatchChangeEmailSettings }} />
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { userId } = state.auth || {};
  const { isFetching, isPutting, data } = state.emailSetting || {};
  return { userId, isFetching, isPutting, data };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchEmailSettings = userId =>
    dispatch(fetchEmailSettings(userId));
  const dispatchChangeEmailSettings = (userId, settings) =>
    dispatch(changeEmailSettings(userId, settings));
  return { dispatchFetchEmailSettings, dispatchChangeEmailSettings };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(EmailSettingContainer);
