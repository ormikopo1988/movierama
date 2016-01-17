import React from 'react';
import ReactDom from 'react-dom';
import ReactBootstrap from 'react-bootstrap';

export default React.createClass({
  
  render: function() { 
  	var isType = this.props.isType;

  	switch(isType) {
  		case 'MOV': 
  			return (
  				<span><i className="fa fa-film smallFa"></i> <i>Movie</i></span>
  			);
  			break;
    	default:
    		return (
    			<p> unknown 'isType' [{isType}] </p>
    		);
	 }
  }
});
