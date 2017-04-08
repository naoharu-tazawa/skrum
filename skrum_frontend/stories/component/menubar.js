import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import MenuBar from '../../src/common/component/MenuBar';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
  height: '62px',
  width: '800px',
  padding: '20px',
  border: 'solid 1px #000',
};

const barItems = [
  { title: 'ホーム', urlOrFunc: () => {} },
  { title: 'マップ', urlOrFunc: () => {} },
  { title: 'タイムライン', urlOrFunc: () => {} },
  { title: 'プロファイル', urlOrFunc: () => {} },
]

storiesOf('コンポーネント', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('メニューバー', () => (<div>
    <div style={formContainerStyle}>
      <MenuBar isOpen={true} barItems={barItems} user={{ name: 'User1' }} />
    </div>
  </div>));
