import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import InlineEntityImagePicker from '../../components/InlineEntityImagePicker';
import InlineTextInput from '../../editors/InlineTextInput';
import InlineTextArea from '../../editors/InlineTextArea';
import { fetchCompany, putCompany, postCompanyImage } from './action';
import { EntityType } from '../../util/EntityUtil';
import { loadImageSrc } from '../../util/ImageUtil';
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
    const { isFetching, companyId, name, vision, mission,
      dispatchPostCompanyImage, dispatchPutCompany } = this.props;
    if (isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.heading}>会社情報設定</div>
        <div className={styles.company_img}>
          <InlineEntityImagePicker
            entity={{ id: companyId, type: EntityType.COMPANY }}
            onSubmit={file => loadImageSrc(file).then(({ image, mimeType }) =>
              dispatchPostCompanyImage(companyId, image, mimeType))}
          />
        </div>
        <div>
          <div className={styles.packet}>
            <div className={styles.title}>会社名：</div>
            <div className={styles.data}>
              <InlineTextInput
                value={name}
                required
                onSubmit={value => dispatchPutCompany(companyId, { name: value })}
              />
            </div>
          </div>
          <div className={styles.packet}>
            <div className={styles.title}>ヴィジョン：</div>
            <div className={styles.data}>
              <InlineTextArea
                value={vision}
                placeholder="ヴィジョンを入力してください"
                maxLength={250}
                onSubmit={value => dispatchPutCompany(companyId, { vision: value })}
              />
            </div>
          </div>
          <div className={styles.packet}>
            <div className={styles.title}>ミッション：</div>
            <div className={styles.data}>
              <InlineTextArea
                value={mission}
                placeholder="ミッションを入力してください"
                maxLength={250}
                onSubmit={value => dispatchPutCompany(companyId, { mission: value })}
              />
            </div>
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
  const dispatchPostCompanyImage = (companyId, image, mimeType) =>
    dispatch(postCompanyImage(companyId, image, mimeType));
  return { dispatchFetchCompany, dispatchPutCompany, dispatchPostCompanyImage };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(CompanyProfileContainer);
