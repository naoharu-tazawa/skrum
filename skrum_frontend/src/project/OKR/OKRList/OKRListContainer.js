import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrsPropTypes } from './propTypes';
import OKRList from './OKRList';
import NewOKR from '../NewOKR/NewOKR';
import { withBasicModalDialog } from '../../../util/FormUtil';
import { mapOKR } from '../../../util/OKRUtil';
import { EntityType } from '../../../util/EntityUtil';

class OKRListContainer extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
    ownerName: PropTypes.string,
  };

  render() {
    const { okrs = [], ownerName } = this.props;
    const { addOkrModal = null } = this.state || {};
    return (
      <div>
        <OKRList
          okrs={okrs}
          onAdd={() => this.setState({ addOkrModal:
            withBasicModalDialog(NewOKR, () => this.setState({ addOkrModal: null }), { type: 'Okr', ownerName }) })}
        />
        {addOkrModal}
      </div>);
  }
}

const mapBasicsStateToProps = (subject, ownerType) => (state) => {
  const { [subject]: basics = {} } = state.basics || {};
  const { okrs = [] } = basics || {};
  const { name, firstName, lastName } = basics[subject] || {};
  return { okrs: okrs.map(okr => mapOKR({ ...okr, ownerType })),
    ownerName: name || `${lastName} ${firstName}` };
};

export const UserOKRListContainer = connect(
  mapBasicsStateToProps('user', EntityType.USER),
)(OKRListContainer);

export const GroupOKRListContainer = connect(
  mapBasicsStateToProps('group', EntityType.GROUP),
)(OKRListContainer);

export const CompanyOKRListContainer = connect(
  mapBasicsStateToProps('company', EntityType.COMPANY),
)(OKRListContainer);
