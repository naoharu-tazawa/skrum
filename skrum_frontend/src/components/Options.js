import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { toPairs } from 'lodash';
import Select from 'react-select';
import styles from './Options.css';

export default class Options extends PureComponent {

  static propTypes = {
    className: PropTypes.string,
    renderer: PropTypes.func,
    map: PropTypes.shape({}).isRequired,
    defaultValue: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    value: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
  };

  render() {
    const getOptionStyle = (id, currentId) => (id === currentId ? styles.current : '');
    const { className, renderer, map, defaultValue,
      value: dirtyValue, onChange, onFocus, onBlur } = this.props;
    const options = toPairs(map).map(([id, label]) => ({ value: `${id}`, label }));
    if (renderer) {
      return <div className={`${styles.component} ${className || ''}`}>{options.map(renderer)}</div>;
    }
    return (
      <Select
        className={`${styles.select} ${className || ''}`}
        options={options}
        optionRenderer={({ value: id, label }) =>
          <div className={getOptionStyle(id, defaultValue)}>{label}</div>}
        value={dirtyValue || defaultValue}
        {...{ onChange, onFocus, onBlur }}
        placeholder=""
        clearable={false}
        searchable={false}
      />);
  }
}
