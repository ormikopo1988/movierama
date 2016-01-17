import React from 'react';
import ReactDom from 'react-dom';
var moment = require('moment');
import MessagesBoxComp from './MessagesBoxComp';
import LoadingComp from './LoadingComp';
var RB = require('react-bootstrap');

export default React.createClass({ //GroupEdit

  render: function() {;
  	var result = this.props.data;
  	return (
      <div classNameName="container">
		<div className="row">
        	<Movie key={result.id} result={result} title={this.props.title}></Movie> :
	    </div>
      </div>
    );
  } 
});

var Movie = React.createClass({

	getInitialState: function() {
		return {
			loading: false
		}
	},

	handleSave: function() {
	    var title = this.refs.title.getValue().trim() || '';
	    var description = this.refs.description.getValue().trim() || '';
	    
	    if(!title || !description) {
	      alert('You must fill all the required input fields!');
	      return;
	    }

	    //data to send to server!
	    var data = {
	      id: this.props.result.id,
	      title: title,
	      description: description,
	    }

	    console.log('MOVIE DATA UPDATE: ', data);

	    var url = MOVIERAMA.linkPrefix('api/movie/saveMovie');
	    
	    this.setState({
	      loading: true
	    });

	    MOVIERAMA.ajaxCall(url, 'POST', data, this, this.cbHandleSave);
	},

	cbHandleSave: function(result) {
		if(result.movieCreatedOk === true) {
			this.refs.msgBox.clearErrors();
			this.refs.msgBox.addSuccess('Your movie was updated successully!', false);
			setTimeout(this.refs.msgBox.clearSuccesses, 3000);
		}
		else {
			this.refs.msgBox.addError(result.errors);
		}
		this.setState({
	      loading: false
	    });
	},

	handleDelete: function() {
		var result = confirm('Are you sure you want to delete this movie?');
		if (result) {
		    //data to send to server!
		    var data = {
		      id: this.props.result.id
		    }

		    var url = MOVIERAMA.linkPrefix('api/movie/delete');
		    
		    this.setState({
		      loading: true
		    });

		    MOVIERAMA.ajaxCall(url, 'POST', data, this, this.cbHandleDelete);
		} else {
			return;
		}
	},

	cbHandleDelete: function(result) {
		if(result.movieDeletedOk === true) {
			this.refs.msgBox.clearErrors();
			this.refs.msgBox.addSuccess('Your movie was deleted successully!', false);
			window.location = MOVIERAMA.linkPrefix('');
		}
		else {
			this.refs.msgBox.addError(result.errors);
		}
		this.setState({
	      loading: false
	    });
	},

	render: function() {
		var result = this.props.result;
		console.log('RESULT: ', result);

		return (
			<RB.Well>
	          <center><h3>Edit Movie {'"'+this.props.title+'"'}</h3></center><hr/>
	          	<a href={MOVIERAMA.linkPrefix('createEvaluation/MOV/'+result.id)}><i className="fa fa-star smallFa"></i> Create Evaluation for Movie</a>
				<br/>
				<a href={MOVIERAMA.linkPrefix('evaluations/view/MOV/'+result.id)}><i className="fa fa-eye-slash smallFa"></i> View Current Movie Evaluations</a>
				<br/><br/>
	          <RB.Row>
	            <RB.Col md={12}>
	              <RB.Input type="text" defaultValue={result.title} addonBefore="Title (*)" placeholder="Title..." ref="title" name="title" />
	            </RB.Col>
	          </RB.Row>
	          <RB.Row>
	            <RB.Col md={12}>
	              <RB.Input rows={4} defaultValue={result.description} type="textarea" addonBefore="Description (*)" placeholder="Description..." ref="description" name="description" />
	            </RB.Col>
	          </RB.Row><br/>
	          <RB.Row>
	            <center>
	            	{
               			this.state.loading === false ?
                  			<RB.Button bsStyle="success" onClick={this.handleSave}><i className="glyphicon glyphicon-ok-sign"></i> Save</RB.Button> :
                  			<LoadingComp loadingText="Saving Movie..."></LoadingComp>
             	    }
	            </center><br/><br/>
	            <center>
	            	{
               			this.state.loading === false ?
                  			<RB.Button bsStyle="danger" onClick={this.handleDelete}><i className="fa fa-ban smallFa"></i> Delete Movie</RB.Button> :
                  			<LoadingComp loadingText="Deleting Movie..."></LoadingComp>
             	    }
	            </center>
	          </RB.Row><br/>
	          <RB.Row>
	            <RB.Col md={12}>
	              <MessagesBoxComp ref="msgBox"/>
	            </RB.Col>
	          </RB.Row>
	        </RB.Well>
		)
	}
});