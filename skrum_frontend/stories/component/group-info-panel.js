import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import GroupInfoPanel from '../../src/common/component/GroupInfoPanel';

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
  .add('グループ情報', () => (<div>
    <div style={formContainerStyle}>
      <GroupInfoPanel
        group={{ name: 'Group1', lastUpdate: new Date('2017-03-01'),
                 company: '会社', dept: '所属部署' ,
                 mission: 'テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキス',
                 leader: 'User1' }}
        infoLink= ''
      />
    </div>
  </div>));
