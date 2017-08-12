import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrsPropTypes } from './propTypes';
import OKRList from './OKRList';
import NewOKR from '../NewOKR/NewOKR';
import { withModal } from '../../../util/ModalUtil';
import { mapOKR } from '../../../util/OKRUtil';
import { EntityType } from '../../../util/EntityUtil';

class OKRListContainer extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
    ownerName: PropTypes.string,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { okrs = [], ownerName, openModal } = this.props;
    return (
      <div>
        <OKRList
          okrs={okrs}
          onAddOkr={() => openModal(NewOKR, { type: 'Okr', ownerName })}
          onAddParentedOkr={okr => openModal(NewOKR,
            { type: 'Okr', parentOkr: okr, differingParentOkrOwner: true })}
        />
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
)(withModal(OKRListContainer));

export const GroupOKRListContainer = connect(
  mapBasicsStateToProps('group', EntityType.GROUP),
)(withModal(OKRListContainer));

export const CompanyOKRListContainer = connect(
  mapBasicsStateToProps('company', EntityType.COMPANY),
)(withModal(OKRListContainer));
