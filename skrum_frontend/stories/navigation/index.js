import React from 'react';
import { storiesOf, action } from '@kadira/storybook';
import SideBar from '../../src/navigation/sidebar/SideBar';
import Header from '../../src/navigation/header/Header';

const sections = [
  {
    title: 'プロジェクトチーム',
    items: [
      {
        title: 'TeamNameA',
        imgSrc: 'http://icons.iconarchive.com/icons/ariil/alphabet/256/Letter-A-icon.png',
      },
      {
        title: 'TeamNameB',
        imgSrc: 'http://icons.iconarchive.com/icons/ariil/alphabet/256/Letter-B-icon.png',
      },
      {
        title: 'TeamNameC',
        imgSrc: 'http://icons.iconarchive.com/icons/ariil/alphabet/256/Letter-C-icon.png',
      },
    ],
  },
  {
    title: '部署',
    items: [
      {
        title: 'DepartmentA',
        imgSrc: 'http://icons.iconarchive.com/icons/ariil/alphabet/256/Letter-A-icon.png',
      },
      {
        title: 'DepartmentB',
        imgSrc: 'http://icons.iconarchive.com/icons/ariil/alphabet/256/Letter-B-icon.png',
      },
      {
        title: 'DepartmentC',
        imgSrc: 'http://icons.iconarchive.com/icons/ariil/alphabet/256/Letter-C-icon.png',
      },
    ],
  },
];

storiesOf('ナビゲーション', module)
  .add('サイドバー', () => (
    <div style={{width: '200px'}}>
        <SideBar
          isOpen
          onClickToggle={action('開閉ボタンが押されました')}
          userName={'スクラム太郎'}
          companyName={'スクラムジャパン.Inc'}
          sections={sections}
        />
    </div>))
  .add('ヘッダー', () => (<div>
    <Header activeMenu="okr" />
  </div>))
;
