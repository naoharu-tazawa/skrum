import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Header from './Header';
import NewOKR from '../../project/OKR/NewOKR/NewOKR';
import { logout } from '../../auth/action';
import { withModal } from '../../util/ModalUtil';

class HeaderContainer extends Component {

  static propTypes = {
    openModal: PropTypes.func.isRequired,
    dispatchLogout: PropTypes.func.isRequired,
  };

  render() {
    const { openModal, dispatchLogout } = this.props;
    return (
      <div>
        <Header
          onAdd={() => openModal(NewOKR, { type: 'Okr' })}
          handleLogoutSubmit={dispatchLogout}
        />
      </div>
    );
  }
}

const mapDispatchToProps = (dispatch) => {
  const dispatchLogout = () => dispatch(logout());
  return { dispatchLogout };
};

export default connect(
  null,
  mapDispatchToProps,
)(withModal(HeaderContainer));
