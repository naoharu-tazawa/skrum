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
  };

  hide() {
    this.dropdown.hide();
    return true;
  }

  render() {
    const { trigger, options } = this.props;
    return (
      <Dropdown
        ref={(dropdown) => { this.dropdown = dropdown; }}
        style={{ position: 'relative' }}
      >
        <DropdownTrigger>
          {trigger}
        </DropdownTrigger>
        <DropdownContent>
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
