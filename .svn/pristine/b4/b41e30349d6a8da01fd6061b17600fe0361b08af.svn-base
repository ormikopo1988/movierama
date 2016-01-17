import React from 'react';
import ReactDom from 'react-dom';

// MessagesBoxComp

export default React.createClass({
	getInitialState: function() {
		var
			errors 		= 	this.props.errors 		|| [], 
			warnings 	= 	this.props.warnings 	|| [], 
			successes 	= 	this.props.successes 	|| [],
			infos   	=	this.props.infos 		|| [] 
		;

		if ( this.props.allMessages !== undefined ) {
			var howMany = this.props.allMessages.length;
			for (var i = 0; i < howMany; i++) {
				var aMsg = this.props.allMessages[i];
				switch( aMsg.messageType ) {
					case 'S': successes.push( aMsg.messageText ); break;
					case 'I': infos.push( aMsg.messageText ); break;
					case 'W': warnings.push( aMsg.messageText ); break;
					case 'E': errors.push( aMsg.messageText ); break;
					default: infos.push( aMsg.messageText ); break;
				}
			}
		}

		this.ourState = {
			errors: 	errors, 
			warnings: 	warnings,
			successes: 	successes,
			infos: 		infos
		};
	
		return this.ourState;
	},
	
	// public
	clearAll: function() {
		//console.log( 'MessagesBoxComp: Clearing All Messages' );
		this.ourState = {
			errors: 	[], 
			warnings: 	[],
			successes: 	[],
			infos: 		[],
		};

		this.setState( this.ourState );
		//console.log( 'MessagesBoxComp: Cleared All Messages' );
	}
	,
	
	clearSome: function(type) {
		this.ourState[type] = [];
		this.setState( this.ourState );	// equivalent to this.setState( { errors: [] } );
	}
	,
	
	add: function(msg, type, clear) {
		clear = ( typeof clear !== 'undefined' ? clear : true );	// def value

		//console.log('add msg:', msg, type, clear, this.state[type], this.ourState[type] );

		var newMsgArray = ( clear ? [] : this.ourState[type] );
		
		if ( Array.isArray(msg) ) {
			newMsgArray = newMsgArray.concat(msg);
    	}
    	else {
    		newMsgArray.push(msg);
    	}

    	this.ourState[type] = newMsgArray;

		this.setState( this.ourState );
	},

	// public
	addError: function(msg, clear) {
		this.add(msg, 'errors', clear );
	},

	// public
	addWarning: function(msg, clear) {
		this.add(msg, 'warnings', clear );
	},

	// public
	addInfo: function(msg, clear) {
		this.add(msg, 'infos', clear );
	}
	,

	// public
	addSuccess: function(msg, clear) {
		this.add(msg, 'successes', clear );
	}
	,

	// public
	clearErrors: function() {
		this.clearSome('errors');
	}
	,
	// public
	clearWarnings: function() {
		this.clearSome('warnings');
	}
	,
	// public
	clearSuccesses: function() {
		this.clearSome('successes');
	}
	,
	// public
	clearInfos: function() {
		this.clearSome('infos');
	}
	,

	renderMsg: function(msg, index) {
		return (
			<MessageItem message={msg} key={index}> </MessageItem>
		);
	}
	,

	render: function() {
		var errorsHidden	= ( this.state.errors.length == 0 ? 'hidden' : '' ); 
		var warningsHidden	= ( this.state.warnings.length == 0 ? 'hidden' : '' ); 
		var successesHidden	= ( this.state.successes.length == 0 ? 'hidden' : '' ); 
		var infosHidden		= ( this.state.infos.length == 0 ? 'hidden' : '' ); 

		//console.log( this.state );

		return (
			<div id="messagesBox">
				<div id="errorMessages" className={'alert alert-danger ' + errorsHidden} role="alert" >
					{this.state.errors.map(this.renderMsg)}
				</div>
				<div id="warningMessages" className={'alert alert-warning ' + warningsHidden} role="alert" >
					{this.state.warnings.map(this.renderMsg)}
				</div>
				<div id="successMessages" className={'alert alert-success ' + successesHidden} role="alert" >
					{this.state.successes.map(this.renderMsg)}
				</div>
				<div id="infoMessages" className={'alert alert-info ' + infosHidden} role="alert" >
					{this.state.infos.map(this.renderMsg)}
				</div>
			</div>
		);
	}
});

var MessageItem = React.createClass({
	rawMarkup: function() {
		return { __html: this.props.message}
	}
	,
	render: function() {
		return (
			<div dangerouslySetInnerHTML={this.rawMarkup()} />
		);
	}
});

