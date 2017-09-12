import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty, partial, includes } from 'lodash';
import SearchDropdown from '../../editors/SearchDropdown';
import EntitySubject from '../../components/EntitySubject';
import { mapOKR } from '../../util/OKRUtil';
import { explodePath } from '../../util/RouteUtil';
import { searchOkr, searchParentOkr } from './action';

const ownerPropType = PropTypes.shape({
  id: PropTypes.number, // fixme .isRequired,
  type: PropTypes.string, // fixme .isRequired,
});

const okrPropType = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  detail: PropTypes.string,
  owner: PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
    type: PropTypes.string.isRequired,
  }).isRequired,
});

class OKRSearch extends PureComponent {

  static propTypes = {
    timeframeId: PropTypes.number.isRequired,
    owner: ownerPropType,
    exclude: PropTypes.arrayOf(PropTypes.number),
    okrsFound: PropTypes.arrayOf(okrPropType),
    value: PropTypes.oneOfType([okrPropType, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    disabled: PropTypes.bool,
    tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    dispatchSearchOkr: PropTypes.func,
    dispatchSearchParentOkr: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { timeframeId, owner, okrsFound = [], value, onChange, onFocus, onBlur, disabled,
      tabIndex, dispatchSearchOkr, dispatchSearchParentOkr, isSearching } = this.props;
    const { currentInput = (value || {}).name } = this.state || {};
    const dispatcher = owner ? partial(dispatchSearchParentOkr, owner) : dispatchSearchOkr;
    return (
      <SearchDropdown
        items={isEmpty(currentInput) ? [] : okrsFound}
        labelPropName="name"
        renderItem={okr =>
          <EntitySubject entity={okr.owner} subject={okr.name} local plain avatarSize={20} />}
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={keyword => !isEmpty(keyword) && dispatcher(timeframeId, keyword)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && value)}
        {...{ onFocus, onBlur, disabled }}
        tabIndex={`${tabIndex}`}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state, { timeframeId, exclude = [] }) => {
  const { isSearching, data = [] } = state.okrsFound || {};
  const stateProps = {
    isSearching,
    okrsFound: data.map(okr => mapOKR(okr)).filter(({ id }) => !includes(exclude, id)),
  };
  if (timeframeId) return stateProps;
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { ...stateProps, timeframeId: explodePath(pathname).timeframeId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSearchOkr = (timeframeId, keyword) =>
    dispatch(searchOkr(timeframeId, keyword));
  const dispatchSearchParentOkr = (owner, timeframeId, keyword) =>
    dispatch(searchParentOkr(owner.type, owner.id, timeframeId, keyword));
  return { dispatchSearchOkr, dispatchSearchParentOkr };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(OKRSearch);
