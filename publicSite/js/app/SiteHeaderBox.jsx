import React from 'react';
import ReactDom from 'react-dom';
import ReactBootstrap from 'react-bootstrap';

export default React.createClass({
  
  render: function() { 
  	console.log('SiteHeader.render: props:', this.props);
  	var userData = this.props.userData || null;
  	var userId = this.props.userData.movieRamaUserId || null;

    return (
        <nav className="navbar navbar-inverse navbar-fixed-top">
			<div className="container-fluid">
				<div className="navbar-header">
					<button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" data-aria-expanded="false" data-aria-controls="navbar">
						<span className="sr-only">Toggle navigation</span>
	                    <span className="icon-bar"></span>
	                    <span className="icon-bar"></span>
	                    <span className="icon-bar"></span>
					</button>
					<a className="navbar-brand" href={MOVIERAMA.linkPrefix('')}><i className="fa fa-film"></i> MovieRama</a>
				</div>
				<div className="collapse navbar-collapse" id="navbar">
		            <ul className="nav navbar-right navbar-nav">
		            	{
			            	userData.movieRamaUserId != null ?
				            	<li role="presentation">
				            		<a href="javascript:void(0)">Hello {userData.firstName} {userData.lastName}! How are you today?</a>
								</li>
				            	 : null
		            	}
	                    <li role="presentation">
	                    	{
	                    		(userId === null) ? 
	                    			<a href={MOVIERAMA.linkPrefix('login')}><i className="fa fa-sign-in smallFa"></i> Login</a> 
	                    			: null
	                    	}
	                    </li>
	                    <li role="presentation"> 
                    		{(userData.movieRamaUserId == null) ? 
                    			<a href={MOVIERAMA.linkPrefix('register')}><i className="fa fa-registered smallFa"></i> Register</a> : 
                    			<a href={MOVIERAMA.linkPrefix('logout')}><i className="fa fa-sign-out smallFa"></i> Logout</a>
                    		}
                    	</li>
		            </ul>
		        </div>
			</div>
        </nav>
    );
  }
  
});
