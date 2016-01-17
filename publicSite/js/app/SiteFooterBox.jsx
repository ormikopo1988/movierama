import React from 'react';
import ReactDom from 'react-dom';
import ReactBootstrap from 'react-bootstrap';

export default React.createClass({
	  
  render: function() {
  	var f6s = MOVIERAMA.imgPrefix('$social/f6s_footer.png');
  	var twitter = MOVIERAMA.imgPrefix('$social/twitter_footer.png');
  	var facebook = MOVIERAMA.imgPrefix('$social/facebook_footer.png');
  	
  	return (
    	<nav className="navbar navbar-default navbar-inverse">
	        <footer>
			    <div className="container-fluid">
			    	<div className="navbar-footer" id="navbar-footer">
				        <div className="col-xs-12 col-sm-12 col-md-12">
				        	<center>
				            <div>
				                <img height="16" className="sSpace" src={twitter} alt="img" />
				                <img height="16" className="sSpace" src={facebook} alt="img" />
				                <br/><br/>
				            </div>
				            <p className="sSpace"><span className="copyr">Copyright &copy; 2016 </span><span className="disclaim hidden-xs">MovieRama</span></p>
				        	</center>
				        </div>
			        </div>
			    </div>
			</footer>
		</nav>
    );
  }
  
});
