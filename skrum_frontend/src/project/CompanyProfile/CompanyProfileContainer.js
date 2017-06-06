import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { errorType } from '../../util/PropUtil';
import styles from './CompanyProfileContainer.css';
import { fetchCompany, putCompany } from './action';

function SubmitButton() {
  return <button className={styles.btn}>変更する</button>;
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
    isProcessing: PropTypes.bool,
    dispatchFetchCompany: PropTypes.func,
    dispatchPutCompany: PropTypes.func,
    error: errorType,
  };

  componentWillMount() {
    const { dispatchFetchCompany, companyId } = this.props;
    dispatchFetchCompany(companyId);
  }

  componentWillReceiveProps() {
  }

  handleSubmit(e) {
    const target = e.target;
    e.preventDefault();
    this.props.dispatchPutCompany(
      this.props.companyId,
      target.name.value.trim(),
      target.vision.value.trim(),
      target.mission.value.trim(),
    );
  }

  changeText(e) {
    // This is incorrect code. should be revised.
    const target = e.target;
    e.preventDefault();
    this.setState({ companySetting: { data:
    { name: target.name.value.trim(),
      vision: target.vision.value.trim(),
      mission: target.mission.value.trim(),
    } } });
    this.props.dispatchPutCompany(
      this.props.companyId,
      target.name.value.trim(),
      target.vision.value.trim(),
      target.mission.value.trim(),
    );
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
    return this.props.isProcessing ? <DisabledButton /> : <SubmitButton />;
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.title}>会社情報設定</div>
        <div className={styles.company_img}>
          <span><img className={styles.img} src="/img/profile/img_leader.jpg" alt="" /></span>
          <span><button className={styles.btn}>画像アップロード</button></span>
        </div>
        <form onSubmit={e => this.handleSubmit(e)}>
          <table className={styles.floatL}>
            <tbody>
              <tr>
                <td colSpan="2"><div className={styles.td}>会社名</div></td>
              </tr>
              <tr>
                <td className={styles.title} colSpan="2">
                  <input id="companyName" type="text" ref={input => (this.name = input)} value={this.props.name} onChange={this.changeText} />
                </td>
              </tr>
              <tr>
                <td className={styles.top}>
                  <div className={styles.td}>ヴィジョン：</div>
                </td>
                <td>
                  <textarea className={styles.textarea} id="vision" type="password" ref={input => (this.vision = input)} value={this.props.vision} onChange={this.changeText} />
                </td>
              </tr>
              <tr>
                <td className={styles.top}>
                  <div className={styles.td}>ミッション：</div>
                </td>
                <td>
                  <textarea className={styles.textarea} id="mission" type="password" ref={input => (this.mission = input)} value={this.props.mission} onChange={this.changeText} />
                </td>
              </tr>
              <tr>
                <td colSpan="2">
                  <div className={styles.td}>{this.renderError()}</div>
                </td>
              </tr>
            </tbody>
          </table>
          <div className={`${styles.btn_area} ${styles.floatL}`}>{this.renderButton()}</div>
        </form>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { companyId } = state.auth || {};
  const { isFetching = false, isProcessing = false } = state.companySetting || {};
  const { name, vision, mission } = state.companySetting.data || {};
  return { companyId, name, vision, mission, isFetching, isProcessing };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchCompany = companyId => dispatch(fetchCompany(companyId));
  const dispatchPutCompany = (companyId, companyName, vision, mission) =>
    dispatch(putCompany(companyId, companyName, vision, mission));
  return { dispatchFetchCompany, dispatchPutCompany };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(CompanyProfileContainer);
