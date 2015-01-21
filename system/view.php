<?php
namespace system;

/**
 * 视图类
 */
class view{
	/**
	 * 构建视图
	 *@param string or array $views 视图相对路径
	 *@param array $date 传递到视图的参数
	 */
	static public function make($views, $data = array()){
		extract($data);
		if(is_array($views)){
			foreach ($views as $view) {
				$path = APPPATH.'views/'.$view.'.php';
				if(!file_exists($path)){
					self::load_error($path);
				}else{
					include $path;
				}
			}
		}else if(is_string($views)){
			$path = APPPATH.'views/'.$views.'.php';
			if(!file_exists($path)){
				self::load_error($path);
			}else{
				include $path;
			}
		}
	}

	/**
	 * 返回json格式字符串
	 *@param array $data
	 */
	static function json($data){
		echo json_encode($data);
		exit;
	}

	/**
	 * 404页面
	 */
	static public function show_404(){
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		$override = config::get('404_override');
		if($override){
			router::redirect($override);
		}else{
			include APPPATH.'error/404.php';
		}
		exit;
	}

	/**
	 * 加载类或者文件错误
	 *@param string $path 加载错误路径显示
	 */
	static public function load_error($path){
		header('HTTP/1.1 500 Internal Server Error');
		header("Status: 500 file is not exists");
		$message = $path.' is not exists.';
		include APPPATH.'error/loadError.php';
		exit;
	}
}