import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import { d3treePropTypes } from './propTypes';
import D3Tree from './D3Tree';
import styles from './D3Tree.css';

class D3TreeContainer extends Component {

  static propTypes = {
    map: d3treePropTypes.isRequired,
    companyId: PropTypes.number.isRequired,
  };

  render() {
    return (
      <div className={styles.container}>
        <D3Tree map={this.props.map} companyId={this.props.companyId} />
      </div>);
  }
}

const mapStateToProps = subject => (state) => {
  const { [subject]: map = {} } = state.map || {};
  const { companyId } = state.auth;
  return { map, companyId };
};

export const UserD3TreeContainer = connect(
  mapStateToProps('user'),
)(D3TreeContainer);

export const GroupD3TreeContainer = connect(
  mapStateToProps('group'),
)(D3TreeContainer);

export const CompanyD3TreeContainer = connect(
  mapStateToProps('company'),
)(D3TreeContainer);
