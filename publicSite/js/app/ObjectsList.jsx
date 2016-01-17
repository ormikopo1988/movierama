import React from 'react';
import ReactDom from 'react-dom';
var moment = require('moment');
import OpinionsComp from './OpinionsComp';
import MessagesBoxComp from './MessagesBoxComp';
import IsTypeComp from './IsTypeComp';
import FlagComp from './FlagComp';
import EvaluationFormComp from './EvaluationFormComp';
var RB = require('react-bootstrap');

export default React.createClass({ //ObjectsList
  
  /*
	properties
	data: array of movies
	title
	isType
	targetUserId: '' | movieRamaUserId
	autocompleteUrl: last part of api/autoComplete/
	viewUrl: where to go e.g. profile/view | organisation/view etc.
	isLoggedIn: true | false
  */

  getInitialState: function() {
  	return {};
  },

  handleSearch: function() {
  	var val = this.refs.targetId.getValue();
  	window.location.href = MOVIERAMA.linkPrefix(this.props.viewUrl+'/'+val);
  },

  render: function() {
  	var results = this.props.data;
  	console.log('OBJECTS LIST - RENDER: ', results);
  	
  	return (
      <div classNameName="container">
		<center>
			<h1>{this.props.title}</h1>
			{
				this.props.isLoggedIn ? 
					<div clasName="well">
						<a href={MOVIERAMA.linkPrefix('profile/edit')}>
							<RB.Button bsStyle="default"><i className="fa fa-pencil smallFa"></i> Edit Profile</RB.Button>
						</a> &nbsp; &nbsp;
						<a href={MOVIERAMA.linkPrefix('movie/createForm')}>
							<RB.Button bsStyle="success"><i className="fa fa-plus smallFa"></i> New Movie</RB.Button>
						</a><br/><br/>
		            </div> : null
			}
		</center>
		<div className="panel panel-default">
			<div className="panel-heading">
				Sort By:
			</div>
			<div className="panel-body">
				{
					this.props.targetUserId === '' ?
						<span>
							<a href={MOVIERAMA.linkPrefix('movies/sort/noOfLikes')}>Likes</a> | &nbsp;
							<a href={MOVIERAMA.linkPrefix('movies/sort/noOfHates')}>Hates</a> | &nbsp;
							<a href={MOVIERAMA.linkPrefix('movies/sort/publishDateTime')}>Date</a>
						</span> :
						<span>
							<a href={MOVIERAMA.linkPrefix('movies/'+this.props.targetUserId+'/sort/noOfLikes')}>Likes</a> | &nbsp;
							<a href={MOVIERAMA.linkPrefix('movies/'+this.props.targetUserId+'/sort/noOfHates')}>Hates</a> | &nbsp;
							<a href={MOVIERAMA.linkPrefix('movies/'+this.props.targetUserId+'/sort/publishDateTime')}>Date</a>
						</span>
				}
			</div>
		</div>
		<div className="row">
	        {results.map(function(result) {
	          switch(result.isType) {
	          	case 'MOV':
	          		return (
	          			<Movie key={result.id} result={result}></Movie>
	          		);
	          		break;
	          	default:
	          		return (
	          			<p>unknown 'isType' [{result.isType}] </p>
	          		);
	          }
	        })}
	    </div>
      </div>
    );
  } 
});

var Movie = React.createClass({

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
		var createdDate = moment.utc(result.MUM_publishDateTime, 'YYYYMMDDhhmmss').fromNow();
		console.log('MOVIE - RENDER: ', result);
		return (
			<div className="col-md-12 col-sm-12">
				<div className="panel panel-default well-agency">
					<div className="sub-info">
						<IsTypeComp isType={result.isType}></IsTypeComp>
						<FlagComp whatId={result.MUM_id} whatType="MOV"></FlagComp>
						<h2>
							{result.MUM_title}
						</h2>
						<small>
							Posted by <a href={MOVIERAMA.linkPrefix('movies/'+result.MUM_movieramaUserId)}>{result.PPR_firstName} {result.PPR_lastName}</a> <i className="fa fa-clock-o smallFa"> {createdDate}</i><br/><br/>
							{ result.MUM_description }
						</small>
					</div>
					<div className="sub-info">
						{
							result.opinions ?
								<OpinionsComp noOfLikes={result.MUM_noOfLikes} noOfHates={result.MUM_noOfHates} opinions={result.opinions} userId={result.MUM_movieramaUserId} isSelfMovieCreator={result.isSelfMovieCreator}></OpinionsComp>
								:
								<span>
									{
										result.MUM_noOfLikes == 0 && result.MUM_noOfHates == 0 ?
											<p>No opinions yet.</p> : null
									}
									{
										result.MUM_noOfLikes == 1 ?
											<span>{result.MUM_noOfLikes} like | </span> :
											<span>{result.MUM_noOfLikes} likes | </span>
									}
									{
										result.MUM_noOfHates == 1 ?
											<span>{result.MUM_noOfHates} hate</span> :
											<span>{result.MUM_noOfHates} hates</span>
									}
								</span>	
						}
						{
							result.isSelfMovieCreator ? 
								<a href={MOVIERAMA.linkPrefix('movie/edit/'+result.MUM_id)}><i className="fa fa-pencil smallFa"></i> Edit Movie</a>
								: null
						}
						{
							result.hasRunningEvals === 1 ?
								<span>
									<br/><br/>
									<a href="#" onClick={this.open}><i className="fa fa-star smallFa"></i> Click here to review Movie</a> &nbsp; &nbsp;
									<a href={MOVIERAMA.linkPrefix('evaluations/view/MOV/'+result.MUM_id)}><i className="fa fa-eye smallFa"></i> View Movie Evaluations</a>
									<RB.Modal show={this.state.showModal} onHide={this.close} dialogClassName="custom-modal">
								    	<RB.Modal.Header closeButton>
							            	<RB.Modal.Title><center>Review Form</center></RB.Modal.Title>
								        </RB.Modal.Header>
								    	<RB.Modal.Body>
											<EvaluationFormComp onSuccessEval={this.onSuccessfullEvalSubmit} isType="MOV" data={result.runningEvalId}></EvaluationFormComp>
								    	</RB.Modal.Body>
								    	<RB.Modal.Footer>
							            	<center><RB.Button onClick={this.close}>Close</RB.Button></center>
							            </RB.Modal.Footer>
							    	</RB.Modal>
								</span> : null
						}
					</div>
				</div>
			</div>
		)
	}
});
