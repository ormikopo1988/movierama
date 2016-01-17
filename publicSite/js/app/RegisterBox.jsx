import React from 'react';
import ReactDom from 'react-dom';
import $ from 'jquery';

var RB = require('react-bootstrap');

import MessagesBoxComp from './MessagesBoxComp';
import MOVIERAMA from './movierama';

export default React.createClass({ //RegisterBox
  
  getInitialState: function() {
    return {data: []};
  },
  
  render: function() {
    return (
		<div className="registerBox">
		  <h1>Register</h1>
		  <RegisterForm {...this.props} />
		</div>
    );
  }
  
});	// RegisterBox

/*----------------------------------------------------------------------------*/

var RegisterForm = React.createClass({
    hm: {
    	  email: '',
	      firstName: '',
	      lastName: '',
	      password: 'Password must be at least 8 chars and include at least one Capital letter and one digit',
	      passwordConfirm: ''
	    }
	,    

	getInitialState: function() {
		return {
			isLoading: false,
			emailData: { bsStyle: null, helpMessage: this.hm.email },
			firstNameData: { bsStyle: null, helpMessage: this.hm.firstName },
			lastNameData: { bsStyle: null, helpMessage: this.hm.lastName },
			passwordData: { bsStyle: null, helpMessage:  this.hm.password },
			passwordConfirmData: { bsStyle: null, helpMessage: this.hm.passwordConfirm },
		}
	}
	,

	addErrors: function(msg, clear) {
		// msg can be a single String or an array of Strings
		clear = ( typeof clear !== 'undefined' ? clear : false );	// def value

		//console.log('addErrors: ', msg, clear);
		this.refs.msgBox.addError(msg, clear);
	}
	,

	clearErrors: function(msg) {
		this.refs.msgBox.clearAll();
	}
	,

	handleSubmit: function(e) {
	    var self = this;
	    e.preventDefault();

	    self.clearErrors();
	    
	    var okToProceed = true;

	    var agreeWithTerms = ReactDOM.findDOMNode(this.refs.agreeCbx).checked;

	    var data = {
	      email: this.refs.userEmail.getValue().trim(),
	      firstName: this.refs.firstName.getValue().trim(),
	      lastName: this.refs.lastName.getValue().trim(),
	      password: this.refs.password.getValue().trim(),
	      passwordConfirm: this.refs.passwordConfirm.getValue().trim(),
	    };

		console.log(data);
		
		this.clearErrors();

		var newState = {}
		newState.firstNameData	= (data.firstName == '' ? { bsStyle: 'error', helpMessage: 'Please fill-in' } : { bsStyle: null, helpMessage: this.hm.firstName });
		newState.lastNameData	= (data.lastName == '' ? { bsStyle: 'error', helpMessage: 'Please fill-in' } : { bsStyle: null, helpMessage: this.hm.lastName });
		newState.passwordData 	= (data.password == '' ? { bsStyle: 'error', helpMessage: 'Please fill-in' } : { bsStyle: null, helpMessage: this.hm.password });
		newState.passwordConfirmData = (data.passwordConfirm == '' ? { bsStyle: 'error', helpMessage: 'Please fill-in' } : { bsStyle: null, helpMessage: this.hm.passwordConfirm });

		okToProceed = (
			data.firstName 		!= '' &&
			data.lastName 		!= '' &&
			data.password 		!= '' &&
			data.passwordConfirm 		!= ''
		);

		this.setState( newState );

		if ( !okToProceed) {
			this.addErrors( 'Please correct the errors above and retry.' );
		}

	    if ( !agreeWithTerms ) {
	    	okToProceed = false;
	   		this.addErrors( 'Please accept the Terms and Privacy Policy before proceeding.' );
	   		//return;
	    }
	    
	    if ( okToProceed ) {
	    	//console.log('DISABLED AJAX CALL');
		    this.setState({isLoading: true});
		    MOVIERAMA.ajaxCall('api/registration/do', 'POST', data, self, this.cbHandleSubmit);
		}

	    return;
	}	// handleSubmit
	,

	cbHandleSubmit: function(result) {
    	var self = this;

	    this.setState({isLoading: false});

    	console.log('cbHandleSubmit');
    	console.log(result);

    	if(result.registerOK === false) {
    		self.clearErrors();
    		self.addErrors(result.errors, true);
    	}
    	else {
    		self.clearErrors();
    		window.location = MOVIERAMA.linkPrefix('login');
    	}
    	
    	return;
    }		
	,

	componentDidMount: function() {
  		var self = this;
	    ReactDom.findDOMNode(this.refs.userEmail).focus(); 
  	}
  	,
  
    render: function() {
	  	var isLoading = this.state.isLoading;

	  	var personalDataRender  = (
	       <RB.Well>
			<center><h4>Tell us a bit about yourself</h4></center>
			<RB.Row>
				<RB.Col md={6}>
				    <RB.Input type="text" 
				    	autoFocus
				        placeholder="Your First Name..." ref="firstName" name="firstName"  
						help={this.state.firstNameData.helpMessage}
						bsStyle={this.state.firstNameData.bsStyle}
				    />
				</RB.Col>
				<RB.Col md={6}>
				    <RB.Input type="text" 
				    	placeholder="Your Last Name..." ref="lastName" name="lastName"  
						help={this.state.lastNameData.helpMessage}
						bsStyle={this.state.lastNameData.bsStyle}
					/>
				</RB.Col>
			</RB.Row>
	        </RB.Well>
	  	);

		var passwordsRender = (
	       <RB.Well>
	         <center><h4>Your desired password</h4></center>
	         <RB.Row>
	            <RB.Col md={6}>
		        	<RB.Input  type="password" 
		        		className="form-control" ref="password" placeholder="Password..." aria-describedby="basic-addon3"
						help={this.state.passwordData.helpMessage}
						bsStyle={this.state.passwordData.bsStyle}
	        		></RB.Input>
	            </RB.Col>
	            <RB.Col md={6}>
		        	<RB.Input  type="password" 
		        		className="form-control" ref="passwordConfirm" placeholder="Retype Password..." aria-describedby="basic-addon4"
						help={this.state.passwordConfirmData.helpMessage}
						bsStyle={this.state.passwordConfirmData.bsStyle}
		        	></RB.Input>
	            </RB.Col>
	         </RB.Row>
	      </RB.Well>
		);

		var registerButtonDiv = ( 
	        <div>
		        <RB.Row>
		            <center>
			            <label className="checkbox-inline">
			            	<input type="checkbox" name="agreeCbx" ref="agreeCbx" defaultChecked={false} />
	   						I agree with the Terms&nbsp;
	   		 		    	and Privacy Policy of MovieRama
		            	</label>
		            </center>
		        </RB.Row>
	            <br />
		        <RB.Row>
		            <center>
		                <RB.Button id="registerButton" 
		                	type="button"
		                	bsStyle="success"
		                	bsSize="large"
		                	disabled={isLoading}
	    					onClick={!isLoading ? this.handleSubmit : null}
		                >
		                	
		                {isLoading ? 
		                	<span><i className="fa fa-circle-o-notch fa-spin"></i> Sending the information...</span> : 
		                	<span><i className="glyphicon glyphicon-ok-sign"></i> Register</span>
		                }
		                </RB.Button>
		            </center>
		        </RB.Row><br/>
			</div>
		);

		return (
			<div className="well well-large formInlineStyle">
				<RB.Well>
					<RB.Row>
						<RB.Col md={12}>
							<RB.Input
								type="email"
								value={this.state.value}
								autoFocus={true}
								placeholder="Enter a valid e-mail..."
								label="e-mail"
								help={this.state.emailData.helpMessage}
								bsStyle={this.state.emailData.bsStyle}
								hasFeedback
								ref="userEmail"
								groupClassName="group-class"
								labelClassName="label-class"
								onKeyPress={this.handleKeyPress}
								onChange={this.handleOnChange}
								onBlur={this.handleCheckEmail}
							/>
						</RB.Col>
					</RB.Row>
				</RB.Well>

					<br/>  
					
				{personalDataRender}

				{passwordsRender}

				<br/>
				<br/>

				{registerButtonDiv}

				<MessagesBoxComp ref="msgBox"/>
			</div>
	    );
 	}
  
});	// RegisterForm


/*----------------------------------------------------------------------------*/

