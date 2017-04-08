import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import GroupBar from '../../src/common/component/GroupBar';

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
  .add('グループバー', () => (<div>
    <div style={formContainerStyle}>
      <GroupBar
        name='Group1' lastUpdate={new Date('2017-03-01')} progress={68}
      />
    </div>
  </div>));
