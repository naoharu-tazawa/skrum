import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import Autocomplete from 'react-autocomplete';
import { debounce, isObject } from 'lodash';
import styles from './SearchDropdown.css';

export default class SearchDropdown extends PureComponent {

  static propTypes = {
    className: PropTypes.string,
    items: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
    labelPropName: PropTypes.string.isRequired,
    inputStyle: PropTypes.shape({}),
    onSearch: PropTypes.func.isRequired,
    onSelect: PropTypes.func.isRequired,
    inputOnSelect: PropTypes.oneOf(['keep', 'clear']),
    renderItem: PropTypes.func,
    value: PropTypes.oneOfType([PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    disabled: PropTypes.bool,
    tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    isSearching: PropTypes.bool,
  };

  componentWillMount() {
    this.handleSearch = debounce(value => this.props.onSearch(value), 1000);
  }

  render() {
    const { className = '', items, labelPropName, inputStyle, onSelect, inputOnSelect, renderItem,
      value, onChange, onFocus, onBlur, disabled, tabIndex, isSearching } = this.props;
    const { currentInput = isObject(value) ? value[labelPropName] : value,
      isFocused = false } = this.state || {};
    return (
      <Autocomplete
        items={items}
        value={currentInput}
        getItemValue={({ [labelPropName]: label }) => label}
        renderItem={(item, isHighlighted, renderStyles) =>
          <div
            style={{
              background: isHighlighted ? '#ebf5ff' : 'white',
              padding: '2px 4px',
              ...renderStyles,
            }}
          >
            {renderItem ? renderItem(item) : item[labelPropName]}
          </div>
        }
        wrapperProps={{ className: `${styles.component}
          ${className}
          ${isFocused ? styles.focused : ''}
          ${isFocused && isSearching ? styles.searching : ''}
        ` }}
        inputProps={{
          className: styles.input,
          style: inputStyle,
          onFocus: (e) => { this.setState({ isFocused: true }); if (onFocus) onFocus(e); },
          onBlur: (e) => { this.setState({ isFocused: false }); if (onBlur) onBlur(e); },
          disabled,
          tabIndex,
        }}
        onChange={(e) => {
          const { target } = e;
          this.setState({ currentInput: target.value });
          onSelect({});
          if (onChange) onChange(e);
          this.handleSearch(target.value);
        }}
        onSelect={(input, item) => {
          switch (inputOnSelect) {
            case 'keep':
              break;
            case 'clear':
              this.setState({ currentInput: '' });
              break;
            default:
              this.setState({ currentInput: input });
          }
          onSelect(item);
        }}
        menuStyle={{
          boxShadow: '0 2px 12px rgba(0, 0, 0, 0.1)',
          background: 'rgba(255, 255, 255, 0.9)',
          marginTop: '2px',
          fontSize: '13px',
          position: 'fixed',
          overflow: 'auto',
          maxHeight: '33%',
          maxWidth: 0,
          padding: 0,
          zIndex: 1,
        }}
      />
    );
  }
}
