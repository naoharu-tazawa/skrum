import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import Dropdown, { DropdownTrigger, DropdownContent } from 'react-simple-dropdown';
import styles from './Dropdown.css';

export default class extends PureComponent {

  static propTypes = {
    trigger: PropTypes.element,
    triggerIcon: PropTypes.string,
    content: PropTypes.func.isRequired,
    arrow: PropTypes.oneOf(['left', 'center', 'right']),
  };

  hide() {
    this.dropdown.hide();
    return true;
  }

  render() {
    const { trigger, triggerIcon, content, arrow = 'center' } = this.props;
    const { activeContent } = this.state || {};
    let style = {}; // left
    if (arrow === 'center') style = { left: '50%', transform: 'translate(-50%, 0)' };
    else if (arrow === 'right') style = { right: 0 };
    return (
      <Dropdown
        ref={(dropdown) => { this.dropdown = dropdown; }}
        style={{ position: 'relative' }}
        onShow={() => this.setState({ activeContent:
          activeContent || content({ onClose: this.hide.bind(this) }) })}
      >
        <DropdownTrigger>
          {trigger || (
            <div
              className={styles.circle}
              style={{ background: `url(${triggerIcon || '/img/common/inc_link.png'}) no-repeat center` }}
            />)}
        </DropdownTrigger>
        <DropdownContent style={style}>
          {activeContent}
        </DropdownContent>
      </Dropdown>
    );
  }
}
