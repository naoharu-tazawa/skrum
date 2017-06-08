import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import styles from './InlineText.css';

export default class InlineTextInput extends PureComponent {

  static propTypes = {
    // name: PropTypes.string.isRequired,
    type: PropTypes.string,
    value: PropTypes.string,
    readonly: PropTypes.bool,
  };

  state = {};

  render() {
    const { type = 'text', value = '', readonly = false } = this.props;
    const { hitMode = false, editMode = false } = this.state;
    return (
      <span
        onMouseEnter={() => !readonly && !editMode && this.setState({ hitMode: true })}
        onMouseLeave={() => !readonly && !editMode && this.setState({ hitMode: false })}
        onMouseDown={() => !readonly && this.setState({ editMode: true, hitMode: false })}
        className={`${styles.default} ${!editMode && hitMode ? styles.hitMode : ''}`}
      >
        {!editMode && value}
        {!editMode && <span className={styles.editButton} />}
        {editMode && (
          <form onSubmit={() => this.setState({ editMode: false })}>
            <input
              type={type}
              defaultValue={value}
              onBlur={() => this.setState({ editMode: false })}
              onKeyDown={e => e.key === 'Escape' && this.setState({ editMode: false })}
            />
            <div className={styles.saveOptions}>
              <button type="submit" className={styles.submit}>&nbsp;</button>
              <button type="cancel" className={styles.cancel}>&nbsp;</button>
            </div>
          </form>)}
      </span>
    );
  }
}
