import React from 'react';
import ReactDom from 'react-dom';

var moment = require('moment');
var RB = require('react-bootstrap');

export default React.createClass({ //ViewEvaluationResults
  
  /*
	properties
	data: [] results of evaluation
  */

  getInitialState: function() {
  	return {};
  },

  render: function() {
  	console.log('View evaluation results - Render: ', this.props);
  	
  	var data = this.props.data;
  	      		
  	return (
      <div className="container">
      	<center><h1>Evaluation Results</h1></center>
      	<div className="row">
	      	<div className="panel panel-default well-agency">
	      		<h3>Final Results</h3>
	      		{
	      			data.isRunning === '1' ? 
	      				<span>Evaluation still in progress...</span> : 
	      			data.isRunning == '0' && data.finalCount != '0' && data.finalCount != '0' ?
	      				<span>
		      				<strong><i className="fa fa-star smallFa"></i> Final Score - {data.finalScore} / 100</strong><br/>
		      				<strong><i className="fa fa-globe smallFa"></i> {data.finalCount} User(s) Voted in Total</strong>
	      				</span> :
	      				<span>
	      					<strong><i className="fa fa-star smallFa"></i> Final Score - {data.finalScore} / 100</strong><br/>
		      				<strong><i className="fa fa-globe smallFa"></i> {data.finalCount} User(s) Voted in Total</strong><br/><br/>
	      					<small><i className="fa fa-exclamation-triangle smallFa"></i> {data.tip}</small>
		      			</span>
		      		}
	      	</div>
      	</div>
      	<div className="row">
	      	{
	      		data.criteriaResults.map(function(criteria, index) {
	      			return (
	      				<div className="panel panel-default well-agency">
	      					<h4><i className="fa fa-list smallFa"></i> Criteria {index+1}</h4>
				      		<strong>{criteria.label} - [{criteria.description}] ({criteria.weight * 10}%)</strong><br/>
				      		<strong>Score: {criteria.theValue * 10} / 100</strong><br/>
				      		<strong>Total Users Voted : {criteria.theCount}</strong><br/>
				      	</div>
	      			);
	      		})
	      	}
      	</div>
      </div>
    );
  } 
});