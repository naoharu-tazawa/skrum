import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import Dropdown, { DropdownTrigger, DropdownContent } from 'react-simple-dropdown';
import styles from './Dropdown.css';

export default class DropdownMenu extends PureComponent {

  static propTypes = {
    trigger: PropTypes.element,
    triggerIcon: PropTypes.string,
    options: PropTypes.arrayOf(PropTypes.shape({
      caption: PropTypes.string.isRequired,
      onClick: PropTypes.func,
      path: PropTypes.string,
    })).isRequired,
    align: PropTypes.oneOf(['left', 'center', 'right']),
  };

  hide() {
    this.dropdown.hide();
    return true;
  }

  render() {
    const { trigger, triggerIcon, options, align = 'left' } = this.props;
    const alignmentStyle = {
      center: { left: '50%', transform: 'translate(-50%, 0)' },
      right: { right: 0 },
    };
    return (
      <Dropdown
        ref={(dropdown) => { this.dropdown = dropdown; }}
        style={{ position: 'relative' }}
      >
        <DropdownTrigger style={{ cursor: 'pointer' }}>
          {trigger || (
            <div
              className={styles.circle}
              style={{ background: `url(${triggerIcon || '/img/common/inc_link.png'}) no-repeat center` }}
            />)}
        </DropdownTrigger>
        <DropdownContent style={alignmentStyle[align]}>
          {options.map(({ caption, onClick, path, target }, index) => (
            onClick
              ? (
                <a
                  key={index}
                  onMouseUp={() => this.hide() && onClick()}
                  href={path}
                  target={target}
                >
                  {caption}
                </a>)
              : <Link key={index} to={path} onClick={this.hide.bind(this)}>{caption}</Link>
          ))}
        </DropdownContent>
      </Dropdown>
    );
  }
}
