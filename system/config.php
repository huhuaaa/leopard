<?php
namespace system;
/**
 * 配置操作类,提供静态操作方法
 */
class config{

	//存放配置的数组
	static protected $config = array();

	/**
	 * 读取配置的方法
	 *@param string $path php配置文件的地址,不需要扩展名
	 */
	static public function read($path){
		$filePath = $path.'.php';
		if(file_exists($filePath)){
			include $filePath;
			foreach ($config as $key => $value) {
				self::$config[$key] = $value;
			}
		}else{
			//显示加载文件错误
			view::show_error($filePath);
		}
		return isset($config) ? $config : FALSE;
	}

	/**
	 * 获取配置对应键值的值
	 *@param string $key
	 */
	static public function get($key){
		return isset(self::$config[$key]) ? self::$config[$key] : NULL;
	}

	/**
	 * 加载配置方法
	 *@param string name 指定名称，读取application/config目录下对应的配置文件
	 */
	static public function load($name){
		return self::read(APPPATH.'config/'.$name);
	}
}