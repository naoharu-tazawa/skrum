import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import GroupList from '../../src/common/container/GroupList';

const style = {
  padding: '20px',
  backgroundColor: 'gray',
  height: '90vh',
};

const formContainerStyle = {
  float: 'left',
  marginLeft: '20px',
//  height: '300px',
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
  .add('所属グループ一覧', () => (<div>
    <div style={formContainerStyle}>
      <GroupList
        list={[
          { name: 'Group1', progress: 60, lastUpdate: new Date('2017-03-01') },
          { name: 'Group2', progress: 80, lastUpdate: new Date('2017-03-02') },
          { name: 'Group3', progress: 30, lastUpdate: new Date('2017-03-03') },
          { name: 'Group4', progress: 30, lastUpdate: new Date('2017-03-04') },
          { name: 'Group5', progress: 20, lastUpdate: new Date('2017-03-05') },
          { name: 'Group6', progress: 70, lastUpdate: new Date('2017-03-06') },
        ]}
      />
    </div>
  </div>));
