import React from 'react';
import ReactDom from 'react-dom';
var RB = require('react-bootstrap');
import $ from 'jquery';

import MessagesBoxComp from './MessagesBoxComp';

/*
  react properties:

  opinions (opinions array of user)
  userId (current user Id)
  isSelfMovieCreator (true | false)

 */

export default React.createClass({ //OpinionsComp

  getDefaultProps: function() {
    return {
      inList: true,
    };
  },

  getInitialState: function() {
    var hasOpinion;

    if(this.props.opinions.opinionStatus === 'N') {
      hasOpinion = 'false'; //no opinion yet
    }
    
    else if(this.props.opinions.opinionStatus === 'L'){
      hasOpinion = 'true1'; //true1 means the user likes the movie
    }

    else if(this.props.opinions.opinionStatus === 'H') {
      hasOpinion = 'true2'; //true2 means the user hates the movie
    }

    return {
      opinions: this.props.opinions,
      hasOpinion: hasOpinion,
      isSelfMovieCreator: this.props.isSelfMovieCreator,
      noOfLikes: this.props.noOfLikes,
      noOfHates: this.props.noOfHates
  	};
  },

  likeMovie: function(urlString) {
    var url = MOVIERAMA.linkPrefix(urlString);
    MOVIERAMA.ajaxCall(url, 'POST', {}, this, this.cbHandleLike);
  },

  cbHandleLike: function(result) {
    console.log(result);
    if(result.likeOk) {
      this.refs.msgBox.clearErrors();
      this.refs.msgBox.addSuccess('Movie liked successfully!', false);
      setTimeout(this.refs.msgBox.clearSuccesses, 3000);
      
      var noOfLikes = this.state.noOfLikes + 1;

      this.setState({
        hasOpinion: 'true1',
        noOfLikes: parseInt(noOfLikes)
      });

      var refreshData = {
        requestorId: MOVIERAMA.globals.userId,
        targetIds: [this.state.opinions.targetId]
      };

      console.log('Refresh Data: ', refreshData);
      MOVIERAMA.ajaxCall(MOVIERAMA.linkPrefix('api/movie/getOpinionsInfo'), 'POST', refreshData, this, this.refreshData);
    }

    else {
      this.refs.msgBox.addError(result.errors);
      setTimeout(this.refs.msgBox.clearErrors, 5000);
    }
  },

  hateMovie: function(urlString) {
    var url = MOVIERAMA.linkPrefix(urlString);
    MOVIERAMA.ajaxCall(url, 'POST', {}, this, this.cbHandleHate);
  },

  cbHandleHate: function(result) {
    console.log(result);
    if(result.hateOk) {
      this.refs.msgBox.clearErrors();
      this.refs.msgBox.addSuccess('Movie hated successfully!', false);
      setTimeout(this.refs.msgBox.clearSuccesses, 3000);
      
      var noOfHates = this.state.noOfHates + 1;

      this.setState({
        hasOpinion: 'true2',
        noOfHates: parseInt(noOfHates)
      });
      
      var refreshData = {
        requestorId: MOVIERAMA.globals.userId,
        targetIds: [this.state.opinions.targetId]
      };
      
      console.log('Refresh Data: ', refreshData);
      MOVIERAMA.ajaxCall(MOVIERAMA.linkPrefix('api/movie/getOpinionsInfo'), 'POST', refreshData, this, this.refreshData);
    }

    else {
      this.refs.msgBox.addError(result.errors);
      setTimeout(this.refs.msgBox.clearErrors, 5000);
    }
  },

  unLikeMovie: function(urlString) {
    var url = MOVIERAMA.linkPrefix(urlString);
    MOVIERAMA.ajaxCall(url, 'POST', {}, this, this.cbHandleUnLike);
  },

  cbHandleUnLike: function(result) {
    console.log(result);
    if(result.unLikeOk) {
      this.refs.msgBox.clearErrors();
      this.refs.msgBox.addSuccess('Movie unliked successfully!', false);
      setTimeout(this.refs.msgBox.clearSuccesses, 3000);
      
      //var noOfLikes = this.state.noOfLikes - 1;

      this.setState({
        hasOpinion: 'false'
        //noOfLikes: parseInt(noOfLikes)
      });

      var refreshData = {
        requestorId: MOVIERAMA.globals.userId,
        targetIds: [this.state.opinions.targetId]
      };

      console.log('Refresh Data: ', refreshData);
      MOVIERAMA.ajaxCall(MOVIERAMA.linkPrefix('api/movie/getOpinionsInfo'), 'POST', refreshData, this, this.refreshData);
    }

    else {
      this.refs.msgBox.addError(result.errors);
      setTimeout(this.refs.msgBox.clearErrors, 5000);
    }
  },

  unHateMovie: function(urlString) {
    var url = MOVIERAMA.linkPrefix(urlString);
    MOVIERAMA.ajaxCall(url, 'POST', {}, this, this.cbHandleUnHate);
  },

  cbHandleUnHate: function(result) {
    console.log(result);
    if(result.unHateOk) {
      this.refs.msgBox.clearErrors();
      this.refs.msgBox.addSuccess('Movie unhated successfully!', false);
      setTimeout(this.refs.msgBox.clearSuccesses, 3000);
      
      //var noOfHates = this.state.noOfHates - 1;

      this.setState({
        hasOpinion: 'false'
        //noOfHates: parseInt(noOfHates)
      });
      
      var refreshData = {
        requestorId: MOVIERAMA.globals.userId,
        targetIds: [this.state.opinions.targetId]
      };
      
      console.log('Refresh Data: ', refreshData);
      MOVIERAMA.ajaxCall(MOVIERAMA.linkPrefix('api/movie/getOpinionsInfo'), 'POST', refreshData, this, this.refreshData);
    }

    else {
      this.refs.msgBox.addError(result.errors);
      setTimeout(this.refs.msgBox.clearErrors, 5000);
    }
  },

  refreshData: function(result) {
    console.log('REFRESHED DATA: ', result);
    this.setState({
      opinions: result.getOpinions[0]
    });

    //refresh vote counters
    MOVIERAMA.ajaxCall(MOVIERAMA.linkPrefix('api/movie/getCounters/'+result.getOpinions[0].targetId), 'POST', {}, this, this.refreshCounters);
  },

  refreshCounters: function(result) {
    console.log('REFRESHED COUNTERS: ', result);

    this.setState({
      noOfLikes: result.getCounters['likes'],
      noOfHates: result.getCounters['hates']
    });
  },

  render: function() {
     var req = this.state.opinions;
     console.log('Opinions - Render: ', req);

     var cbLike = this.likeMovie.bind(null, req.likeLink);
     var cbHate = this.hateMovie.bind(null, req.hateLink);

     var cbUnLike = this.unLikeMovie.bind(null, req.opinionLink);
     var cbUnHate = this.unHateMovie.bind(null, req.opinionLink);
     
     var style = ( this.props.inList ? { display: 'inline-block'} : null );
     
     var noOfLikes = this.state.noOfLikes;
     var noOfHates = this.state.noOfHates; 

     return (
        <span>
          {
            noOfLikes == 0 && noOfHates == 0 && !this.state.isSelfMovieCreator ?
            <p>Be the first to vote for this movie: </p> : null
            }
          {
            noOfLikes == 1 ?
              <span>{noOfLikes} like | </span> :
              <span>{noOfLikes} likes | </span>
          }
          {
            noOfHates == 1 ?
              <span>{noOfHates} hate</span> :
              <span>{noOfHates} hates</span>
          }
          <br/><br/>
          {
            MOVIERAMA.globals.userId !== req.targetId ?
              <div className="movieActionsDiv" style={style}>
                  {
                    this.state.hasOpinion === 'false' && !this.state.isSelfMovieCreator ? 
                      <span>
                        <button type="button" className="btn btn-default btn-lg btn-link" 
                          onClick={cbLike}>
                            <span><i className="fa fa-thumbs-up fa-lg"></i> Like Movie</span>
                        </button>
                        <button type="button" className="btn btn-default btn-lg btn-link" 
                          onClick={cbHate}>
                            <span><i className="fa fa-thumbs-down fa-lg"></i> Hate Movie</span>
                        </button>
                      </span> :
                    this.state.hasOpinion === 'true1' && !this.state.isSelfMovieCreator ? 
                      <span>
                        <button type="button" className="btn btn-default btn-lg btn-link" 
                          onClick={cbUnLike}>
                            <span><i className="fa fa-undo fa-lg"></i> Unlike Movie</span>
                        </button>
                        <button type="button" className="btn btn-default btn-lg btn-link" 
                          onClick={cbHate}>
                            <span><i className="fa fa-thumbs-down fa-lg"></i> Hate Movie</span>
                        </button>
                      </span> :
                    this.state.hasOpinion === 'true2' && !this.state.isSelfMovieCreator ? 
                      <span>
                        <button type="button" className="btn btn-default btn-lg btn-link" 
                          onClick={cbUnHate}>
                            <span><i className="fa fa-undo fa-lg"></i> Unhate Movie</span>
                        </button>
                        <button type="button" className="btn btn-default btn-lg btn-link" 
                          onClick={cbLike}>
                            <span><i className="fa fa-thumbs-up fa-lg"></i> Like Movie</span>
                        </button>
                      </span> : null
                  }
                  <MessagesBoxComp ref="msgBox"/>
               </div> : null
           }
         </span>
      );
  }
}); //OpinionsComp