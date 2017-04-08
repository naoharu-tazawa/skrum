import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';

const gap = '1em';
const style = {
  okrNav: {
    display: 'block',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
    padding: `${gap} ${gap}`,
  },
  isOpen: {
    height: '260px',
    width: '680px',
  },
  dialogTitle: {
    position: 'relative',
    width: '100%',
    fontSize: 'large',
    fontWeight: 'bold',
  },
  dialogSubTitle: {
    fontSize: 'small',
    paddingBottom: gap,
  },
  isHide: {
    display: 'none',
  },
  userImage: {
    display: 'block',
    minWidth: '36px',
    minHeight: '36px',
    margin: 'auto',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  userBox: {
    display: 'flex',
    paddingBottom: gap,
  },
  userName: {
    margin: 'auto 1em',
    textAlign: 'center',
  },
  quarterOptions: {
    paddingBottom: gap,
  },
  quarterSelect: {
    fontSize: 'medium',
  },
  dialogOption: {
    whiteSpace: 'nowrap',
    verticalAlign: 'top',
    display: 'flex',
    paddingBottom: '0.5em',
  },
  dialogOptionTitle: {
    fontSize: 'small',
  },
  dialogOptions: {
    width: '100%',
  },
  reason: {
    width: '100%',
  },
  reasonInput: {
    width: 'calc(100% - 1em)',
    marginLeft: '1em',
    fontSize: 'large',
  },
  dialogButton: {
    fontSize: 'large',
    width: '8em',
    height: '2.2em',
    float: 'right',
    margin: '2em 0 0 1em',
  },
  primaryButton: {
    color: 'white',
    backgroundColor: '#2171ce',
  },
  disabledButton: {
    color: 'lightgray',
  },
};

class OKRClose extends Component {
  state = {
    reason: '',
  }

  static propTypes = {
    isOpen: PropTypes.bool,
    handleSubmit: PropTypes.func.isRequired,
  };

  static defaultProps = {
    isOpen: true,
  };

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.okrNav, style.isOpen));
    }
    return style.okrNav;
  }

  onReasonChange(event) {
    this.setState({ choice: 'other', reason: event.target.value })
  }

  render() {
    const { isOpen, handleSubmit } = this.props
    const { choice, reason } = this.state
    const isSubmitDisabled = !choice || (choice === 'other' && _.isEmpty(reason))
    const submitButtonStyle = _.defaults(isSubmitDisabled ? style.disabledButton : {},
                                         style.dialogButton,
                                         style.primaryButton)
    return (
      <div style={OKRClose.checkNaviOpen(isOpen)}>
        <div style={style.dialogTitle}>OKRのクローズ</div>
        <div style={style.dialogSubTitle}>クローズの理由を選択してください。</div>
        <table style={style.dialogOptions}>
          <tbody>
            <tr>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='achieved' checked={choice === 'achieved'}
                         onChange={() => this.setState({ choice: 'achieved' })} />
                  目標を達成した
                </label>
              </td>
            </tr>
            <tr>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='unneeded' checked={choice === 'unneeded'}
                         onChange={() => this.setState({ choice: 'unneeded' })} />
                  追う必要性がなくなった
                </label>
              </td>
            </tr>
            <tr>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='other' checked={choice === 'other'}
                         onChange={() => this.setState({ choice: 'other' })} />
                  その他
                </label>
                <div style={style.reason}>
                  <input type='text' name='reason' placeholder='ここに理由を入力'
                         onChange={this.onReasonChange.bind(this)} style={style.reasonInput} />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div>
          <button name='ok' onClick={() => handleSubmit(this.state)}
                  disabled={isSubmitDisabled} style={submitButtonStyle}>
            クローズ
          </button>
          <button name='cancel' style={style.dialogButton}>キャンセル</button>
        </div>
      </div>
    );
  }
}

export default OKRClose;
