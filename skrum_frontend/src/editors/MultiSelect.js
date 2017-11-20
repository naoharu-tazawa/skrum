import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { slice, find, replace } from 'lodash';
import ResizeAware from '../components/ResizeAware';
import styles from './MultiSelect.css';

const Item = ({ getRef, children, onDelete }) => (
  <span ref={getRef} className={styles.item}>
    {children}
    <a
      className={styles.delete}
      tabIndex={0}
      onClick={onDelete}
    >
      <img src="/img/delete.png" alt="" />
    </a>
  </span>);

Item.propTypes = {
  getRef: PropTypes.func,
  children: PropTypes.node,
  onDelete: PropTypes.func,
};

export const withMultiSelect = (WrappedSearchDropdown, { labelPropName, stripSpace }) =>
  class extends PureComponent {

    static propTypes = {
      padding: PropTypes.number,
      value: PropTypes.arrayOf(PropTypes.oneOfType([PropTypes.shape({}), PropTypes.string])),
      onChange: PropTypes.func,
      onFocus: PropTypes.func,
      onBlur: PropTypes.func,
      tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    };

    render() {
      const { padding = 4, value: items = [], onChange, onFocus, onBlur, tabIndex } = this.props;
      const { leftPad = 0, topPad = 0 } = this.state || {};
      const deleteItem = index => onChange([...slice(items, 0, index), ...slice(items, index + 1)]);
      return (
        <div className={styles.component}>
          <span
            className={styles.items}
            style={{ margin: `${padding}px` }}
          >
            {items.map((item, index) => (
              <Item
                key={index}
                onDelete={() => {
                  deleteItem(index);
                  if (items.length === 1) this.setState({ leftPad: 0, topPad: 0 });
                }}
              >
                {item[labelPropName]}
              </Item>
            ))}
            <ResizeAware
              key={items.length}
              component="span"
              onlyEvent
              onResize={({ left, top }) => this.setState({ leftPad: left, topPad: top })}
              style={{ backgroundColor: 'transparent', width: '6em', height: '1em', display: 'inline-block' }}
            />
          </span>
          <WrappedSearchDropdown
            onChange={({ [labelPropName]: name, ...itemOthers }) => {
              if (name) {
                const item = {
                  [labelPropName]: stripSpace ? replace(name, ' ', '') : name,
                  ...itemOthers,
                };
                if (!find(items, item)) onChange([...items, item]);
              }
            }}
            {...{ onFocus, onBlur, tabIndex }}
            inputStyle={{ paddingLeft: `${leftPad + padding}px`, paddingTop: `${topPad + padding + 2}px` }}
          />
        </div>);
    }
  };

export default { withMultiSelect };
