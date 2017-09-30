import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import styles from './HelpContainer.css';

class HelpContainer extends Component {

  static propTypes = {
    pathname: PropTypes.string.isRequired,
  };

  render() {
    const { pathname } = this.props;
    const { showing = 'GettingStarted' } = this.state || {};
    return (
      <div className={styles.container}>
        <section className={styles.list}>
          <div className={styles.ttl}>
            <nav>
              <ul>
                <li>
                  <Link
                    className={showing === 'GettingStarted' ? styles.active : ''}
                    onClick={() => this.setState({ showing: 'GettingStarted' })}
                    to={pathname}
                  >
                    スタートアップガイド
                  </Link>
                </li>
                <li>
                  <Link
                    className={showing === 'UserManual' ? styles.active : ''}
                    onClick={() => this.setState({ showing: 'UserManual' })}
                    to={pathname}
                  >
                    操作マニュアル
                  </Link>
                </li>
              </ul>
            </nav>
          </div>
          <div className={styles.guide} style={showing === 'GettingStarted' ? {} : { display: 'none' }}>
            <iframe src="/pdf/skrum_startup_guide.pdf" height="100%" width="100%" />
          </div>
          <div className={styles.guide} style={showing === 'UserManual' ? {} : { display: 'none' }}>
            <iframe src="/pdf/skrum_operating_manual.pdf" height="100%" width="100%" />
          </div>
        </section>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { pathname };
};

export default connect(
  mapStateToProps,
  null,
)(HelpContainer);
