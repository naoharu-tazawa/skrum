import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrsPropTypes } from './propTypes';
import OKRList from './OKRList';
import NewOKR from '../NewOKR/NewOKR';
import Permissible from '../../../components/Permissible';
import { withModal } from '../../../util/ModalUtil';
import { mapOKR } from '../../../util/OKRUtil';
import { EntityType } from '../../../util/EntityUtil';
import { groupTypePropType } from '../../../util/GroupUtil';
import { changeOkrOwner, changeParentOkr, changeDisclosureType, setRatios, copyOkr, deleteOkr, deleteKR } from '../action';

class OKRListContainer extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
    subject: PropTypes.string,
    id: PropTypes.number,
    ownerType: PropTypes.string,
    ownerName: PropTypes.string,
    roleLevel: PropTypes.number,
    groupType: groupTypePropType,
    dispatchChangeOkrOwner: PropTypes.func.isRequired,
    dispatchChangeKROwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchSetRatios: PropTypes.func.isRequired,
    dispatchCopyOkr: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
    dispatchDeleteKR: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { okrs = [], subject, id, ownerType, ownerName, roleLevel, groupType,
      dispatchChangeOkrOwner, dispatchChangeKROwner, dispatchChangeParentOkr,
      dispatchChangeDisclosureType, dispatchSetRatios, dispatchCopyOkr, dispatchDeleteOkr,
      dispatchDeleteKR, openModal } = this.props;
    const onAddOkr = () => openModal(NewOKR, { type: 'Okr', ownerName });
    return (
      <Permissible entity={{ id, type: ownerType, roleLevel, groupType }}>
        {({ permitted }) => (
          <OKRList
            {...{
              okrs,
              subject,
              dispatchChangeOkrOwner,
              dispatchChangeKROwner,
              dispatchChangeParentOkr,
              dispatchChangeDisclosureType,
              dispatchSetRatios,
              dispatchCopyOkr,
              dispatchDeleteOkr,
              dispatchDeleteKR,
            }}
            onAddOkr={permitted ? onAddOkr : null}
            onAddParentedOkr={okr => openModal(NewOKR,
              { type: 'Okr', parentOkr: okr, differingParentOkrOwner: true })}
          />)}
      </Permissible>);
  }
}

const mapBasicsStateToProps = (subject, ownerType) => (state) => {
  const { [subject]: basics = {} } = state.basics || {};
  const okrs = (basics.okrs || []).map(okr => mapOKR({ ...okr, ownerType }));
  const { [`${subject}Id`]: id = 0, name, firstName, lastName, roleLevel, groupType } = basics[subject] || {};
  const ownerName = name || `${lastName} ${firstName}`;
  return { okrs, subject, id, ownerType, ownerName, roleLevel, groupType };
};

const mapDispatchToProps = subject => (dispatch) => {
  const dispatchChangeOkrOwner = (id, owner) =>
    dispatch(changeOkrOwner(subject, id, owner));
  const dispatchChangeKROwner = (id, owner) =>
    dispatch(changeOkrOwner(subject, id, owner));
  const dispatchChangeParentOkr = (id, newParentOkrId) =>
    dispatch(changeParentOkr(subject, id, newParentOkrId));
  const dispatchChangeDisclosureType = (id, disclosureType) =>
    dispatch(changeDisclosureType(subject, id, disclosureType));
  const dispatchSetRatios = (id, ratios, unlockedRatio) =>
    dispatch(setRatios(subject, id, ratios, unlockedRatio));
  const dispatchCopyOkr = (id, timeframeId) =>
    dispatch(copyOkr(subject, id, timeframeId));
  const dispatchDeleteOkr = id =>
    dispatch(deleteOkr(subject, id));
  const dispatchDeleteKR = id =>
    dispatch(deleteKR(subject, id));
  return {
    dispatchChangeOkrOwner,
    dispatchChangeKROwner,
    dispatchChangeParentOkr,
    dispatchChangeDisclosureType,
    dispatchSetRatios,
    dispatchCopyOkr,
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
