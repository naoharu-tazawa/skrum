import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import LoginForm from '../../src/auth/component/LoginForm';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '100vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
  width: '200px',
  padding: '20px',
  border: 'dashed 1px #000',
};

storiesOf('トップ', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('ログインフォーム', () => (<div>
    <div style={formContainerStyle}>
      <LoginForm handleLoginSubmit={action('ボタンが押されました')} />
    </div>
    <div style={formContainerStyle}>
      <LoginForm handleLoginSubmit={action('ボタンが押されました')} isFetching />
    </div>
  </div>));
