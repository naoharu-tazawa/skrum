import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import SideBar from '../../src/common/sidebar/SideBar';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
  width: '200px',
  padding: '20px',
  border: 'solid 1px #000',
};

const barItems = [
  { title: 'User1', urlOrFunc: () => {}, category: 'main' },
  { title: 'グループ', urlOrFunc: () => {}, category: 'main' },
  { title: '①グループ', urlOrFunc: () => {} },
  { title: '②グループ', urlOrFunc: () => {} },
  { title: '部署', urlOrFunc: () => {}, category: 'main' },
  { title: 'A部', urlOrFunc: () => {} },
  { title: 'B部', urlOrFunc: () => {} },
  { title: '会社', urlOrFunc: () => {}, category: 'main' },
]

storiesOf('コンポーネント', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('サイドバー', () => (<div>
    <div style={formContainerStyle}>
      <SideBar isOpen={true} barItems={barItems} />
    </div>
  </div>));
