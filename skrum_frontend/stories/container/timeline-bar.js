import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import TimelineBar from '../../src/common/container/TimelineBar';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
  height: '300px',
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
  .add('タイムラインバー', () => (<div>
    <div style={formContainerStyle}>
      <TimelineBar
        post='○○さんがKR ~~~ を設定しました。'
        date={new Date('2017-03-01')}
        user={{ name: 'User1' }}
        comments={[{ post: 'プロジェクト発足しました。登録したのでメンバー各位、KRの設定をお願いします。' }]}
        handleLike={action('タイムラインバー「いいね」ボタンが押されました')}
        handleAddComment={action('タイムラインバー「コメントする」ボタンが押されました')}
      />
    </div>
  </div>));
