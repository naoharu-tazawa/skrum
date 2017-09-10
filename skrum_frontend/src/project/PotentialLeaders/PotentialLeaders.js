import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { find, isEmpty } from 'lodash';
import SearchDropdown from '../../components/SearchDropdown';
import EntitySubject from '../../components/EntitySubject';
import { EntityType } from '../../util/EntityUtil';
import { getPotentialLeaders } from './action';

export const leaderPropType = PropTypes.shape({
  userId: PropTypes.number, // fixme .isRequired,
  name: PropTypes.string,
});

class PotentialLeaders extends PureComponent {

  static propTypes = {
    groupId: PropTypes.number.isRequired,
    leaderUserId: PropTypes.number,
    leaderName: PropTypes.string,
    potentialLeadersFound: PropTypes.arrayOf(leaderPropType),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    dispatchGetPotentialLeaders: PropTypes.func,
    isGetting: PropTypes.bool,
  };

  componentDidMount() {
    const { groupId, dispatchGetPotentialLeaders } = this.props;
    dispatchGetPotentialLeaders(groupId);
  }

  render() {
    const { potentialLeadersFound = [], leaderUserId, leaderName: currentLeaderName,
      onChange, onFocus, onBlur, isGetting } = this.props;
    const leaderName = ((find(potentialLeadersFound, { userId: leaderUserId }) || {}).name) ||
      currentLeaderName;
    const { currentInput = leaderName || '' } = this.state || {};
    const type = EntityType.USER;
    return (
      <SearchDropdown
        items={isGetting ? [] : potentialLeadersFound}
        labelPropName="name"
        renderItem={({ userId: id, name }) =>
          <EntitySubject entity={{ type, id, name }} local plain avatarSize={20} />}
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={() => {}}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && { value: { userId: leaderUserId, name: leaderName } })}
        {...{ onFocus, onBlur }}
        isSearching={isGetting}
      />
    );
  }
}

const mapStateToProps = (state, props) => {
  const { isGetting, groupId, data = [] } = state.potentialLeaders || {};
  return { potentialLeadersFound: data, isGetting: isGetting || groupId !== props.groupId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchGetPotentialLeaders = groupId => dispatch(getPotentialLeaders(groupId));
  return { dispatchGetPotentialLeaders };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PotentialLeaders);
