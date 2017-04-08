import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import KRWeightingBar from '../component/KRWeightingBar';

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
    height: '460px',
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
  weightingBars: {
    borderWidth: 'thin',
    borderTopStyle: 'solid',
  },
  weightingBar: {
    borderWidth: 'thin',
    borderBottomStyle: 'solid',
  },
  graph: {
    width: '136px',
    height: '136px',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
    margin: '1em auto',
  },
  dialogButton: {
    fontSize: 'large',
    width: '8em',
    height: '2.2em',
    float: 'right',
    margin: '0 0 0 1em',
  },
  primaryButton: {
    color: 'white',
    backgroundColor: '#2171ce',
  },
};

class KRWeightingSetting extends Component {
  state = {
    weightings: {}
  }

  static propTypes = {
    isOpen: PropTypes.bool,
    title: PropTypes.string.isRequired,
    weightings: PropTypes.arrayOf(PropTypes.shape({
      title: PropTypes.string.isRequired,
      weighting: PropTypes.number.isRequired,
      isLocked: PropTypes.bool.isRequired,
      user: PropTypes.shape({
        name: PropTypes.string.isRequired,
      }).isRequired,
    })),
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }).isRequired,
    handleSubmit: PropTypes.func.isRequired,
  };

  static defaultProps = {
    isOpen: true,
  };

  componentDidMount() {
    const { weightings } = this.props
    this.state.weightings = _.fromPairs(weightings.map(({ weighting }, index) => [index, weighting]))
  }

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.okrNav, style.isOpen));
    }
    return style.okrNav;
  }

  onWeightingChange(self, index, weighting) {
    self.setState({ weightings: Object.assign(self.state.weightings, { [index]: weighting })})
  }

  render() {
    const { isOpen, title, weightings, user, handleSubmit } = this.props
    return (
      <div style={KRWeightingSetting.checkNaviOpen(isOpen)}>
        <div style={style.dialogTitle}>KRの加重平均重要度設定</div>
        <div style={style.userBox}>
          <div style={style.userImage} />
          <div style={style.userName}>{user.name}</div>
          <div style={style.title}>
            {title}
          </div>
        </div>
        <div style={style.weightingBars}>
          {weightings.map((weighting, index) =>
            <div key={index} style={style.weightingBar}>
              <KRWeightingBar {...weighting}
                              onChange={({ weighting }) => this.onWeightingChange(this, index, weighting)} />
            </div>)}
        </div>
        <div style={style.graph} />
        <div>
          <button name='ok' onClick={() => handleSubmit(this.state)}
                  style={{...style.dialogButton, ...style.primaryButton}}>
            OK
          </button>
          <button name='cancel' style={style.dialogButton}>キャンセル</button>
        </div>
      </div>
    );
  }
}

export default KRWeightingSetting;
