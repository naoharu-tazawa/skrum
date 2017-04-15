import React, { Component, PropTypes } from 'react';

export default class TeamRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      projectName: PropTypes.string.isRequired,
      subMenu: PropTypes.string.isRequired,
    }),
  };

  render() {
    return (<div>
        プロジェクトトップ
      </div>);
  }
}
