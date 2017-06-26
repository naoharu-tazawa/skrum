import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Header from './Header';
import { timeframesPropTypes } from './propTypes';
import BasicModalDialog from '../../dialogs/BasicModalDialog';
import NewOKR from '../../project/OKR/NewOKR/NewOKR';
import { logout } from '../../auth/action';

class HeaderContainer extends Component {

  static propTypes = {
    timeframes: timeframesPropTypes,
    dispatchLogout: PropTypes.func,
    pathname: PropTypes.string,
  };

  handleAddOkrOpen() {
    this.setState({ isAddOkrModalOpen: true });
  }

  handleAddOkrClose() {
    this.setState({ isAddOkrModalOpen: false });
  }

  render() {
    const { timeframes = [] } = this.props;
    const { isAddOkrModalOpen = false } = this.state || {};
    return (
      <div>
        <Header
          timeframes={timeframes}
          handleAdd={this.handleAddOkrOpen.bind(this)}
          handleLogoutSubmit={this.props.dispatchLogout}
        />
        {isAddOkrModalOpen && (
          <BasicModalDialog onClose={this.handleAddOkrClose.bind(this)}>
            <NewOKR type="Okr" onClose={this.handleAddOkrClose.bind(this)} />
          </BasicModalDialog>
        )}
      </div>
    );
  }
}

const mapStateToProps = (state) => {
  const { timeframes = [] } = state.top.data || {};
  return { timeframes };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchLogout = () => dispatch(logout());
  return { dispatchLogout };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(HeaderContainer);
