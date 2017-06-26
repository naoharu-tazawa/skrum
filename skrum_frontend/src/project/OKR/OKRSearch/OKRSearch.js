import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { isEmpty } from 'lodash';
import SearchDropdown from '../../../components/SearchDropdown';
import { explodePath } from '../../../util/RouteUtil';
import { mapOKR } from '../../../util/OKRUtil';
import { searchOkr } from './action';

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
    okrs: PropTypes.arrayOf(okrPropType),
    value: PropTypes.oneOfType([okrPropType, PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    pathname: PropTypes.string,
    dispatchSearchOkr: PropTypes.func,
    isSearching: PropTypes.bool,
  };

  render() {
    const { okrs = [], value, onChange, onFocus, onBlur,
      pathname, dispatchSearchOkr, isSearching } = this.props;
    const { currentInput = (value || {}).name } = this.state || {};
    return (
      <SearchDropdown
        items={isEmpty(currentInput) ? [] : okrs}
        labelPropName="name"
        onChange={({ target }) => this.setState({ currentInput: target.value })}
        onSearch={val => !isEmpty(val) && dispatchSearchOkr(explodePath(pathname).timeframeId, val)}
        onSelect={onChange}
        {...{ value, onFocus, onBlur }}
        isSearching={isSearching}
      />
    );
  }
}

const mapStateToProps = (state) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { isSearching, data = [] } = state.okrsFound || {};
  return { okrs: data.map(okr => mapOKR(okr)), pathname, isSearching };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSearchOkr = (timeframeId, keyword) => dispatch(searchOkr(timeframeId, keyword));
  return { dispatchSearchOkr };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(OKRSearch);
