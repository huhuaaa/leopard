<?php
namespace system;
/**
 * 加载类
 *
 */
class loader{

	/**
	 * 静态加载类方法，根据类名称加载类文件
	 * 结合命名空间和路径规则
	 */
	static public function load($className){
		//默认查找system目录下的文件
		$path = BASEPATH.$className.'.php';
		if(file_exists($path)){
			include $path;
		}else{
			//查找不到的情况下，去查找application目录
			$path = APPPATH.$className.'.php';
			if(file_exists($path)){
				include $path;
			}else{
				if(strpos($className, 'controllers') !== FALSE){
					//404错误
					view::show_404();
				}else{
					//显示加载错误
					view::load_error($path);
				}
			}
		}
	}
}