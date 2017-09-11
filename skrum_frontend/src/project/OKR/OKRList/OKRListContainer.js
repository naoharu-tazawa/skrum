import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrsPropTypes } from './propTypes';
import OKRList from './OKRList';
import NewOKR from '../NewOKR/NewOKR';
import { withModal } from '../../../util/ModalUtil';
import { mapOKR } from '../../../util/OKRUtil';
import { EntityType } from '../../../util/EntityUtil';
import { changeOkrOwner, changeDisclosureType, setRatios, deleteOkr, deleteKR } from '../action';
import { changeParentOkr } from '../../OKRDetails/action'; // TODO move this to OKR action

class OKRListContainer extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
    ownerName: PropTypes.string,
    dispatchChangeOkrOwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchSetRatios: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
    dispatchDeleteKR: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { okrs = [], ownerName, dispatchChangeOkrOwner, dispatchChangeParentOkr,
      dispatchChangeDisclosureType, dispatchSetRatios, dispatchDeleteOkr, dispatchDeleteKR,
      openModal } = this.props;
    return (
      <div>
        <OKRList
          {...{
            okrs,
            dispatchChangeOkrOwner,
            dispatchChangeParentOkr,
            dispatchChangeDisclosureType,
            dispatchSetRatios,
            dispatchDeleteOkr,
            dispatchDeleteKR,
          }}
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

const mapDispatchToProps = subject => (dispatch) => {
  const dispatchChangeOkrOwner = (id, owner) =>
    dispatch(changeOkrOwner(subject, id, owner));
  const dispatchChangeParentOkr = (id, newParentOkrId) =>
    dispatch(changeParentOkr(id, newParentOkrId));
  const dispatchChangeDisclosureType = (id, disclosureType) =>
    dispatch(changeDisclosureType(subject, id, disclosureType));
  const dispatchSetRatios = (id, ratios) =>
    dispatch(setRatios(subject, id, ratios));
  const dispatchDeleteOkr = id =>
    dispatch(deleteOkr(subject, id));
  const dispatchDeleteKR = id =>
    dispatch(deleteKR(subject, id));
  return {
    dispatchChangeOkrOwner,
    dispatchChangeParentOkr,
    dispatchChangeDisclosureType,
    dispatchSetRatios,
    dispatchDeleteOkr,
    dispatchDeleteKR,
  };
};

export const UserOKRListContainer = connect(
  mapBasicsStateToProps('user', EntityType.USER),
  mapDispatchToProps('user'),
)(withModal(OKRListContainer));

export const GroupOKRListContainer = connect(
  mapBasicsStateToProps('group', EntityType.GROUP),
  mapDispatchToProps('group'),
)(withModal(OKRListContainer));

export const CompanyOKRListContainer = connect(
  mapBasicsStateToProps('company', EntityType.COMPANY),
  mapDispatchToProps('company'),
)(withModal(OKRListContainer));
