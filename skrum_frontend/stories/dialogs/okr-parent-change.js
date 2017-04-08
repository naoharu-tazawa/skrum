import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import OKRParentChange from '../../src/common/dialog/OKRParentChange';

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
  .add('OKRの紐付け先変更', () => (<div>
    <div style={formContainerStyle}>
      <OKRParentChange
        title='目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー'
        groupOptions={[ 'Group 1', 'Group 2', 'Group 3', 'Group 4' ]}
        user={{ name: 'User1' }}
        handleSubmit={action('OKRの紐付け先変更ボタンが押されました')}
      />
    </div>
  </div>));
