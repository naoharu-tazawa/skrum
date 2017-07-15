import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { groupPropTypes } from './propTypes';
import styles from './GroupInfoEdit.css';
import InlineTextInput from '../../../editors/InlineTextInput';
import InlineTextArea from '../../../editors/InlineTextArea';
import { replacePath } from '../../../util/RouteUtil';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';

export default class GroupInfoEdit extends Component {

  static propTypes = {
    group: groupPropTypes.isRequired,
    infoLink: PropTypes.string.isRequired,
    dispatchPutGroup: PropTypes.func.isRequired,
  };

  render() {
    const { group, dispatchPutGroup } = this.props;
    const { groupId, name, groupPaths, mission, leaderName, lastUpdate } = group;
    const groupPathsLink = groupPaths.map(({ groupTreeId, groupPath }) =>
      <ul key={groupTreeId}>
        {groupPath.map(({ id, name: groupName }, index) =>
          <li key={id}>
            <Link
              to={replacePath({ subject: !index ? 'company' : 'group', id })}
              className={styles.groupLink}
            >
              {groupName}
            </Link>
            { index === (groupPath.length - 1) ? <span className={styles.delete}><img src="/img/delete.svg" alt="" width="10px" /></span> : null }
          </li>)}
      </ul>);
    return (
      <section className={styles.profile_box}>
        <h1 className={styles.ttl_setion}>基本情報</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <div className={styles.profile_img}>
            <div><img src="/img/profile/img_profile.jpg" alt="" /></div>
            <p>最終更新: {toRelativeTimeText(lastUpdate)}</p>
          </div>
          <div className={styles.profile_txt}>
            <h2 className={styles.team_name}>
              <InlineTextInput
                value={name}
                required
                onSubmit={(value, completion) =>
                  dispatchPutGroup(groupId, { name: value }).then(completion)}
              />
            </h2>
            <nav className={styles.breads}>
              {groupPathsLink}
            </nav>
            <div className={styles.add_belonging}>所属先グループを追加</div>
            <div>
              <div className={styles.title}>ミッション</div>
              <div className={styles.txt}>
                <InlineTextArea
                  value={mission}
                  placeholder="ミッションを入力してください"
                  maxLength={250}
                  onSubmit={(value, completion) =>
                    dispatchPutGroup(groupId, { mission: value }).then(completion)}
                />
              </div>
            </div>
            <div className={`${styles.leader} ${styles.cf}`}>
              <div><img src="/img/profile/img_leader.jpg" alt="" /></div>
              <dl>
                <dt>リーダー</dt>
                <dd>
                  {leaderName}
                  <span className={styles.btn}>
                    <button className={styles.hover}>
                      <img src="/img/profile/icn_write.png" alt="" width="25" />
                    </button>
                  </span>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </section>
    );
  }
}
