import React from 'react';
import ReactDom from 'react-dom';
import $ from 'jquery';
import MessagesBoxComp from './MessagesBoxComp';
import LoadingComp from './LoadingComp';
var RB = require('react-bootstrap');

export default React.createClass({ //EvaluationFormComp -> modal
	getInitialState: function() {
		return {
			loading: true
		};
	},

	componentWillMount: function() {
		var data = {
			evaluationId: this.props.data.id || this.props.data
		};
		MOVIERAMA.ajaxCall(MOVIERAMA.linkPrefix('api/evaluation/getEvalCriteria'), 'POST', data, this, this.handleGetCriteria);
	},

	handleGetCriteria: function(result) {
		console.log('EvaluationFormComp.handleGetCriteria: ', result);
		if(result.getCriteriaOk) {
			this.setState({
				data: result.getCriteria,
				loading: false
			});
		}
	},

	handleSubmitEval: function() {
		var LIKERT5AD = '';
		var LIKERT10AD = '';
		var LIKERT5LH = '';
		var LIKERT10LH = '';
		var COMMENTSPM = '';
		var COMMENTS5AD = '';
		var COMMENTS10AD = '';
		var COMMENTS5LH = '';
		var COMMENTS10LH = '';

		if(this.refs.LIKERT5AD) {
			LIKERT5AD = this.refs.LIKERT5AD.getValue() || '';	
		}
		if(this.refs.LIKERT10AD) {
			LIKERT10AD = this.refs.LIKERT10AD.getValue() || '';
		}
		if(this.refs.LIKERT5LH) {
			LIKERT5LH = this.refs.LIKERT5LH.getValue() || '';
		}
		if(this.refs.LIKERT10LH) {
			LIKERT10LH = this.refs.LIKERT10LH.getValue() || ''; 
		}
		if(this.refs.COMMENTSPM) {
			COMMENTSPM = this.refs.COMMENTSPM.getValue() || '';
		}
		if(this.refs.COMMENTS5AD) {
			COMMENTS5AD = this.refs.COMMENTS5AD.getValue() || '';	
		}
		if(this.refs.COMMENTS10AD) {
			COMMENTS10AD = this.refs.COMMENTS10AD.getValue() || '';
		}
		if(this.refs.COMMENTS5LH) {
			COMMENTS5LH = this.refs.COMMENTS5LH.getValue() || '';
		}
		if(this.refs.COMMENTS10LH) {
			COMMENTS10LH = this.refs.COMMENTS10LH.getValue() || ''; 
		}


		//First scan for missing answers on non optional criteria
		var errors = [];
		for(var i=0; i<this.state.data.length; i++) {
			if(this.state.data[i].isOptional === '0') {
				if(this.state.data[i].evaluationTypeDVCode === 'LIKERT5AD') {
					if(!LIKERT5AD) {
						errors.push('Please fill in Disagree / Disagree (1-5)');	
					}
				}
				if(this.state.data[i].evaluationTypeDVCode === 'LIKERT10AD') {
					if(!LIKERT10AD) {
						errors.push('Please fill in Disagree / Agree (1-10)');
					}
				}
				if(this.state.data[i].evaluationTypeDVCode === 'LIKERT5LH') {
					if(!LIKERT5LH) {
						errors.push('Please fill in Hate / Love (1-5)');
					}
				}
				if(this.state.data[i].evaluationTypeDVCode === 'LIKERT10LH') {
					if(!LIKERT10LH) {
						errors.push('Please fill in Hate / Love (1-10)');
					}
				}	
			}
		}

		console.log('Errors: ', errors);
		if(errors.length !== 0) {
			this.refs.msgBox.addError(errors);
			return;
		}
		else {
			this.refs.msgBox.clearErrors();
		}

		var answers = [];
		for(var i=0; i<this.state.data.length; i++) {
			var answer;
			switch(this.state.data[i].evaluationTypeDVCode) {
				case 'LIKERT5AD':
					answer = {
						evaluationId: this.state.data[i].evaluationId,
						evaluationCriteriaId: this.state.data[i].id,
						theValue: LIKERT5AD,
						comments: COMMENTS5AD
					};
					answers.push(answer);
					break;
				case 'LIKERT10AD':
					answer = {
						evaluationId: this.state.data[i].evaluationId,
						evaluationCriteriaId: this.state.data[i].id,
						theValue: LIKERT10AD,
						comments: COMMENTS10AD
					};
					answers.push(answer);
					break;
				case 'LIKERT5LH':
					answer = {
						evaluationId: this.state.data[i].evaluationId,
						evaluationCriteriaId: this.state.data[i].id,
						theValue: LIKERT5LH,
						comments: COMMENTS5LH
					};
					answers.push(answer);
					break;
				case 'LIKERT10LH':
					answer = {
						evaluationId: this.state.data[i].evaluationId,
						evaluationCriteriaId: this.state.data[i].id,
						theValue: LIKERT10LH,
						comments: COMMENTS10LH
					};
					answers.push(answer);
					break;
			}
		}

		this.setState({
	      loading: true
	    });

	    var dataToSend = {
	    	data: JSON.stringify(answers)
	    };

		MOVIERAMA.ajaxCall(MOVIERAMA.linkPrefix('api/evaluation/submitEvaluation'), 'POST', dataToSend, this, this.cbSubmitEval);
	},

	cbSubmitEval: function(result) {
		if(result.evaluationSavedOk === true) {
	    	this.refs.msgBox.clearErrors();
			this.refs.msgBox.addSuccess('Your movie review was saved successully!', false);
			
			//send the user somewhere else?????
			//close the modal
			this.props.onSuccessEval();
		}
		else {
			this.refs.msgBox.addError(result.errors);
		}
		this.setState({
			loading: false
	    });
	},
  
	render: function() {
		var data = this.state.data || null;
		console.log('EVALUATION FORM RENDER: ', data);
		var self = this;
		return (
			<div className="container">
				<div clasName="well">
					{
						this.state.loading === false ? 
							<div className="row">
								{
						        	data !== null ? 
							        	data.map(function(criteria) {
							          		return (
							          			<span>
							          				{
									          			criteria.evaluationTypeDVCode === 'LIKERT5AD' ?
										          			<div className="col-md-12">
																<div className="panel panel-default">
																	<div className="panel-heading">
																		{criteria.description}
																	</div>
																	<div className="panel-body">
																		<div className="col-md-12">
																			<RB.Input addonBefore={criteria.label} type="select" ref="LIKERT5AD" name="LIKERT5AD" >
																				<option value=""> - Disagree / Agree ( 1 - 5 ) - </option>
																				<option value="2"> Fully Disagree </option>
																				<option value="4"> Disagree a little </option>
																				<option value="6"> Neither Disagree Nor Agree </option>
																				<option value="8"> Agree a little </option>
																				<option value="10"> Fully Agree </option>
																			</RB.Input>
																		</div>
																		<RB.Col md={12}>
															            	<RB.Input type="textarea" cols={3} rows={3} addonBefore="Other Comments" ref="COMMENTS5AD" name="COMMENTS5AD" ></RB.Input>
															            </RB.Col>
																	</div>
																</div>
															</div> : 
										          		criteria.evaluationTypeDVCode === 'LIKERT10AD' ?
										          			<div className="col-md-12">
																<div className="panel panel-default">
																	<div className="panel-heading">
																		{criteria.description}
																	</div>
																	<div className="panel-body">
																		<div className="col-md-12">
																			<RB.Input addonBefore={criteria.label} type="select" ref="LIKERT10AD" name="LIKERT10AD" >
																				<option value=""> - Disagree / Agree ( 1 - 10 ) - </option>
																				<option value="1"> Disagree 5 </option>
																				<option value="2"> Disagree 4 </option>
																				<option value="3"> Disagree 3 </option>
																				<option value="4"> Disagree 2 </option>
																				<option value="5"> Disagree 1 </option>
																				<option value="6"> Agree 1 </option>
																				<option value="7"> Agree 2 </option>
																				<option value="8"> Agree 3 </option>
																				<option value="9"> Agree 4 </option>
																				<option value="10"> Agree 5 </option>
																			</RB.Input>
																		</div>
																		<RB.Col md={12}>
															            	<RB.Input type="textarea" cols={3} rows={3} addonBefore="Other Comments" ref="COMMENTS10AD" name="COMMENTS10AD" ></RB.Input>
															            </RB.Col>
																	</div>
																</div>
															</div> : 
										          		criteria.evaluationTypeDVCode === 'LIKERT5LH' ?
										          			<div className="col-md-12">
																<div className="panel panel-default">
																	<div className="panel-heading">
																		{criteria.description}
																	</div>
																	<div className="panel-body">
																		<div className="col-md-12">
																			<RB.Input addonBefore={criteria.label} type="select" ref="LIKERT5LH" name="LIKERT5LH" >
																				<option value=""> - Hate / Love ( 1 - 5 ) - </option>
																				<option value="2"> Hate a lot </option>
																				<option value="4"> Hate a little </option>
																				<option value="6"> Neither Hate Nor Love </option>
																				<option value="8"> Love a little </option>
																				<option value="10"> Love a lot </option>
																			</RB.Input>
																		</div>
																		<RB.Col md={12}>
															            	<RB.Input type="textarea" cols={3} rows={3} addonBefore="Other Comments" ref="COMMENTS5LH" name="COMMENTS5LH" ></RB.Input>
															            </RB.Col>
																	</div>
																</div>
															</div> : 
										          		criteria.evaluationTypeDVCode === 'LIKERT10LH' ?
										          			<div className="col-md-12">
																<div className="panel panel-default">
																	<div className="panel-heading">
																		{criteria.description}
																	</div>
																	<div className="panel-body">
																		<div className="col-md-12">
																			<RB.Input addonBefore={criteria.label} type="select" ref="LIKERT10LH" name="LIKERT10LH" >
																				<option value=""> - Hate / Love ( 1 - 10 ) - </option>
																				<option value="1"> Hate 1 </option>
																				<option value="2"> Hate 2 </option>
																				<option value="3"> Hate 3 </option>
																				<option value="4"> Hate 4 </option>
																				<option value="5"> Hate 5 </option>
																				<option value="6"> Love 1 </option>
																				<option value="7"> Love 2 </option>
																				<option value="8"> Love 3 </option>
																				<option value="9"> Love 4 </option>
																				<option value="10"> Love 5 </option>
																			</RB.Input>
																		</div>
																		<RB.Col md={12}>
															            	<RB.Input type="textarea" cols={3} rows={3} addonBefore="Other Comments" ref="COMMENTS10LH" name="COMMENTS10LH" ></RB.Input>
															            </RB.Col>
																	</div>
																</div>
															</div> : null
													}<br/>
												</span>
							          		); 
							          	}) : null
						        }
						        <center>
									<RB.Button bsStyle="success" onClick={self.handleSubmitEval}><i className="glyphicon glyphicon-ok-sign"></i> Submit Review</RB.Button>
								</center>
							</div> : 
							<LoadingComp loadingText="Submitting Review..."></LoadingComp>
					}
					<br/>
					<RB.Row>
						<RB.Col md={12}>
							<MessagesBoxComp ref="msgBox"/>
						</RB.Col>
					</RB.Row>
				</div>
			</div>
		);
	}
});