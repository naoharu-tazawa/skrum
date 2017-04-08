
import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import OKRDelete from '../../src/common/dialog/OKRDelete';

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
  .add('OKR削除', () => (<div>
    <div style={formContainerStyle}>
      <OKRDelete
        title='目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー'
        user={{ name: 'User1' }}
        handleSubmit={action('OKR削除ボタンが押されました')}
      />
    </div>
  </div>));
