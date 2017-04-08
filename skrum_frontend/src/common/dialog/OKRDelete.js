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
    height: '210px',
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
  choice: {
    margin: '0 0 0 1em',
  },
  dialogButton: {
    fontSize: 'large',
    width: '8em',
    height: '2.2em',
    float: 'right',
    margin: '2em 0 0 1em',
  },
  destructiveButton: {
    color: 'white',
    backgroundColor: '#d54c53',
  },
  disabledButton: {
    color: 'lightgray',
  },
};

class OKRDelete extends Component {
  state = {
    yesSelected: false
  }

  static propTypes = {
    isOpen: PropTypes.bool,
    title: PropTypes.string.isRequired,
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }).isRequired,
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

  render() {
    const { isOpen, title, user, handleSubmit } = this.props
    const { yesSelected } = this.state
    const deleteButtonStyle = _.defaults(!yesSelected ? style.disabledButton : {},
                                         style.dialogButton,
                                         style.destructiveButton)
    return (
      <div style={OKRDelete.checkNaviOpen(isOpen)}>
        <div style={style.dialogTitle}>OKRの複製・移動</div>
        <div style={style.userBox}>
          <div style={style.userImage} />
          <div style={style.userName}>{user.name}</div>
          <div style={style.title}>
            {title}
          </div>
        </div>
        <div>
          このOKRを削除しますか?
          <label style={style.choice}>
            <input type='radio' name='choice' value='no' defaultChecked={true}
                   onChange={() => this.setState({ yesSelected: false })} />
            いいえ
          </label>
          <label style={style.choice}>
            <input type='radio' name='choice' value='yes'
                   onChange={() => this.setState({ yesSelected: true })} />
            はい
          </label>
        </div>
        <div>
          <button name='ok' onClick={() => handleSubmit(this.state)}
                  disabled={!yesSelected}
                  style={deleteButtonStyle}>
            削除
          </button>
          <button name='cancel' style={style.dialogButton}>キャンセル</button>
        </div>
      </div>
    );
  }
}

export default OKRDelete;
