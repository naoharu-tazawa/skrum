import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import InlineEditor, { inlineEditorPublicPropTypes } from '../../editors/InlineEditor';
import OwnerSearch from './OwnerSearch';
import { entityPropTypes } from '../../util/EntityUtil';

export default class InlineOwnerSearch extends PureComponent {

  static propTypes = {
    owner: PropTypes.oneOfType([entityPropTypes, PropTypes.shape({}), PropTypes.string]),
    ...inlineEditorPublicPropTypes,
  };

  render() {
    const { owner, ...inlineEditorProps } = this.props;
    // const { ownerSelected = owner } = this.state || {};
    const { name = '' } = owner || {};
    return (
      <InlineEditor
        fluid
        dropdown
        {...{ value: name, ...inlineEditorProps }}
      >
        {({ setRef, setValue, submit }) =>
          <OwnerSearch
            ref={setRef}
            value={owner}
            onChange={(ownerSelected) => {
              setValue(ownerSelected.name);
              this.setState({ ownerSelected });
            }}
            onKeyPress={e => e.key === 'Enter' && submit()}
          />}
      </InlineEditor>
    );
  }
}
