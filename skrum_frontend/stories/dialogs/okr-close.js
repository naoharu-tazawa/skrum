import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import OKRClose from '../../src/common/dialog/OKRClose';

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
  .add('OKRのクローズ', () => (<div>
    <div style={formContainerStyle}>
      <OKRClose
        handleSubmit={action('OKRのクローズボタンが押されました')}
      />
    </div>
  </div>));
