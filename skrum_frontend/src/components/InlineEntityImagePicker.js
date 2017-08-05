import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import Dropzone from 'react-dropzone';
import EntityLink, { entityPropType } from './EntityLink';
import InlineEditor, { inlineEditorPublicPropTypes } from '../editors/InlineEditor';
import styles from './InlineEntityImagePicker.css';

export default class InlineEntityImagePicker extends PureComponent {

  static propTypes = {
    entity: entityPropType,
    avatarSize: PropTypes.string,
    ...inlineEditorPublicPropTypes,
  };

  render() {
    const { entity, avatarSize = '180px', ...inlineEditorProps } = this.props;
    const content = ({ preview }) =>
      (preview ?
        <img
          className={styles.preview}
          src={preview}
          alt=""
          style={{ width: avatarSize, height: avatarSize }}
        /> :
        <EntityLink
          entity={entity}
          avatarSize={avatarSize}
          avatarOnly
          local
          fluid
        />);
    return (
      <InlineEditor
        componentClassName={styles.editor}
        {...{ value: {}, ...inlineEditorProps }}
        formatter={content}
      >
        {({ setRef, currentValue, setValue }) =>
          <div className={styles.dropzone} style={{ width: avatarSize, height: avatarSize }}>
            <Dropzone
              ref={setRef}
              onDrop={([file]) => setValue(file)}
              accept="image/*"
              multiple={false}
            >
              {content(currentValue)}
            </Dropzone>
          </div>}
      </InlineEditor>
    );
  }
}
