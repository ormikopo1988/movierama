import React from 'react';
import ReactDOM from 'react-dom';
import TinyMCE from 'react-tinymce';
import RegisterBox from './RegisterBox';
import LoginBox from './LoginBox';
import ProfileEdit from './ProfileEdit';
import SiteHeaderBox from './SiteHeaderBox';
import SiteFooterBox from './SiteFooterBox';
import ObjectsList from './ObjectsList';
import MovieEdit from './MovieEdit';
import MessagesBoxComp from './MessagesBoxComp';
import CreateMovieForm from './CreateMovieForm';
import CreateEvaluationForm from './CreateEvaluationForm';
import EvaluationEdit from './EvaluationEdit';
import ViewEvaluations from './ViewEvaluations';
import ViewEvaluationResults from './ViewEvaluationResults';

import MOVIERAMA from './movierama';

function main() {
	window.MOVIERAMA = MOVIERAMA;
	window.ReactDOM = ReactDOM;
	window.React = React;
	window.TinyMCE = TinyMCE;
	window.LoginBox = LoginBox; 
	window.RegisterBox = RegisterBox;
	window.ProfileEdit = ProfileEdit;
	window.SiteHeaderBox = SiteHeaderBox;
	window.SiteFooterBox = SiteFooterBox;
	window.ObjectsList = ObjectsList;
	window.MovieEdit = MovieEdit;
	window.MessagesBoxComp = MessagesBoxComp;
	window.CreateMovieForm = CreateMovieForm;
	window.CreateEvaluationForm = CreateEvaluationForm;
	window.EvaluationEdit = EvaluationEdit;
	window.ViewEvaluations = ViewEvaluations;
	window.ViewEvaluationResults = ViewEvaluationResults;
}

main();