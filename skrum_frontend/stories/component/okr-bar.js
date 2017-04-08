import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import OKRBar from '../../src/common/component/OKRBar';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
  height: '60px',
  width: '800px',
  padding: '20px',
  border: 'solid 1px #000',
};

storiesOf('コンポーネント', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('OKRバー', () => (<div>
    <div style={formContainerStyle}>
      <OKRBar
        title='目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー'
        isRoot={true}
        progress={68}
        owner={{ name: 'User1' }}
      />
    </div>
  </div>));
