<?php
namespace system;
/**
 * 系统全局类
 */
class Leopard {
	/**
	 * 程序入口
	 */
	static public function run(){
		//加载配置
		config::load('config');

		//输入类初始化
		input::initialize();

		//注册路由
		//router::register('/h', 'admin/admin', 'a');

		//运行路由
		router::run();
	}
}