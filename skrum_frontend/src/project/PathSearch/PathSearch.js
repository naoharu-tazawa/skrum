import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty } from 'lodash';
import SearchDropdown from '../../components/SearchDropdown';
import { explodePath } from '../../util/RouteUtil';
import { searchPath } from './action';

const pathPropType = PropTypes.shape({
  groupPathId: PropTypes.number.isRequired,
  groupPathName: PropTypes.string.isRequired,
});

class PathSearch extends PureComponent {

  static propTypes = {
    groupId: PropTypes.number.isRequired,
    paths: PropTypes.arrayOf(pathPropType),
    value: PropTypes.oneOfType([pathPropType, PropTypes.shape({}), PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    disabled: PropTypes.bool,
    dispatchSearchPath: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { groupId, paths = [], value, onChange, onFocus, onBlur, disabled,
      dispatchSearchPath, isSearching } = this.props;
    const { currentInput = (value || {}).name } = this.state || {};
    return (
      <SearchDropdown
        items={isEmpty(currentInput) ? [] : paths}
        labelPropName="groupPathName"
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={keyword => !isEmpty(keyword) && dispatchSearchPath(groupId, keyword)}
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
  return { dispatchSearchPath };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PathSearch);
