import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty, partial } from 'lodash';
import SearchDropdown from '../../components/SearchDropdown';
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
    owner: ownerPropType,
    timeframeId: PropTypes.number.isRequired,
    okrs: PropTypes.arrayOf(okrPropType),
    value: PropTypes.oneOfType([okrPropType, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    disabled: PropTypes.bool,
    dispatchSearchOkr: PropTypes.func,
    dispatchSearchParentOkr: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { timeframeId, owner, okrs = [], value, onChange, onFocus, onBlur, disabled,
      dispatchSearchOkr, dispatchSearchParentOkr, isSearching } = this.props;
    const { currentInput = (value || {}).name } = this.state || {};
    const dispatcher = owner ? partial(dispatchSearchParentOkr, owner) : dispatchSearchOkr;
    return (
      <SearchDropdown
        items={isEmpty(currentInput) ? [] : okrs}
        labelPropName="name"
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={val => !isEmpty(val) && dispatcher(timeframeId, val)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && value)}
        {...{ onFocus, onBlur, disabled }}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state, { timeframeId }) => {
  const { isSearching, data = [] } = state.okrsFound || {};
  const stateProps = { okrs: data.map(okr => mapOKR(okr)), isSearching };
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
