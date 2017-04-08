import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import KRWeightingSetting from '../../src/common/dialog/KRWeightingSetting';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
  height: '60px',
  padding: '20px',
  border: 'solid 1px #000',
};

storiesOf('ダイアローグ', module)
  .addDecorator(story => (
    <div style={style}>
      {story()}
    </div>
  ))
  .add('KRの加重平均重要度設定', () => (<div>
    <div style={formContainerStyle}>
      <KRWeightingSetting
        title='目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー'
        weightings={[
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            weighting: 60, isLocked: false, user: { name: 'User2'} },
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            weighting: 20, isLocked: false, user: { name: 'User3'} },
          { title: '目標テキストダミーー目標テキストダミーー目標テキストダミーー目標テキストダミーー',
            weighting: 20, isLocked: false, user: { name: 'User4'} },
        ]}
        user={{ name: 'User1' }}
        handleSubmit={action('KRの加重平均重要度設定ボタンが押されました')}
      />
    </div>
  </div>));
