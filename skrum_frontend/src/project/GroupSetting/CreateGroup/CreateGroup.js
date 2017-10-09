import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { toastr } from 'react-redux-toastr';
import { toPairs } from 'lodash';
import PathSearch from '../../PathSearch/PathSearch';
import { isBasicRole } from '../../../util/UserUtil';
import { GroupTypeName } from '../../../util/GroupUtil';
import styles from './CreateGroup.css';

export default class CreateGroup extends Component {

  static propTypes = {
    isPostingGroup: PropTypes.bool.isRequired,
    roleLevel: PropTypes.number.isRequired,
    dispatchCreateGroup: PropTypes.func.isRequired,
  };

  render() {
    const { isPostingGroup, roleLevel, dispatchCreateGroup } = this.props;
    const isBasicUser = isBasicRole(roleLevel);
    const { groupName = '', groupType = isBasicUser && '2', groupPath = {} } = this.state || {};
    const { groupPathId } = groupPath;
    const groupOrTeam = isBasicUser ? 'チーム' : 'グループ';
    return (
      <section>
        <div className={styles.title}>
          {`${groupOrTeam}作成`}
        </div>
        <div>
          <input
            className={styles.input}
            type="text"
            placeholder={`${groupOrTeam}名`}
            value={groupName}
            onChange={e => this.setState({ groupName: e.target.value })}
          />
          {toPairs(GroupTypeName).map(([type, name]) => !isBasicUser && (
            <label className={styles.radio} key={type}>
              <input
                type="radio"
                checked={groupType === type}
                onClick={() => this.setState({ groupType: type })}
              />
              {name}
            </label>))}
        </div>
        <div className={styles.group_search}>
          <span className={styles.group_search_label}>所属先グループ検索：</span>
          <span className={styles.group_search_box}>
            <PathSearch value={groupPath} onChange={value => this.setState({ groupPath: value })} />
          </span>
          {!isPostingGroup && (
            <button
              className={styles.btn}
              disabled={!groupName || !groupType || !groupPathId}
              onClick={() => dispatchCreateGroup({ groupName, groupType, groupPathId })
                .then(({ error /* , payload: { message } = {} */ } = {}) => {
                  if (error) { toastr.error('グループ作成に失敗しました'); }
                  if (!error) { toastr.info('グループを作成しました'); }
                  if (!error) { this.setState({ groupName: '' }); }
                })}
            >
              作成する
            </button>)}
          {isPostingGroup && <div className={styles.disable_btn} />}
        </div>
        <div className={styles.search_notice}>※会社に直接所属させる場合は会社名で検索してください</div>
      </section>
    );
  }
}
