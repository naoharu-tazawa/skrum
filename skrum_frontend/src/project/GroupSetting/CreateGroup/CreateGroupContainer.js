import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import CreateGroup from './CreateGroup';
import { createGroup } from '../action';

class CreateGroupContainer extends Component {

  static propTypes = {
    keyword: PropTypes.string,
    pageNo: PropTypes.number,
    dispatchFetchGroups: PropTypes.func.isRequired,
    isPostingGroup: PropTypes.bool.isRequired,
    roleLevel: PropTypes.number.isRequired,
    dispatchCreateGroup: PropTypes.func.isRequired,
  };

  render() {
    const { isPostingGroup, roleLevel, dispatchCreateGroup } = this.props;
    return <CreateGroup {...{ isPostingGroup, roleLevel, dispatchCreateGroup }} />;
  }
}

const mapStateToProps = (state) => {
  const { isPostingGroup } = state.groupSetting || {};
  const { roleLevel } = state.auth || {};
  return { isPostingGroup, roleLevel };
};

const mapDispatchToProps = (dispatch, { keyword = '', pageNo = 1, dispatchFetchGroups }) => {
  const dispatchCreateGroup = group =>
    dispatch(createGroup(group)).then(({ error }) =>
      (error ? { error } : dispatchFetchGroups(keyword, pageNo)));
  return {
    dispatchCreateGroup,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(CreateGroupContainer);
