import React from 'react';
import ReactDom from 'react-dom';

var RB = require('react-bootstrap');

import MessagesBoxComp from './MessagesBoxComp';
import LoadingComp from './LoadingComp';

export default React.createClass({
  
  //properties
  //whatId id of the object to evaluate
  //whatType type of the object to evaluate
  //lookUps -> EVALUATION_TYPE and _TEMPLATES

  getInitialState: function() {
    console.log('EVALUATION FORM PROPS: ', this.props);
    return {
      loading: false
    };
  },

  handleSave: function() {
    var whatId   = this.props.whatId;
    var whatType = this.props.whatType;
    var evalTemplateId = ( this.refs.evalTemplateId != undefined ? this.refs.evalTemplateId.getValue().trim() : '');
    
    if(!evalTemplateId) {
      alert('Please fill in the required fields!');
      return;
    }

    var data = {
      whatId: whatId,
      whatType: whatType,
      evalTemplateId: evalTemplateId
    };

    //TODO - SEND DATA TO SERVER
    var url = MOVIERAMA.linkPrefix('api/evaluation/create');

    this.setState({
      loading: true
    });

    MOVIERAMA.ajaxCall(url, 'POST', data, this, this.cbHandleSave);

  },

  cbHandleSave: function(result) {
    if(result.evaluationCreatedOk === true) {
      this.refs.msgBox.clearErrors();
      this.refs.msgBox.addSuccess('Your evaluation was created successully!', false);
      setTimeout(this.refs.msgBox.clearSuccesses, 3000);

      window.location = MOVIERAMA.linkPrefix('evaluation/edit/'+this.props.whatType+'/'+result.evaluationCreatedId);
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
        <center><h3>Create Evaluation</h3></center><hr/>
        <RB.Row>
          <RB.Col md={12}>
            <RB.Input type="select" addonBefore="Template (*)" ref="evalTemplateId" name="evalTemplateId" >
              <option value=""> - Select Evaluation Template - </option>
              {MOVIERAMA.renderOptions(this.props.lookUps._TEMPLATES)}
            </RB.Input>
          </RB.Col>
        </RB.Row>
        <RB.Row>
          <center>
            {
              this.state.loading === false ?
                <RB.Button bsStyle="success" onClick={this.handleSave}><i className="glyphicon glyphicon-ok-sign"></i> Save</RB.Button> :
                <LoadingComp loadingText="Creating your Evaluation..."></LoadingComp>
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
  
});
