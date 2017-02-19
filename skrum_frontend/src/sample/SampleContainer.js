import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import SampleComponent from './SampleComponent';
import { fetchSample } from './action';
import { errorType } from '../util/PropUtil';

class SampleContainer extends Component {
  static propTypes = {
    handleSubmit: PropTypes.func.isRequired,
    count: PropTypes.number.isRequired,
    error: errorType,
  };

  renderError() {
    if (this.props.error) {
      return (<pre>
        {this.props.error.message}
      </pre>);
    }
  }

  render() {
    return (
      <div>
        {this.renderError()}
        <div>
          <SampleComponent
            count={this.props.count}
            handleClick={this.props.handleSubmit}
          />
        </div>
      </div>
    );
  }
}


const mapStateToProps = (state) => {
  const { data, error } = state.sample;
  return { count: data.count, error };
};

const mapDispatchToProps = (dispatch) => {
  const handleSubmit = () => dispatch(fetchSample());
  return { handleSubmit };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(SampleContainer);
