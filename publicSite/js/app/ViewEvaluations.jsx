import React from 'react';
import ReactDom from 'react-dom';

var moment = require('moment');
var RB = require('react-bootstrap');

import IsTypeComp from './IsTypeComp';
import EvaluationFormComp from './EvaluationFormComp';

export default React.createClass({ //NotificationsList
  
  /*
	properties
	data: [] of evaluations
    whatId: id of the object to see its evaluations
    whatType: type of the object
  */

  getInitialState: function() {
  	return {};
  },

  render: function() {
  	console.log('View evaluations - Render: ', this.props);
  	
  	if(this.props.data.length !== 0) {
  		var results = this.props.data;
  	}
  	
  	return (
      <div className="container-fluid">
      	<center><h1>Evaluations</h1></center>
		{
        	this.props.data.length !== 0 ? 
	        	results.map(function(result) {
	          		return (
	          			result.isType === 'MOV' ?
		          			<div className="row">
		          				<Evaluation isType="MOV" key={result.id} result={result}></Evaluation>
		          			</div> : null
	          		); 
	          	}) : null
        }
      </div>
    );
  } 
});

var Evaluation = React.createClass({

	getInitialState() {
		return { showModal: false };
	},

	close() {
		this.setState({ showModal: false });
	},

	open() {
		this.setState({ showModal: true });
	},

	onSuccessfullEvalSubmit() {
		this.setState({ showModal: false });
	},

	render: function() {
		var result = this.props.result;
		var isType = this.props.isType;

		//CREATE TIME
		var startDateTime = 'Not started';
		var endDateTime = 'Not started';
		if(result.startDateTime !== '') {
			startDateTime = moment.utc(result.startDateTime, 'YYYYMMDDhhmmss').fromNow();
		}
		if(result.endDateTime !== '') {
			endDateTime = moment.utc(result.endDateTime, 'YYYYMMDDhhmmss').fromNow();
		}
		
		return (
			<div className="col-md-12 col-sm-12">
				<div className="panel panel-default well-agency">
					<IsTypeComp isType={isType}></IsTypeComp> 
					{
						result.isSelfOwner ?
							<span>
								<span> - </span>
								<a href={MOVIERAMA.linkPrefix('evaluation/edit/'+isType+'/'+result.id)}><i className="fa fa-pencil smallFa"></i> Edit Evaluation</a>
							</span> : null
					}
					<br/>
					<i className="fa fa-play smallFa"></i> Begin - <i>{startDateTime}</i><br/>
					<i className="fa fa-stop smallFa"></i> End - <i>{endDateTime}</i><br/>
					{
						result.isRunning === '1' ?
							<span>
								Status: <i className="fa fa-toggle-on smallFa"></i> - Running<br/>
							</span> : 
							<span>
								Status: <i className="fa fa-toggle-off smallFa"></i> - Closed<br/><br/>
								<a href={MOVIERAMA.linkPrefix('evaluation/results/'+result.id)}>Click here to see Evaluation Results</a>
							</span>
					}
					<br/>
					{
						isType === 'MOV' && result.isRunning === '1' ?
							<span>
								<a href="#" onClick={this.open}>Click here to evaluate Movie</a>
							</span> : null
					}
					<RB.Modal show={this.state.showModal} onHide={this.close} dialogClassName="custom-modal">
				    	<RB.Modal.Header closeButton>
			            	<RB.Modal.Title><center>Evaluation Form</center></RB.Modal.Title>
				        </RB.Modal.Header>
				    	<RB.Modal.Body>
							<EvaluationFormComp onSuccessEval={this.onSuccessfullEvalSubmit} isType={this.props.isType} data={this.props.result}></EvaluationFormComp>
				    	</RB.Modal.Body>
				    	<RB.Modal.Footer>
			            	<center><RB.Button onClick={this.close}>Close</RB.Button></center>
			            </RB.Modal.Footer>
			    	</RB.Modal>
				</div>
			</div>
		)
	}
}); //Evaluation