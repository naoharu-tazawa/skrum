import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import style from './KRWeightingBar.css';

/*
const gap = '1em';
const style = {
  okrNav: {
    display: 'flex',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
  },
  isOpen: {
    height: '48px',
  },
  isHide: {
    display: 'none',
  },
  userBox: {
    display: 'flex',
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
  userName: {
    margin: 'auto 1em',
    textAlign: 'center',
  },
  title: {
    position: 'relative',
    width: '100%',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
    margin: 'auto ' + gap,
    fontSize: 'small',
  },
  weighting: {
    width: '3.5em',
    fontSize: 'large',
    margin: '0.5em 0',
    textAlign: 'right',
  },
  weightingPercent: {
    margin: 'auto 1em auto 0.25em',
  },
  lockImage: {
    minWidth: '36px',
    height: '36px',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
    margin: 'auto',
  },
};
*/

class KRWeightingBar extends Component {
  state = {}

  static propTypes = {
    isOpen: PropTypes.bool,
    title: PropTypes.string.isRequired,
    weighting: PropTypes.number.isRequired,
    isLocked: PropTypes.bool.isRequired,
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }).isRequired,
    onChange: PropTypes.func,
  };

  static defaultProps = {
    isOpen: true,
    onChange: () => {}
  };

  componentDidMount() {
    const { weighting } = this.props
    this.state.weighting = weighting
  }

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.okrNav, style.isOpen));
    }
    return style.okrNav;
  }

  onWeightingChange(event) {
    const weighting = _.floor(_.toNumber(event.target.value), 1)
    this.setState({ weighting })
  }

  onWeightingBlur(event) {
    const weighting = _.floor(_.toNumber(event.target.value), 1)
    this.setState({ weighting })
    this.props.onChange({ weighting })
  }

  render() {
    const { isOpen, title, user, isLocked } = this.props
    const { weighting } = this.state
//    if (_.isUndefined(weighting)) { return null }
    return (
      <div style={KRWeightingBar.checkNaviOpen(isOpen)}>
        <div style={style.userBox}>
          <div style={style.userImage} />
          <div style={style.userName}>{user.name}</div>
        </div>
        <div style={style.title}>
          {title}
        </div>
        <input type='number' value={weighting}
               onChange={this.onWeightingChange.bind(this)}
               onBlur={this.onWeightingBlur.bind(this)} style={style.weighting} />
        <div style={style.weightingPercent}>%</div>
        <div style={style.lockImage} />
      </div>
    );
  }
}

export default KRWeightingBar;
