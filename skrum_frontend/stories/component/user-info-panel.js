import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import UserInfoPanel from '../../src/common/component/UserInfoPanel';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
  height: '60px',
  width: '600px',
  padding: '20px',
  border: 'solid 1px #000',
};

storiesOf('コンポーネント', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('ユーザー情報', () => (<div>
    <div style={formContainerStyle}>
      <UserInfoPanel
        user={{ name: 'User1', lastUpdate: new Date('2017-03-01'),
                dept: 'テキスト ・ テキスト・ テキスト ・ テキスト・ テキスト ・ テキスト' ,
                position: 'テキストテキスト', tel: '09000000', email: 'Name@mail.com' }}
        infoLink= ''
      />
    </div>
  </div>));
