import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty, partial, flatten, includes } from 'lodash';
import SearchDropdown from '../../editors/SearchDropdown';
import EntitySubject from '../../components/EntitySubject';
import { EntityType, EntityTypeName, entityPropTypes, entityTypePropType } from '../../util/EntityUtil';
import { mapOKR } from '../../util/OKRUtil';
import { explodePath } from '../../util/RouteUtil';
import { searchOkr, searchParentOkr } from './action';

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
    owner: entityPropTypes,
    defaultOKRs: PropTypes.arrayOf(okrPropType),
    keyword: PropTypes.string,
    exclude: PropTypes.arrayOf(PropTypes.number),
    loadBasicsOKRs: entityTypePropType,
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
    const { timeframeId, owner, defaultOKRs, keyword, okrsFound,
      value, onChange, onFocus, onBlur, disabled,
      tabIndex, dispatchSearchOkr, dispatchSearchParentOkr, isSearching } = this.props;
    const currentName = (value || {}).name; // || (find(defaultOKRs, value) || {}).name
    const { currentInput = currentName || '' } = this.state || {};
    const dispatcher = owner ? partial(dispatchSearchParentOkr, owner) : dispatchSearchOkr;
    return (
      <SearchDropdown
        items={(keyword !== currentInput ? defaultOKRs : okrsFound) || []}
        labelPropName="name"
        renderItem={okr =>
          <EntitySubject entity={okr.owner} subject={okr.name} local plain avatarSize={20} />}
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={q => !isEmpty(q) && dispatcher(timeframeId, q)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && { value: { name: currentName, ...value } })}
        {...{ onFocus, onBlur, disabled }}
        tabIndex={`${tabIndex}`}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state, { timeframeId, exclude = [], loadBasicsOKRs }) => {
  const basicsOKRs = loadBasicsOKRs && EntityTypeName[loadBasicsOKRs];
  const defaultOKRs = !loadBasicsOKRs ? [] :
    flatten((state.basics[basicsOKRs].okrs || [])
      .map(okr => mapOKR({ ...okr, ownerType: EntityType.USER }))
      .map(okr => [okr, ...okr.keyResults]));
  const { isSearching, keyword, data } = state.okrsFound || {};
  const stateProps = {
    ...{ isSearching, keyword, defaultOKRs },
    okrsFound: !data ? defaultOKRs : data.map(okr => mapOKR(okr))
      .filter(({ id }) => !includes(exclude, id)),
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
