import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import OKRCopyMove from '../../src/common/dialog/OKRCopyMove';

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
  .add('OKRの複製・移動', () => (<div>
    <div style={formContainerStyle}>
      <OKRCopyMove
        title='目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー'
        quarterOptions={[ '2017 / 1Q', '2017 / 2Q', '2017 / 3Q', '2017 / 4Q' ]}
        user={{ name: 'User1' }}
        handleSubmit={action('OKRの複製・移動ボタンが押されました')}
      />
    </div>
  </div>));
