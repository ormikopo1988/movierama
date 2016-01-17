import React from 'react';
import ReactDom from 'react-dom';
var RB = require('react-bootstrap');
import $ from 'jquery';

import MessagesBoxComp from './MessagesBoxComp';
import LoadingComp from './LoadingComp';

export default React.createClass({ //CreateMovieForm

  getInitialState: function() {
    
    console.log(this.props.data);
    
    return {
      loading: false
  	};
  },

  handleSave: function() {
    var title = this.refs.title.getValue().trim() || '';
    var description = this.refs.description.getValue().trim() || '';
    
    if(!title || !description) {
      alert('You must fill all the required input fields!');
      return;
    }

    var data = {
      title: title,
      description: description
    }

    var url = MOVIERAMA.linkPrefix('api/movie/create');
    //data to send to server!
    this.setState({
      loading: true
    });
    MOVIERAMA.ajaxCall(url, 'POST', data, this, this.cbHandleSave);
  },

  cbHandleSave: function(result) {
    if(result.movieCreatedOk === true) {
      this.refs.msgBox.clearErrors();
      this.refs.msgBox.addSuccess('Your movie was created successully!', false);
      setTimeout(this.refs.msgBox.clearSuccesses, 3000);

      //window.location = MOVIERAMA.linkPrefix('movie/edit/'+result.movieCreatedId);
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
        <RB.Well>
          <center><h3>Create Movie</h3></center><hr/>
          <RB.Row>
            <RB.Col md={12}>
              <RB.Input type="text" addonBefore="Title (*)" placeholder="Title..." ref="title" name="title" />
            </RB.Col>
          </RB.Row>
          <RB.Row>
            <RB.Col md={12}>
              <RB.Input rows={4} type="textarea" addonBefore="Description (*)" placeholder="Description..." ref="description" name="description" />
            </RB.Col>
          </RB.Row><br/>
          <RB.Row>
            <center>
              {
                this.state.loading === false ?
                  <RB.Button bsStyle="success" onClick={this.handleSave}><i className="glyphicon glyphicon-ok-sign"></i> Save</RB.Button> :
                  <LoadingComp loadingText="Saving Movie..."></LoadingComp>
              }
            </center>
          </RB.Row><br/>
          <RB.Row>
            <RB.Col md={12}>
              <MessagesBoxComp ref="msgBox"/>
            </RB.Col>
          </RB.Row>
        </RB.Well>
      );
  }
}); //CreateGroupForm