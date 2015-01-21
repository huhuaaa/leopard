<?php
//默认时间格式
date_default_timezone_set("Asia/Shanghai");

//环境
define('EVIRONMENT', 'develop');

//根据环境显示错误
switch(EVIRONMENT){
	case 'develop':
	error_reporting(E_ALL); //报告所有错误
	break;
	case 'production':
	error_reporting(0); //关闭错误显示
	set_error_handler('error_function', E_ALL);//定义错误的处理方法
	break;
	default:
	error_reporting(0); //默认关闭错误提示
	set_error_handler('error_function', E_ALL);//定义错误的处理方法
	break;
}

//获取文件目录
$root =  dirname(__file__).'/';
//代码根目录
define('BASEPATH', $root.'../');
//根目录
define('ROOTPATH', $root);
//系统代码目录
define('SYSPATH', $root.'../system/');
//应用代码目录
define('APPPATH', $root.'../application/');
//当前访问的时间搓（秒为单位）
define('TIMESTAMP', $_SERVER['REQUEST_TIME']);
//域名
define('HTTP_HOST', $_SERVER['HTTP_HOST']);
//访问的路径带参数
define('REQUEST_URI', $_SERVER['REQUEST_URI']);
//访问路径不带参数
define('PATH_INFO', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');

use system\Leopard;
use system\loader;
use system\log;
//引入加载类
include SYSPATH.'loader.php';

/**
 * 自动加载，结合命名空间
 */
function __autoload($className){
	loader::load($className);
}

//错误处理方法
function error_function($error_level,$error_message,$error_file,$error_line,$error_context){
	//将错误记录日志，帮助修补漏洞
	log::write('ERROR', $error_level.' - '.$error_message.' in '.$error_file.' on line '.$error_line);
}

//程序入口
Leopard::run();