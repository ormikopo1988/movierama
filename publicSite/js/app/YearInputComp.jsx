import React from 'react';
import ReactDom from 'react-dom';
var RB = require('react-bootstrap');

export default React.createClass({ //YearInputComp

  getInitialState: function() {
  	return {};
  },

  getValue: function() {
  	return this.refs.selectYear.getValue();
  },

  render: function() {
  	var options = [];
  	

  	for(var i=this.props.min; i<=this.props.max; i++) {
  		options.push({
  			label: i,
  			value: i 
  		});
  	}

	return (
		<RB.Input type="select" ref="selectYear" {...this.props}>
	    	<option value=""> - {this.props.placeholder} - </option>
	    	{MOVIERAMA.renderOptions(options)}
	    </RB.Input>
    );
  }
});