import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import Dropdown, { DropdownTrigger, DropdownContent } from 'react-simple-dropdown';

export default class DropdownMenu extends PureComponent {

  static propTypes = {
    trigger: PropTypes.element.isRequired,
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
    const { trigger, options, align = 'left' } = this.props;
    let style = {}; // left
    if (align === 'center') style = { left: '50%', transform: 'translate(-50%, 0)' };
    else if (align === 'right') style = { right: 0 };
    return (
      <Dropdown
        ref={(dropdown) => { this.dropdown = dropdown; }}
        style={{ position: 'relative' }}
      >
        <DropdownTrigger style={{ cursor: 'pointer' }}>
          {trigger}
        </DropdownTrigger>
        <DropdownContent style={style}>
          {options.map(({ caption, onClick, path }, index) => (
            onClick
              ? <a key={index} onMouseUp={() => this.hide() && onClick()}>{caption}</a>
              : <Link key={index} to={path} onClick={this.hide.bind(this)}>{caption}</Link>
          ))}
        </DropdownContent>
      </Dropdown>
    );
  }
}
