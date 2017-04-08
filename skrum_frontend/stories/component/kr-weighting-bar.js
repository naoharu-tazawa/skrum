import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import KRWeightingBar from '../../src/common/component/KRWeightingBar';

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
  .add('KRの加重平均重要度設定バー', () => (<div>
    <div style={formContainerStyle}>
      <KRWeightingBar
        title='目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー'
        weighting={60} isLocked={false} user={{ name: 'User1'}}
      />
    </div>
  </div>));
