<?php
namespace controllers\admin;
use system\view;

class admin{

	function a(){
		echo 'a';
	}

	function index(){
		//echo 'admin index';
		view::make('index',array('time'=>TIMESTAMP));
	}
}