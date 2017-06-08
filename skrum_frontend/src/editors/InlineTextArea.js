import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import styles from './InlineText.css';

export default class InlineTextArea extends PureComponent {

  static propTypes = {
    // name: PropTypes.string.isRequired,
    value: PropTypes.string.isRequired,
    readonly: PropTypes.bool,
  };

  state = {};

  render() {
    const { value = '', readonly = false } = this.props;
    const { hitMode = false, editMode = false } = this.state;
    return (
      <span
        onMouseEnter={() => !readonly && this.setState({ hitMode: true })}
        onMouseLeave={() => !readonly && this.setState({ hitMode: false })}
        onMouseDown={() => !readonly && this.setState({ editMode: true })}
        className={`${styles.default} ${!editMode && hitMode ? styles.hitMode : ''}`}
      >
        {!editMode && value}
        {!editMode && <span className={styles.editButton} />}
        {editMode && (
          <form onSubmit={() => this.setState({ editMode: false })}>
            <textarea
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
