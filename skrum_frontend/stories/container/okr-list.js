import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import OKRList from '../../src/common/container/OKRList';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
//  height: '300px',
  width: '800px',
  padding: '20px',
  border: 'solid 1px #000',
};

storiesOf('コンテイナー', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('所有OKR一覧', () => (<div>
    <div style={formContainerStyle}>
      <OKRList
        list={[
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            isRoot: true, progress: 60, owner: { name: 'User2'} },
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            isRoot: true, progress: 80, owner: { name: 'User3'} },
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            isRoot: true, progress: 30, owner: { name: 'User4'} },
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            isRoot: false, progress: 30, owner: { name: 'User5'} },
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            isRoot: false, progress: 20, owner: { name: 'User6'} },
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            isRoot: false, progress: 70, owner: { name: 'User007'} },
        ]}
      />
    </div>
  </div>));
