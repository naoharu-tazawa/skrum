import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import Autocomplete from 'react-autocomplete';
import { debounce, isObject } from 'lodash';
import styles from './SearchDropdown.css';

export default class SearchDropdown extends PureComponent {

  static propTypes = {
    items: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
    labelPropName: PropTypes.string.isRequired,
    onSearch: PropTypes.func.isRequired,
    onSelect: PropTypes.func.isRequired,
    value: PropTypes.oneOfType([PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  componentWillMount() {
    this.handleSearch = debounce(value => this.props.onSearch(value), 1000);
  }

  render() {
    const { items, labelPropName, onSelect, value, onChange, onFocus, onBlur,
      isSearching } = this.props;
    const { currentInput = isObject(value) ? value[labelPropName] : value,
      isFocused = false } = this.state || {};
    return (
      <Autocomplete
        items={items}
        value={currentInput}
        getItemValue={({ [labelPropName]: label }) => label}
        renderItem={({ [labelPropName]: label }, isHighlighted, renderStyles) =>
          <div
            style={{
              background: isHighlighted ? 'lightgray' : 'white',
              padding: '2px 4px',
              ...renderStyles,
            }}
          >
            {label}
          </div>
        }
        wrapperProps={{ className: `${styles.component}
          ${isFocused ? styles.focused : ''}
          ${isSearching ? styles.searching : ''}
        ` }}
        inputProps={{
          className: styles.input,
          onFocus: (e) => { this.setState({ isFocused: true }); onFocus(e); },
          onBlur: (e) => { this.setState({ isFocused: false }); onBlur(e); },
        }}
        onChange={(e) => {
          const { target } = e;
          this.setState({ currentInput: target.value });
          onSelect(undefined);
          if (onChange) onChange(e);
          this.handleSearch(target.value);
        }}
        onSelect={(input, item) => {
          this.setState({ currentInput: input });
          onSelect(item);
        }}
        menuStyle={{
          boxShadow: '0 2px 12px rgba(0, 0, 0, 0.1)',
          background: 'rgba(255, 255, 255, 0.9)',
          padding: '0',
          marginTop: '2px',
          fontSize: '13px',
          position: 'fixed',
          overflow: 'auto',
          maxHeight: '33%',
          maxWidth: '0',
        }}
      />
    );
  }
}
