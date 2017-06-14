import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { errorType } from '../../util/PropUtil';
import styles from './CompanyProfileContainer.css';
import InlineTextInput from '../../editors/InlineTextInput';
import InlineTextArea from '../../editors/InlineTextArea';
import { fetchCompany, putCompany } from './action';

function SubmitButton() {
  return <button className={styles.btn}>画像アップロード</button>;
}

function DisabledButton() {
  return <div className={styles.disable_btn} />;
}

class CompanyProfileContainer extends Component {

  static propTypes = {
    companyId: PropTypes.number,
    name: PropTypes.string,
    vision: PropTypes.string,
    mission: PropTypes.string,
    isFetching: PropTypes.bool,
    isPutting: PropTypes.bool,
    isPosting: PropTypes.bool,
    dispatchFetchCompany: PropTypes.func,
    dispatchPutCompany: PropTypes.func,
    error: errorType,
  };

  componentWillMount() {
    const { dispatchFetchCompany, companyId } = this.props;
    dispatchFetchCompany(companyId);
  }

  renderError() {
    if (this.props.error) {
      return (<pre>
        <p>エラーが発生しました</p>
        <br />
        {this.props.error.message}
      </pre>);
    }
  }

  renderButton() {
    return this.props.isPosting ? <DisabledButton /> : <SubmitButton />;
  }

  render() {
    const { companyId, name, vision, mission, dispatchPutCompany, isFetching } = this.props;
    if (isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.title}>会社情報設定</div>
        <div className={styles.company_img}>
          <span><img className={styles.img} src="/img/profile/img_leader.jpg" alt="" /></span>
          <span>{this.renderButton()}</span>
        </div>
        <div>
          <div className={styles.packet}>
            <span className={styles.title}>会社名：</span>
            <span className={styles.data}>
              <InlineTextInput
                value={name}
                onSubmit={value => dispatchPutCompany(companyId, { name: value })}
              />
            </span>
          </div>
          <div className={styles.packet}>
            <span className={styles.title}>ヴィジョン：</span>
            <span className={styles.data}>
              <InlineTextArea
                value={vision}
                onSubmit={value => dispatchPutCompany(companyId, { vision: value })}
              />
            </span>
          </div>
          <div className={styles.packet}>
            <span className={styles.title}>ミッション：</span>
            <span className={styles.data}>
              <InlineTextArea
                value={mission}
                onSubmit={value => dispatchPutCompany(companyId, { mission: value })}
              />
            </span>
          </div>
          <div className={styles.packet}>
            <div className={styles.title}>{this.renderError()}</div>
          </div>
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { companyId } = state.auth || {};
  const { isFetching = false, isPutting = false } = state.companySetting || {};
  const { name, vision, mission } = state.companySetting.data || {};
  return { companyId, name, vision, mission, isFetching, isPutting };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchCompany = companyId => dispatch(fetchCompany(companyId));
  const dispatchPutCompany = (companyId, name, vision, mission) =>
    dispatch(putCompany(companyId, name, vision, mission));
  return { dispatchFetchCompany, dispatchPutCompany };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(CompanyProfileContainer);
