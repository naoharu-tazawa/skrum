import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { initial, last, slice, find, replace } from 'lodash';
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
      value: PropTypes.arrayOf(PropTypes.oneOfType([PropTypes.shape({}), PropTypes.string])),
      onChange: PropTypes.func,
      onFocus: PropTypes.func,
      onBlur: PropTypes.func,
      tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    };

    render() {
      const { value: items = [], onChange, onFocus, onBlur, tabIndex } = this.props;
      const { leftPad = 0, topPad = 0 } = this.state || {};
      const lastItem = last(items);
      const deleteItem = index => onChange([...slice(items, 0, index), ...slice(items, index + 1)]);
      return (
        <div className={styles.component}>
          <span className={styles.items}>
            {initial(items).map((item, index) => (
              <Item
                key={index}
                onDelete={() => deleteItem(index)}
              >
                {item[labelPropName]}
              </Item>
            ))}
            {lastItem && (
              <ResizeAware
                key={items.length}
                component={Item}
                onlyEvent
                onResize={({ left, top, width }) =>
                  this.setState({ leftPad: left + width, topPad: top })}
                onDelete={() => {
                  deleteItem(items.length - 1);
                  if (items.length === 1) this.setState({ leftPad: 0 });
                }}
              >
                {lastItem[labelPropName]}
              </ResizeAware>)}
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
            inputStyle={{ paddingLeft: `${leftPad + 6}px`, paddingTop: `${topPad + 6}px` }}
          />
        </div>);
    }
  };

export default { withMultiSelect };
