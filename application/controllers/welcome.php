<?php
namespace controllers;
use system\Leopard;
use system\config;
use system\loader;
use system\router;
use system\controller;
use system\model;
use system\view;
use system\input;
use system\log;
use system\db;

class welcome extends controller{
	function __constrcut(){

	}

	function index(){
		$db = new DB();
		echo 'index';
	}

	//上传模拟控制器
	function upload(){
		$file = input::upload(array('name'=>'file','dir'=>ROOTPATH.'upload','allow'=>'jpg|png', 'filename'=>'1', 'limit'=> 700)); //limit KB
		if(!$file){
			var_dump(input::upload_error());
		}else{
			file_get_contents($file['path']);
			var_dump($file);
		}
		$html = <<<EOT
		<meta charset="utf-8" />
		<form enctype="multipart/form-data" method="post">
			<input type="file" name="file" />
			<input type="submit" value="上传" />
		</form>
EOT;
		echo $html;
	}
}
