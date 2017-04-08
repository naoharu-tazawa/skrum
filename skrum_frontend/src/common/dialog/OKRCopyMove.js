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
    height: '360px',
    width: '680px',
  },
  dialogTitle: {
    position: 'relative',
    paddingBottom: gap,
    width: '100%',
    fontSize: 'large',
    fontWeight: 'bold',
  },
  title: {
    position: 'relative',
    padding: '10px 13px',
    width: '100%',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
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
    paddingRight: '2em',
    verticalAlign: 'top',
  },
  dialogOptionExplained: {
    fontSize: 'small',
  },
  dialogOptionRow: {
    paddingBottom: gap,
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

class OKRCopyMove extends Component {
  state = {}

  static propTypes = {
    isOpen: PropTypes.bool,
    title: PropTypes.string.isRequired,
    quarterOptions: PropTypes.arrayOf(PropTypes.string),
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }).isRequired,
    handleSubmit: PropTypes.func.isRequired,
  };

  static defaultProps = {
    isOpen: true,
  };

  componentDidMount() {
    const { quarterOptions } = this.props
    this.setState({ quarter: quarterOptions[0] })
  }

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.okrNav, style.isOpen));
    }
    return style.okrNav;
  }

  handleQuarterChange(event) {
    this.setState({ quarter: event.target.value })
  }

  render() {
    const { isOpen, title, quarterOptions, user, handleSubmit } = this.props
    const { quarter, choice } = this.state
    const submitButtonStyle = _.defaults(!choice ? style.disabledButton : {},
                                         style.dialogButton,
                                         style.primaryButton)
    return (
      <div style={OKRCopyMove.checkNaviOpen(isOpen)}>
        <div style={style.dialogTitle}>OKRの複製・移動</div>
        <div style={style.userBox}>
          <div style={style.userImage} />
          <div style={style.userName}>{user.name}</div>
          <div style={style.title}>
            {title}
          </div>
        </div>
        <div style={style.quarterOptions}>
          <select name='select' style={style.quarterSelect} defaultValue={quarter}
                  onChange={this.handleQuarterChange.bind(this)}>
            {quarterOptions.map((option, index) => <option key={index} value={option}>{option}</option>)}
          </select>
        </div>
        <table>
          <tbody>
            <tr style={style.dialogOptionRow}>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='copy'
                         onChange={() => this.setState({ choice: 'copy' })} />
                  複製
                </label>
              </td>
              <td>
                <label style={style.dialogOptionExplained}>
                  説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー
                  説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー
                  説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー
                </label>
              </td>
            </tr>
            <tr style={style.dialogOptionRow}>
              <td style={style.dialogOption}>
                <label>
                  <input type='radio' name='choice' value='move'
                         onChange={() => this.setState({ choice: 'move' })} />
                  移動
                </label>
              </td>
              <td>
                <label style={style.dialogOptionExplained}>
                  説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー
                  説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー
                  説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー説明テキストダミー
                </label>
              </td>
            </tr>
          </tbody>
        </table>
        <div>
          <button name='ok' onClick={() => handleSubmit(this.state)}
                  disabled={!choice}
                  style={submitButtonStyle}>
            OK
          </button>
          <button name='cancel' style={style.dialogButton}>キャンセル</button>
        </div>
      </div>
    );
  }
}

export default OKRCopyMove;
