import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty, partial } from 'lodash';
import SearchDropdown from '../../components/SearchDropdown';
import { explodePath } from '../../util/RouteUtil';
import { searchPath, searchAdditionalPath } from './action';

const pathPropType = PropTypes.shape({
  groupPathId: PropTypes.number.isRequired,
  groupPathName: PropTypes.string.isRequired,
});

class PathSearch extends PureComponent {

  static propTypes = {
    groupId: PropTypes.number,
    paths: PropTypes.arrayOf(pathPropType),
    value: PropTypes.oneOfType([pathPropType, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    disabled: PropTypes.bool,
    dispatchSearchPath: PropTypes.func,
    dispatchSearchAdditionalPath: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { groupId, paths = [], value, onChange, onFocus, onBlur, disabled,
      dispatchSearchPath, dispatchSearchAdditionalPath, isSearching } = this.props;
    const { currentInput = (value || {}).name } = this.state || {};
    const dispatcher = groupId ? partial(dispatchSearchAdditionalPath, groupId) :
      dispatchSearchPath;
    return (
      <SearchDropdown
        items={isEmpty(currentInput) ? [] : paths}
        labelPropName="groupPathName"
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={keyword => !isEmpty(keyword) && dispatcher(keyword)}
        onSelect={onChange}
        {...(!isEmpty(currentInput) && value)}
        {...{ onFocus, onBlur, disabled }}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state, { groupId }) => {
  const { isSearching, data = [] } = state.pathsFound || {};
  const stateProps = { paths: data, isSearching };
  if (groupId) return stateProps;
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { ...stateProps, groupId: explodePath(pathname).id };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSearchPath = (groupId, keyword) =>
    dispatch(searchPath(groupId, keyword));
  const dispatchSearchAdditionalPath = (groupId, keyword) =>
    dispatch(searchAdditionalPath(groupId, keyword));
  return { dispatchSearchPath, dispatchSearchAdditionalPath };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PathSearch);
