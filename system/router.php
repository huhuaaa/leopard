<?php
namespace system;
/**
* 路由类
*/
class router
{
	//已注册的路由数组
	static public $router = array();
	/**
	 * 路由初始化
	 */
	static public function run(){
		$paths = self::analyze();
		$paths = self::rewrite($paths);
		$className = '';
		$function = '';
		$count = count($paths);
		$params = array();
		for ($i = 0; $i < $count; $i++) {
			if($function){
				array_push($params, $paths[$i]);
				continue;
			}
			$className .= '\\'.$paths[$i];
			if(file_exists(APPPATH.'controllers/'.$className.'.php')){
				if($i < $count - 1){
					$function = $paths[$i + 1];
				}
			}
		}
		$controller = FALSE;
		if(!$className){
			$className = '\\'.config::get('default_controller');
		}
		if(!$function){
			$function = 'index';
		}
		$className = '\\controllers'.$className;
		$controller = new $className();
		//检验对象是否存在对应方法
		if(method_exists($controller, $function)){
			//调用对象的方法
			call_user_func_array(array($controller,$function), $params);
		}else{
			//不存在显示404
			view::show_404();
		}
		return $controller;
	}

	/**
	 * 解析路径规则
	 */
	static public function analyze(){
		$uri = preg_replace('/^\/|\/$/', '', PATH_INFO);
		$array = array();
		if(isset(self::$router[$uri])){
			$array[0] = self::$router[$uri][0];
			$array[1] = self::$router[$uri][1];
		}else{
			if($uri){
				$array = explode('/', $uri);
			}
		}
		return $array;
	}

	/**
	 * 注册路由
	 */
	static public function register($uri, $controller, $function = 'index'){
		//去掉uri的起始斜杠
		if(strpos($uri, '/') === 0 || strrpos($uri, '/') == strlen($uri)){
			$uri = preg_replace('/^\/|\/$/', '', $uri);
		}
		//替换斜杠为命名空间的反斜杠
		if(strpos($controller, '/') !== FALSE){
			$controller = strtr($controller, '/', '\\');
		}
		self::$router[$uri] = array($controller, $function);
	}

	/**
	 * 路由重写方法
	 *@param array $arguments
	 */
	static public function rewrite($arguments){
		return $arguments;
	}

	/**
	 * 跳转地址
	 *@param string $uri 跳转地址
	 */
	static public function redirect($uri){
		header('location:'.$uri);
		exit;
	}
}
