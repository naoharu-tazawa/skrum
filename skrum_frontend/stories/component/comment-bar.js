import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import CommentBar from '../../src/common/component/CommentBar';

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
  .add('コメントバー', () => (<div>
    <div style={formContainerStyle}>
      <CommentBar
        post='プロジェクト発足しました。登録したのでメンバー各位、KRの設定をお願いします。'
      />
    </div>
  </div>));
