import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Header from './Header';
import NewOKR from '../../project/OKR/NewOKR/NewOKR';
import { logout } from '../../auth/action';
import { withModal } from '../../util/ModalUtil';

class HeaderContainer extends Component {

  static propTypes = {
    pathname: PropTypes.string.isRequired,
    currentUserId: PropTypes.number.isRequired,
    roleLevel: PropTypes.number.isRequired,
    openModal: PropTypes.func.isRequired,
    dispatchLogout: PropTypes.func.isRequired,
  };

  render() {
    const { pathname, currentUserId, roleLevel, openModal, dispatchLogout } = this.props;
    return (
      <div>
        <Header
          pathname={pathname}
          currentUserId={currentUserId}
          roleLevel={roleLevel}
          onAdd={() => openModal(NewOKR, { type: 'Okr' })}
          handleLogoutSubmit={dispatchLogout}
        />
      </div>
    );
  }
}

const mapStateToProps = (state) => {
  const { userId: currentUserId } = state.auth || {};
  return { currentUserId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchLogout = () => dispatch(logout());
  return { dispatchLogout };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(withModal(HeaderContainer));
