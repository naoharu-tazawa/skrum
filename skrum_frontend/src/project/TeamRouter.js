import React, { Component } from 'react';
import PropTypes from 'prop-types';

export default class TeamRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      teamId: PropTypes.string,
      action: PropTypes.string,
    }),
  };

  render() {
    return (<div>
        プロジェクトトップ
      </div>);
  }
}
