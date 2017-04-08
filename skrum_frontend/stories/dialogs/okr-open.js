import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import OKROpen from '../../src/common/dialog/OKROpen';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
  height: '60px',
  padding: '20px',
  border: 'solid 1px #000',
};

storiesOf('ダイアローグ', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('OKRの公開設定', () => (<div>
    <div style={formContainerStyle}>
      <OKROpen
        handleSubmit={action('OKRの公開設定ボタンが押されました')}
      />
    </div>
  </div>));
