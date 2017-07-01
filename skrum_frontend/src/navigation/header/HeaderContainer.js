import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Header from './Header';
import NewOKR from '../../project/OKR/NewOKR/NewOKR';
import { withBasicModalDialog } from '../../util/FormUtil';
import { logout } from '../../auth/action';

class HeaderContainer extends Component {

  static propTypes = {
    dispatchLogout: PropTypes.func,
  };

  render() {
    const { dispatchLogout } = this.props;
    const { addOkrModal = null } = this.state || {};
    return (
      <div>
        <Header
          onAdd={() => this.setState({ addOkrModal:
            withBasicModalDialog(NewOKR, () => this.setState({ addOkrModal: null }), { type: 'Okr' }) })}
          handleLogoutSubmit={dispatchLogout}
        />
        {addOkrModal}
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
)(HeaderContainer);
