import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import { fetchUserOkrs, fetchGroupOkrs, fetchCompanyOkrs } from './action';
import { UserD3TreeContainer, GroupD3TreeContainer, CompanyD3TreeContainer } from './D3Tree/D3TreeContainer';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import styles from './MapContainer.css';

class MapContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    subject: PropTypes.string,
    dispatchFetchUserOkrs: PropTypes.func,
    dispatchFetchGroupOkrs: PropTypes.func,
    dispatchFetchCompanyOkrs: PropTypes.func,
    pathname: PropTypes.string,
  };

  componentWillMount() {
    const { pathname } = this.props;
    if (isPathFinal(pathname)) {
      this.fetchOkrs(pathname);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (this.props.pathname !== pathname) {
      this.fetchOkrs(pathname);
    }
  }

  fetchOkrs(pathname) {
    const {
      dispatchFetchUserOkrs,
      dispatchFetchGroupOkrs,
      dispatchFetchCompanyOkrs,
    } = this.props;
    const { subject, id, timeframeId } = explodePath(pathname);
    switch (subject) {
      case 'user':
        dispatchFetchUserOkrs(id, timeframeId);
        break;
      case 'group':
        dispatchFetchGroupOkrs(id, timeframeId);
        break;
      case 'company':
        dispatchFetchCompanyOkrs(id, timeframeId);
        break;
      default:
        break;
    }
  }

  renderD3TreeContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserD3TreeContainer />;
      case 'group':
        return <GroupD3TreeContainer />;
      case 'company':
        return <CompanyD3TreeContainer />;
      default:
        return null;
    }
  }

  render() {
    if (this.props.isFetching) {
      return <span className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        {this.renderD3TreeContainer()}
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching = false } = state.map || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { isFetching, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserOkrs = (userId, timeframeId) =>
    dispatch(fetchUserOkrs(userId, timeframeId));
  const dispatchFetchGroupOkrs = (groupId, timeframeId) =>
    dispatch(fetchGroupOkrs(groupId, timeframeId));
  const dispatchFetchCompanyOkrs = (companyId, timeframeId) =>
    dispatch(fetchCompanyOkrs(companyId, timeframeId));
  return {
    dispatchFetchUserOkrs,
    dispatchFetchGroupOkrs,
    dispatchFetchCompanyOkrs,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(MapContainer);
