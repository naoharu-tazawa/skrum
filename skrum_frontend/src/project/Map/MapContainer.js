import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import D3Tree from './D3Tree/D3Tree';
import { fetchUserObjectives, fetchGroupObjectives, fetchCompanyObjectives } from './action';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import styles from './MapContainer.css';

class MapContainer extends Component {

  static propTypes = {
    subject: PropTypes.string,
    dispatchFetchUserObjectives: PropTypes.func,
    dispatchFetchGroupObjectives: PropTypes.func,
    dispatchFetchCompanyObjectives: PropTypes.func,
    pathname: PropTypes.string,
  };

  componentWillMount() {
    const { pathname } = this.props;
    if (isPathFinal(pathname)) {
      this.fetchObjectives(pathname);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (this.props.pathname !== pathname) {
      this.fetchObjectives(pathname);
    }
  }

  fetchObjectives(pathname) {
    const {
      dispatchFetchUserObjectives,
      dispatchFetchGroupObjectives,
      dispatchFetchCompanyObjectives,
    } = this.props;
    const { section, id, timeframeId } = explodePath(pathname);
    switch (section) {
      case 'user':
        dispatchFetchUserObjectives(id, timeframeId);
        break;
      case 'group':
        dispatchFetchGroupObjectives(id, timeframeId);
        break;
      case 'company':
        dispatchFetchCompanyObjectives(id, timeframeId);
        break;
      default:
        break;
    }
  }

  render() {
    const map =
      {
        okrId: 1,
        okrName: 'ああああああああああああああああああああああああああああああああああああ',
        achievementRate: 30.0,
        ownerType: '1',
        ownerUserId: 1,
        ownerUserName: '田澤尚治',
        children: [
          {
            okrId: 1,
            okrName: 'いいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいいい',
            achievementRate: 30.0,
            ownerType: '1',
            ownerUserId: 1,
            ownerUserName: '田澤尚治',
            parent: 1,
            children: [
              {
                okrId: 1,
                okrName: 'Son of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Daughter of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Son of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Daughter of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Son of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Daughter of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Son of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Daughter of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Son of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Daughter of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
              },
              {
                okrId: 1,
                okrName: 'Son of A',
                achievementRate: 30.0,
                ownerType: '1',
                ownerUserId: 1,
                ownerUserName: '田澤尚治',
                children: [
                  {
                    okrId: 1,
                    okrName: 'Son of A',
                    achievementRate: 30.0,
                    ownerType: '1',
                    ownerUserId: 1,
                    ownerUserName: '田澤尚治',
                  },
                  {
                    okrId: 1,
                    okrName: 'Son of A',
                    achievementRate: 30.0,
                    ownerType: '1',
                    ownerUserId: 1,
                    ownerUserName: '田澤尚治',
                  },
                  {
                    okrId: 1,
                    okrName: 'Son of A',
                    achievementRate: 30.0,
                    ownerType: '1',
                    ownerUserId: 1,
                    ownerUserName: '田澤尚治',
                  },
                  {
                    okrId: 1,
                    okrName: 'Son of A',
                    achievementRate: 30.0,
                    ownerType: '1',
                    ownerUserId: 1,
                    ownerUserName: '田澤尚治',
                  },
                  {
                    okrId: 1,
                    okrName: 'Son of A',
                    achievementRate: 30.0,
                    ownerType: '1',
                    ownerUserId: 1,
                    ownerUserName: '田澤尚治',
                  },
                ],
              },
            ],
          },
          {
            okrId: 1,
            okrName: 'Level 2: B',
            achievementRate: 30.0,
            ownerType: '1',
            ownerUserId: 1,
            ownerUserName: '田澤尚治',
          },
        ],
      };

    return (
      <div className={styles.container}>
        <D3Tree map={map} />
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserObjectives = (userId, timeframeId) =>
    dispatch(fetchUserObjectives(userId, timeframeId));
  const dispatchFetchGroupObjectives = (groupId, timeframeId) =>
    dispatch(fetchGroupObjectives(groupId, timeframeId));
  const dispatchFetchCompanyObjectives = (companyId, timeframeId) =>
    dispatch(fetchCompanyObjectives(companyId, timeframeId));
  return {
    dispatchFetchUserObjectives,
    dispatchFetchGroupObjectives,
    dispatchFetchCompanyObjectives,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(MapContainer);
