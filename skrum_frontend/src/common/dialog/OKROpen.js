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

class OKROpen extends Component {
  state = {}

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

  onReasonChanged() {
    this.setState({ choice: 'other' })
  }

  render() {
    const { isOpen, title, handleSubmit } = this.props
    const { choice } = this.state
    const submitButtonStyle = _.defaults(!choice ? style.disabledButton : {},
                                         style.dialogButton,
                                         style.primaryButton)
    return (
      <div style={OKROpen.checkNaviOpen(isOpen)}>
        <div style={style.dialogTitle}>OKRの公開設定</div>
        <table style={style.dialogOptions}>
          <tbody>
            <tr>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='self' checked={choice === 'self'}
                         onChange={() => this.setState({ choice: 'self' })} />
                  自分のみ
                </label>
              </td>
            </tr>
            <tr>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='group-member' checked={choice === 'group-member'}
                         onChange={() => this.setState({ choice: 'group-member' })} />
                  グループ所属者のみ
                </label>
              </td>
            </tr>
            <tr>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='group-admin' checked={choice === 'group-admin'}
                         onChange={() => this.setState({ choice: 'group-admin' })} />
                  グループの管理のみ
                </label>
              </td>
            </tr>
            <tr>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='super-admin' checked={choice === 'super-admin'}
                         onChange={() => this.setState({ choice: 'super-admin' })} />
                  スーパー管理者のみ
                </label>
              </td>
            </tr>
          </tbody>
        </table>
        <div>
          <button name='ok' onClick={() => handleSubmit(this.state)}
                  disabled={!choice}
                  style={submitButtonStyle}>
            変更
          </button>
          <button name='cancel' style={style.dialogButton}>キャンセル</button>
        </div>
      </div>
    );
  }
}

export default OKROpen;
