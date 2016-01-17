import React from 'react';
import ReactDom from 'react-dom';

var RB = require('react-bootstrap');
import MessagesBoxComp from './MessagesBoxComp';

export default React.createClass({
  
  //properties
  //whatId id of the object to report
  //whatType type of the object to report

  getInitialState: function() {
    return {
      showFlagText: false
    }
  },

  showFlagText: function() {
    this.setState({
      showFlagText: true
    });
  },

  handleReport: function() {
    var whatId   = this.props.whatId;
    var whatType = this.props.whatType;

    var flagText = ReactDOM.findDOMNode(this.refs.flagText).value.trim();
    

    if(!flagText) {
      alert('Please fill in the required fields!');
      return;
    }

    var data = {
      whatId: whatId,
      whatType: whatType,
      flagText: flagText
    };

    //TODO - SEND DATA TO SERVER
    var url = MOVIERAMA.linkPrefix('api/movie/flag');

    MOVIERAMA.ajaxCall(url, 'POST', data, this, this.cbHandleFlag);

  },

  cbHandleFlag: function(result) {
    if(result.flagItemOk === true) {
      this.refs.msgBox.clearErrors();
      this.refs.msgBox.addSuccess('Your Report/Flagging was saved. Thank you!', false);
      setTimeout(this.refs.msgBox.clearSuccesses, 3000);
      this.setState({
        showFlagText: false
      })
    }
    else {
      this.refs.msgBox.addError(result.errors);
    }
  },

  closeFlagText: function() {
    this.setState({
      showFlagText: false
    });
  },

  render: function() { 
    var style = { color: 'CAD3D4' };

    return (
      <span>
        {
            !this.state.showFlagText ?
              <a href="javascript:void(0)" style={style} onClick={this.showFlagText}>
                <i className="fa fa-flag smallFa isType"></i><i>Report</i>
              </a> : 
              <div className="flagDiv" >
                <center><h3>Submit a Report</h3></center>
                <input type="text" className="form-control" placeholder="Type what to report..." aria-describedby="basic-addon-13" ref="flagText" /><br/>
                <center>
                  <button className="button" className="btn btn-success" onClick={this.handleReport}>Submit</button> 
                  &nbsp;&nbsp;
                  <button className="btn-link" onClick={this.closeFlagText}>Cancel</button>
                </center>
              </div>
        }
        <MessagesBoxComp ref="msgBox"/>
      </span>
    );
  }
  
});
