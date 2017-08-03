import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Dropzone from 'react-dropzone';
import EntityLink, { EntityType } from '../../components/EntityLink';
import InlineTextInput from '../../editors/InlineTextInput';
import InlineTextArea from '../../editors/InlineTextArea';
import { fetchCompany, putCompany, postCompanyImage } from './action';
import { errorType } from '../../util/PropUtil';
import styles from './CompanyProfileContainer.css';

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
    dispatchPostCompanyImage: PropTypes.func,
    error: errorType,
  };

  componentWillMount() {
    const { dispatchFetchCompany, companyId } = this.props;
    dispatchFetchCompany(companyId);
  }

  uploadPicture = (file) => {
    const { companyId, dispatchPostCompanyImage } = this.props;
    const data = new FormData();
    data.append('image', file);
    return dispatchPostCompanyImage(companyId, data);
  };

  renderError() {
    if (this.props.error) {
      return (<pre>
        <p>エラーが発生しました</p>
        <br />
        {this.props.error.message}
      </pre>);
    }
  }

  render() {
    const { companyId, name, vision, mission, dispatchPutCompany, isFetching } = this.props;
    const { file } = this.state || {};
    if (isFetching) {
      return <span className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.title}>会社情報設定</div>
        <div className={styles.company_img}>
          <Dropzone
            onDrop={files => this.setState({ file: files[0] }, () => console.log(files[0]))}
            accept="image/*"
            multiple={false}
          >
            {file ?
              <img src={file.preview} className={styles.img} alt="" /> :
              <EntityLink
                entity={{ id: companyId, type: EntityType.COMPANY }}
                local
                fluid
                avatarSize="180px"
                avatarOnly
              />}
            <button className={styles.btn}>画像選択</button>
          </Dropzone>
        </div>
        {file && (
          <button className={styles.btn} onClick={() => this.uploadPicture(file)}>
            画像アップロード
          </button>)}
        <div>
          <div className={styles.packet}>
            <span className={styles.title}>会社名：</span>
            <span className={styles.data}>
              <InlineTextInput
                value={name}
                required
                onSubmit={value => dispatchPutCompany(companyId, { name: value })}
              />
            </span>
          </div>
          <div className={styles.packet}>
            <span className={styles.title}>ヴィジョン：</span>
            <span className={styles.data}>
              <InlineTextArea
                value={vision}
                placeholder="ヴィジョンを入力してください"
                maxLength={250}
                onSubmit={value => dispatchPutCompany(companyId, { vision: value })}
              />
            </span>
          </div>
          <div className={styles.packet}>
            <span className={styles.title}>ミッション：</span>
            <span className={styles.data}>
              <InlineTextArea
                value={mission}
                placeholder="ミッションを入力してください"
                maxLength={250}
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
  const dispatchPutCompany = (companyId, data) =>
    dispatch(putCompany(companyId, data));
  const dispatchPostCompanyImage = (companyId, data) =>
    dispatch(postCompanyImage(companyId, data));
  return { dispatchFetchCompany, dispatchPutCompany, dispatchPostCompanyImage };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(CompanyProfileContainer);
