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
    const map = [
      {
        name: 'Top Level',
        parent: 'null',
        children: [
          {
            name: 'Level 2: A',
            parent: 'Top Level',
            children: [
              {
                name: 'Son of A',
                parent: 'Level 2: A',
              },
              {
                name: 'Daughter of A',
                parent: 'Level 2: A',
              },
            ],
          },
          {
            name: 'Level 2: B',
            parent: 'Top Level',
          },
        ],
      },
    ];

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
