import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Dropzone from 'react-dropzone';
import { toastr } from 'react-redux-toastr';
import { fromByteArray } from 'base64-js';
import { errorType } from '../../util/PropUtil';
import { postCsv } from './action';
import styles from './InitialDataUploadContainer.css';

function SubmitButton() {
  return <button type="submit" className={styles.btn}>アップロード</button>;
}

function DisabledButton() {
  return <div className={styles.disable_btn} />;
}

class InitialDataUploadContainer extends Component {

  static propTypes = {
    userId: PropTypes.number,
    isPosting: PropTypes.bool,
    dispatchPostCsv: PropTypes.func,
    error: errorType,
  };

  componentWillReceiveProps(next) {
    this.result(next);
  }

  result(props = this.props) {
    const { isPosting, error } = props;
    const { csv } = this.state || {};
    if (!isPosting && csv) {
      if (!error) {
        toastr.info('CSV登録しました');
        this.setState({ csv: undefined });
      } else {
        toastr.error('CSV登録が正しくありません');
      }
    }
  }

  handleSubmit(csv) {
    return fetch(csv.preview)
      .then(({ body }) => body.getReader().read()
        .then(({ value }) => this.props.dispatchPostCsv(fromByteArray(value))));
  }

  renderError() {
    if (this.props.error) {
      return (
        <pre className={styles.error}>
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
    const { csv } = this.state || {};
    return (
      <form
        className={styles.container}
        onSubmit={(e) => {
          e.preventDefault();
          return this.handleSubmit(csv);
        }}
      >
        <div className={styles.title} >CSV登録（ユーザ一括登録）</div>
        <div className={styles.upload}>
          <Dropzone
            className={styles.dropzone}
            onDrop={([file]) => this.setState({ csv: file })}
            accept="text/csv"
            multiple={false}
          >
            <button type="button" className={styles.btn}>CSVファイルを選択</button>
          </Dropzone>
          <div className={styles.filename}>{csv && csv.name}</div>
          {csv && (
            <div className={`${styles.btn_area} ${styles.floatL}`}>
              {this.renderButton()}
            </div>)}
        </div>
        {this.renderError()}
        <div className={styles.note}>
          <p>本機能はCSVファイルにてユーザ情報を一括で登録し、同時に各ユーザに招待メールを自動送信する機能です。</p>
          <p>CSVファイルを作成するファイルは次の点に注意してください。</p>
          <ul>
            <li>文字コード
              <p>日本語文字コード(Shift JIS)を使用します。</p>
            </li>
            <li>ファイル拡張子
              <p>ファイル拡張子は&quot;.csv&quot;を指定してください。（ファイル名は任意のもので可）</p>
            </li>
            <li>1レコード(1行)の入力項目は以下の通りです。</li>
            <table
              cellPadding={0}
              cellSpacing={0}
              style={{
                borderCollapse: 'collapse',
                tableLayout: 'fixed',
              }}
            >
              <colgroup>
                <col width={120} span={3} />
                <col width={260} />
              </colgroup>
              <tbody>
                <tr>
                  <td>ファイル内の順序</td>
                  <td>項目名</td>
                  <td>必須項目</td>
                  <td>備考</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>通し番号</td>
                  <td>必須</td>
                  <td>行数を半角数字にて1から連番で入力。</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>名前(姓)</td>
                  <td>必須</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>名前(名)</td>
                  <td>必須</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td>ユーザ権限</td>
                  <td>必須</td>
                  <td>
                    次のいずれかの値を指定します。
                    <ul>
                      <li>一般ユーザ　　　　　：1</li>
                      <li>管理者ユーザ　　　　：2</li>
                      <li>スーパー管理者ユーザ：3</li>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <td>5</td>
                  <td>役職</td>
                  <td>必須</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>6</td>
                  <td>Eメールアドレス</td>
                  <td>必須</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>7</td>
                  <td>電話番号</td>
                  <td>任意</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>8</td>
                  <td>所属部署名1</td>
                  <td>任意</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>9</td>
                  <td>所属部署名2</td>
                  <td>任意</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>10</td>
                  <td>所属部署名3</td>
                  <td>任意</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>11</td>
                  <td>所属部署名4</td>
                  <td>任意</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>12</td>
                  <td>所属部署名5</td>
                  <td>任意</td>
                  <td>&nbsp;</td>
                </tr>
              </tbody>
            </table>
            <li>所属部署登録について
              <p className={styles.double}>&emsp;株式会社Skrum　＞　営業本部　＞　法人営業部　＞　法人営業第１課</p>
              <p>上記のような組織構造があり、ユーザが法人営業第１課に所属していた場合、</p>
              <p>所属部署名は以下のように登録してください。</p>
              <p>&emsp;・所属部署名1：&emsp;営業本部</p>
              <p>&emsp;・所属部署名2：&emsp;法人営業部</p>
              <p>&emsp;・所属部署名3：&emsp;法人営業第１課</p>
            </li>
            <li>データの各要素は&quot;(ダブルクォーテーション)で囲まないでください。</li>
            <li>
              1行に必ず12項目あることを確認してください。電話番号、所属部署名に空欄がある場合でもカンマは必要です。
              つまり1行に必ず11個のカンマが必要です。
            </li>
            <li>スーパー管理者ユーザが登録済みのものも含めて2人以内であることを確認してください。</li>
          </ul>
        </div>
      </form>);
  }
}

const mapStateToProps = (state) => {
  const { userId } = state.auth;
  const { isPosting, error } = state.initialData || {};
  return { userId, isPosting, error };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPostCsv = (userId, currentPassword, newPassword) =>
    dispatch(postCsv(userId, currentPassword, newPassword));
  return { dispatchPostCsv };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(InitialDataUploadContainer);
