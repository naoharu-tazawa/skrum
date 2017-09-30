import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import Dropzone from 'react-dropzone';
import AvatarEditor from 'react-avatar-editor';
import EntityLink from './EntityLink';
import InlineEditor, { inlineEditorPublicPropTypes } from '../editors/InlineEditor';
import { entityPropTypes } from '../util/EntityUtil';
import { loadImageDataUrl, parseDataUrl } from '../util/ImageUtil';
import styles from './InlineEntityImagePicker.css';

export default class InlineEntityImagePicker extends PureComponent {

  static propTypes = {
    entity: entityPropTypes,
    avatarSize: PropTypes.number,
    ...inlineEditorPublicPropTypes,
  };

  render() {
    const { entity, avatarSize = 180, ...inlineEditorProps } = this.props;
    const { readonly } = inlineEditorProps;
    const content = ({ dataUrl }) =>
      (dataUrl ?
        <AvatarEditor
          ref={(ref) => { this.avatar = ref; }}
          image={dataUrl}
          width={avatarSize}
          height={avatarSize}
          border={0}
          style={{ borderRadius: '50%' }}
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
        className={`${styles.editor} ${readonly && styles.readonly}`}
        {...{ value: {}, ...inlineEditorProps }}
        formatter={content}
        preProcess={({ dataUrl } = {}) => dataUrl &&
          { dataUrl, ...parseDataUrl(this.avatar.getImageScaledToCanvas().toDataURL()) }
        }
      >
        {({ setRef, currentValue, setValue }) =>
          <div className={styles.dropzone} style={{ width: avatarSize, height: avatarSize }}>
            <Dropzone
              ref={setRef}
              onDrop={([file]) => loadImageDataUrl(file).then(dataUrl => setValue({ dataUrl }))}
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
