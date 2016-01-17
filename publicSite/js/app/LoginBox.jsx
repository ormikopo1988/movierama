import React from 'react';
import ReactDom from 'react-dom';
import ReactBootstrap from 'react-bootstrap';
import MessagesBoxComp from './MessagesBoxComp';
var RB = require('react-bootstrap');


export default React.createClass({ //LoginBox
  // props: nextRoute

  render: function() {
    return (
      <div className="loginBox">
          <h1>Login</h1>
          <LoginForm {...this.props} />
      </div>
    );
  }
  
});

var LoginForm = React.createClass({
  
  getInitialState: function() {
    return { isLoading: false }
  },
  
  handleSubmit: function(e) {
      e.preventDefault();

      var email     = ReactDOM.findDOMNode(this.refs.email).value.trim();
      var password  = ReactDOM.findDOMNode(this.refs.password).value.trim();
      var okToProceed = true;

      this.refs.msgBox.clearAll();

      if ( !email ) {
        okToProceed = false;
        this.refs.msgBox.addError('Please fill-in your e-mail', false);
      }
      if ( !password ) {
        okToProceed = false;
        this.refs.msgBox.addError('Please fill-in your password', false);
      }

      if ( !okToProceed ) {
        return;
      }

      this.setState({isLoading: true});

      var data = {
        email: email,
        password: password,
      };

      MOVIERAMA.ajaxCall('api/login/do', 'POST', data, this, this.cbHandleSubmit);

      return;
  },
  
  cbHandleSubmit: function(result) {
        if(result.loginOk === false) {
            this.setState({isLoading: false});
            this.refs.msgBox.addError(result.errors, true);
        }
        else {
            this.refs.msgBox.clearAll();
            window.location = MOVIERAMA.linkPrefix(this.props.nextRoute);
        }
        
        return;
  },

  resetPassword: function() {

      var email = ReactDOM.findDOMNode(this.refs.email).value.trim();

      this.refs.msgBox.clearAll();

      if ( !email ) {
        this.refs.msgBox.addError('Please fill-in your e-mail', false);
        return;
      }

      this.setState({isLoading: true});

      var data = {
        email: email
      };

      MOVIERAMA.ajaxCall('api/password/reset', 'POST', data, this, this.cbHandleResetPassword);

      return;
  },

  cbHandleResetPassword: function(result) {
        if(result.resetOk === false) {
            this.setState({isLoading: false});
            this.refs.msgBox.addError(result.errors, true);
        }
        else {
            this.refs.msgBox.clearAll();
            this.refs.msgBox.addSuccess('A new password has been sent to your email!', false);
            setTimeout(this.refs.msgBox.clearSuccesses, 3000);
        }

        this.setState({isLoading: false});
        
        return;
  },

  resendToken: function() {

      var email = ReactDOM.findDOMNode(this.refs.email).value.trim();

      this.refs.msgBox.clearAll();

      if ( !email ) {
        this.refs.msgBox.addError('Please fill-in your e-mail', false);
        return;
      }

      this.setState({isLoading: true});

      var data = {
        email: email
      };

      MOVIERAMA.ajaxCall('api/verificationToken/resend', 'POST', data, this, this.cbHandleResendToken);

      return;
  },

  cbHandleResendToken: function(result) {
        if(result.resendOk === false) {
            this.setState({isLoading: false});
            this.refs.msgBox.addError(result.errors, true);
        }
        else {
            this.refs.msgBox.clearAll();
            this.refs.msgBox.addSuccess('Your verification token has been sent again to your email!', false);
            setTimeout(this.refs.msgBox.clearSuccesses, 3000);
        }

        this.setState({isLoading: false});
        
        return;
  },
  
  render: function() {
    var isLoading = this.state.isLoading;

    return (
      <div className="well well-large formInlineStyle">
	        	<input type="text" className="form-control" ref="email" 
              autoFocus={true} id="userEmail" placeholder="Your e-mail..." aria-describedby="basic-addon2"/>
	        <br/>
	        	<input type="password" className="form-control" ref="password" id="userPassword" placeholder="Your MovieRama Password..." aria-describedby="basic-addon3"/>
	        <br/><br/>
	        <center>
              <RB.Button 
                type="button"
                bsStyle="success"
                bsSize="large"
                disabled={isLoading}
                onClick={!isLoading ? this.handleSubmit : null}
              >
              {
              isLoading ? 
                <span><i className="fa fa-circle-o-notch fa-spin"></i> Checking...</span> : 
                <span><i className="fa fa-sign-in"></i>&nbsp;&nbsp;Login</span>
              }
              </RB.Button>
              <br/><br/>
              <small><a href="#" onClick={this.resetPassword}>Forgot Password?</a> | <a href="#" onClick={this.resendToken}>Resend Verification Token</a></small>
          </center>
          <br/>
          <br/>
        <MessagesBoxComp ref="msgBox"/>
      </div>
    );
  }
});

