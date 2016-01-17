import React from 'react';
import ReactDom from 'react-dom';
import $ from 'jquery';
var RB = require('react-bootstrap');

import MessagesBoxComp from './MessagesBoxComp';

import LoadingComp from './LoadingComp';

export default React.createClass({ //ProfileEdit
  
  getInitialState: function() {
  	return {
    	data: {
    	   main : this.props.data,
		   loading: false
		}
    };
  },
  
  render: function() { //function that returns a tree of React components that will eventually render to HTML
    return (
      <div className="profileBox">
          <Main loading={this.state.data.loading} data={this.state.data.main} />
          <Passwords loading={this.state.data.loading} data={this.state.data.main} />
      </div>
    );
  }
  
});	// ProfileBox

/*************************************************************************************************************/

var Main = React.createClass({
  getInitialState: function() {
  	console.log('MAIN VIEW: ', this.props.data);
    var tmpMainView = this.props.data; //for reseting purposes
    this.initialState = JSON.stringify(tmpMainView);
  
    return {
	  mainView: tmpMainView,
	  loading: this.props.loading
    };
  },
  
  handleChange: function(event) {
	  var tmp = this.state.mainView;
	  tmp[event.target.name] = event.target.value;

	  this.setState({
		  mainView: tmp
	  });
  },
  
  handleReset: function() {
  	  console.log('INITIAL STATE: ', JSON.parse(this.initialState));	
  	  this.refs.msgBox.clearErrors();
	  var resetView = JSON.parse(this.initialState);
	  this.setState({mainView: resetView});
  },
  
  handleSave: function(e) {
	  e.preventDefault();
	  var nameErrors = [];
	  if(this.refs.firstName.getValue().length < 3) {
	  	nameErrors.push('First Name must be more than 2 characters.');
	  	this.refs.msgBox.addError(nameErrors);
	  	return;
	  }

	  if(this.refs.lastName.getValue().length < 3) {
	  	nameErrors.push('Last Name must be more than 2 characters.')
	  	this.refs.msgBox.addError(nameErrors);
	  	return;
	  }
	 
	  var data = this.state.mainView;
	  
	  var url = MOVIERAMA.linkPrefix('api/profile/mainInfoSave');
	  //data to send to server!
	  this.setState({
	  	loading: true
	  });
	  MOVIERAMA.ajaxCall(url, 'POST', data, this, this.cbHandleSave);
  },

  cbHandleSave: function(result) {
  	if(result.saveOk === true) {
  		// if successully saved
  		var data = this.state.mainView;
  		this.initialState = JSON.stringify(this.state.mainView);
	    this.setState({mainView: data});
  		this.refs.msgBox.clearErrors();
  		this.refs.msgBox.addSuccess('Your information was saved successully!', false);
	  	setTimeout(this.refs.msgBox.clearSuccesses, 3000);
  	}
  	else {
  		this.refs.msgBox.addError(result.errors);
  	}
  	this.setState({
		loading: false
	});
  },

  render: function() {
	var mainInfo = this.state.mainView;
	console.log('RENDER PROFILE: ', mainInfo);
	return (
	  <RB.Well id="mainComp">
      	<RB.Row>
      		<RB.Col md={6}>
				<RB.Col md={10}>
					<RB.Input type="text" addonBefore="First Name (*)" placeholder="Your First Name..." value={mainInfo.firstName} ref="firstName" name="firstName" onChange={this.handleChange} />
				</RB.Col>
			</RB.Col>
			<RB.Col md={6}>
				<RB.Col md={10}>
					<RB.Input type="text" addonBefore="Last Name (*)" placeholder="Your Last Name..." value={mainInfo.lastName} ref="lastName" name="lastName" onChange={this.handleChange} />
				</RB.Col>
			</RB.Col>
		</RB.Row><br/>
		<RB.Row>
			{
				this.state.loading === false ?
					<span>
						<RB.Col md={1} mdOffset={5}>
							<RB.Button bsStyle="success" onClick={this.handleSave}><i className="glyphicon glyphicon-ok-sign"></i> Save</RB.Button>
						</RB.Col>
						<RB.Col md={1}>
							<RB.Button bsStyle="default" onClick={this.handleReset}><i className="glyphicon glyphicon-repeat"></i> Reset</RB.Button>
						</RB.Col>
					</span> :
					<LoadingComp loadingText="Saving Information..."></LoadingComp>
			}
		</RB.Row><br/>
		<RB.Row>
			<RB.Col md={12}>
				<MessagesBoxComp ref="msgBox"/>
			</RB.Col>
		</RB.Row>
      </RB.Well>
    );
  }
});	// Main

/*************************************************************************************************************/

var Passwords = React.createClass({

  getInitialState: function() {
	console.log('PASSWORDS VIEW: ', this.props.data);
	
	return {
		loading: this.props.loading
	};
  },

  handleSave: function() {
    var oldPass = this.refs.oldPass.getValue();
    var newPass = this.refs.newPass.getValue();
    var newPassConfirm = this.refs.newPassConfirm.getValue();
    
    if (!oldPass || !newPass || !newPassConfirm) {
    	alert('Please fill all the required input fields for changing password!');
        return;
    }
    
	//send to server
	var url = MOVIERAMA.linkPrefix('api/profile/changePassword');
	var data = {
		movieRamaUserId: this.props.data.movieRamaUserId,
		oldPass: oldPass,
		newPass: newPass,
		newPassConfirm: newPassConfirm
	}

    this.setState({
  		loading: true
    });
	
	MOVIERAMA.ajaxCall(url, 'POST', data, this, this.cbHandleSave);
	
    return;
  },

  cbHandleSave: function(result) {
  	console.log('Change pass result: ', result);
  	if(result.changeOk === true) {
  		this.refs.msgBox.clearErrors();
  		this.refs.msgBox.addSuccess('Your password was changed successully!', false);
	  	setTimeout(this.refs.msgBox.clearSuccesses, 3000);
  	}
  	else {
		this.refs.msgBox.addError(result.errors);
  	}
  	this.setState({
		loading: false
	});
  },
  
  render: function() {
    return (
	  <RB.Well id="mainComp">
  		<center>
			Change Password
		</center><br/>
		<RB.Row>
			<RB.Col md={12}>
				<center>
					<RB.Input type="password" addonBefore="Old Password (*)" placeholder="Old Password..." ref="oldPass" name="oldPass" onChange={this.handleChange} />
				</center>
				<RB.Input type="password" addonBefore="New Password (*)" placeholder="New Password..." ref="newPass" name="newPass" onChange={this.handleChange} />
				<RB.Input type="password" addonBefore="New Password Confirm (*)" placeholder="New Password Confirm..." ref="newPassConfirm" name="newPassConfirm" onChange={this.handleChange} />	
				<small><i className="fa fa-info smallFa"></i> Password must be at least 8 chars and include at least one Capital letter and one digit</small>
			</RB.Col>
		</RB.Row><br/>
		<RB.Row>
			{
				this.state.loading === false ?
					<center>
						<RB.Button bsStyle="success" onClick={this.handleSave}><i className="glyphicon glyphicon-ok-sign"></i> Save</RB.Button>
					</center> :
					<LoadingComp loadingText="Checking and saving..."></LoadingComp>
			}
		</RB.Row><br/>
		<RB.Row>
			<RB.Col md={12}>
				<MessagesBoxComp ref="msgBox"/>
			</RB.Col>
		</RB.Row>
      </RB.Well>
    );
  } 
});	// Passwords