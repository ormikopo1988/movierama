import React from 'react';
import ReactDom from 'react-dom';
var RB = require('react-bootstrap');

// LoadingComp

// Properties:
// loadingText: text to show next to loading gif

export default React.createClass({

	getInitialState: function() {
		return {
			loadingText: this.props.loadingText
		}
	},

	render: function() {
		return (
			<RB.Col md={4} mdOffset={5}>
				<i className="fa fa-spinner fa-pulse"></i> {this.state.loadingText}
			</RB.Col>
		);
	}

});