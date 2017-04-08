import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import KRBar from '../../src/common/component/KRBar';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
//  height: '360px',
  padding: '20px',
  border: 'solid 1px #000',
};

const graph = {
  width: '300px',
  height: '200px',
  float: 'right',
}

storiesOf('コンポーネント', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('KRバー', () => (<div>
    <div style={formContainerStyle}>
      <div style={graph} />
      <KRBar
        isTopLevel
        title='目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー'
        content='詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テ'
        dividend={6}
        divisor={7}
        user={{ name: 'User1', joined: new Date(), expiry: new Date() }}
      />
      <p/>
      <KRBar
        title='目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー'
        content='詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テキストダミーー詳細テ'
        dividend={6}
        divisor={10}
        user={{ name: 'User1', joined: new Date(), expiry: new Date() }}
      />
    </div>
  </div>));
