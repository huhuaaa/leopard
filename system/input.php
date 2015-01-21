<?php
namespace system;
/**
 * 获取输入的类
 */
class input{
	//GET参数数组
	static public $get = array();
	//POST参数数组
	static public $post = array();

	static public $upload_msg = '';

	/**
	 * input 类初始化设置
	 */
	static public function initialize(){

	}

	/**
	 * 读取GET参数对应键值
	 *@param string $key 键
	 *@param boolean $xss_clean 防止xss攻击
	 */
	static public function get($index = NULL, $xss_clean = FALSE){
		if($index === NULL){
			if(empty(self::$get) && !empty($_GET)){
				foreach ($_GET as $key => $value) {
					self::$get[$key] = self::fetch($_GET, $key, $xss_clean);
				}
			}
			return self::$get;
		}
		if(!isset(self::$get[$index])){
			self::$get[$index] = self::fetch($_GET, $index, $xss_clean);
		}
		return self::$get[$index];
	}

	/**
	 * 读取POST参数对应键值
	 *@param string $key 键
	 *@param boolean $xss_clean 防止xss攻击
	 */
	static public function post($index = NULL, $xss_clean = FALSE){
		if($index === NULL){
			if(empty(self::$post) && !empty($_POST)){
				foreach ($_POST as $key => $value) {
					self::$post[$key] = self::fetch($_POST, $key, $xss_clean);
				}
			}
			return self::$post;
		}
		if(!isset(self::$post[$index])){
			self::$post[$index] = self::fetch($_POST, $index, $xss_clean);
		}
		return self::$post[$index];
	}

	/**
	 * 读取GET或者POST参数对应的键值，POST优先
	 *@param string $key 键
	 *@param boolean $xss_clean 防止xss攻击
	 */
	static public function get_post($index, $xss_clean = FALSE){
		$value = self::post($index, $xss_clean);
		return $value !== FALSE ? $value : self::get($index, $xss_clean);
	}

	/**
	 * 清理xss攻击, 需要优化一下效率
	 */
	static public function xss_clean($val){
		// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed    
		// this prevents some character re-spacing such as <java\0script>    
		// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs    
		$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);    

		// straight replacements, the user should never need these since they're normal characters    
		// this prevents like <IMG SRC=@avascript:alert('XSS')>    
		$search = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';   
		$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';    
		$search .= '1234567890!@#$%^&*()';   
		$search .= '~`";:?+/={}[]-_|\'\\';
		$length = strlen($search);
		for ($i = 0; $i < $length; $i++) {   
		  // ;? matches the ;, which is optional   
		  // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars   
		  $ascii = ord($search[$i]);
		  // @ @ search for the hex values   
		  $val = preg_replace('/(&#[xX]0{0,8}'.dechex($ascii).';?)/i', $search[$i], $val); // with a ;   
		  // @ @ 0{0,7} matches '0' zero to seven times    
		  $val = preg_replace('/(&#0{0,8}'.$ascii.';?)/', $search[$i], $val); // with a ;   
		}   

		// now the only remaining whitespace attacks are \t, \n, and \r   
		$ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');   
		$ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');   
		$ra = array_merge($ra1, $ra2);
		$size = sizeof($ra);

		$found = true; // keep replacing as long as the previous round replaced something   
		while ($found == true) {   
		  $val_before = $val;   
		  for ($i = 0; $i < $size; $i++) {   
		     $pattern = '/';
		     $len = strlen($ra[$i]);
		     for ($j = 0; $j < $len; $j++) {   
		        if ($j > 0) {   
		           $pattern .= '(';    
		           $pattern .= '(&#[xX]0{0,8}([9ab]);)';   
		           $pattern .= '|';    
		           $pattern .= '|(&#0{0,8}([9|10|13]);)';   
		           $pattern .= ')*';   
		        }   
		        $pattern .= $ra[$i][$j];   
		     }   
		     $pattern .= '/i';    
		     $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag    
		     $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags    
		     if ($val_before == $val) {    
		        // no replacements were made, so exit the loop    
		        $found = false;    
		     }    
		  }    
		}    
		return $val;   
	}

	/**
	 * 从数据源数组中取数据
	 *@param array $array 数据源数组
	 *@param string $index 键值
	 *@param boolean $xss_clean 防止xss攻击
	 */
	static public function fetch($array, $index, $xss_clean = FALSE){

		if (!isset($array[$index]))
		{
			return FALSE;
		}

		if ($xss_clean === TRUE)
		{
			return self::xss_clean($array[$index]);
		}

		return $array[$index];
	}

	/**
	 * 获取上传文件
	 *@param string $key 键
	 */
	static public function file($key){
		return isset($_FILES[$key]) ? $_FILES[$key] : FALSE;
	}

	/**
	 * 将上传文件保存到指定目录
	 *@param array $config
	 */
	static public function upload($config){
		//$config = array('name'=>'file','dir'=>ROOTPATH.'upload/', 'filename'=>'j.jpg','limit'=>1024, 'allow'=>'jpg|png'); limit单位为KB
		if(isset($config['name']) && isset($config['dir'])){
			$file = self::file($config['name']);
			if($file && $file['name']){
				$ext = substr($file['name'], strrpos($file['name'], '.') + 1);
				$filename = isset($config['filename']) ? $config['filename'].'.'.$ext : $file['name'];
				$without_ext = substr($filename, 0, strrpos($filename, '.'));
				$filepath = $config['dir'].'/'.$filename;
				$allow = isset($config['allow']) && $config['allow'] ? explode('|', strtolower($config['allow'])) : array();
				if(empty($allow) || array_search(strtolower($ext), $allow) !== FALSE){
					if(file_exists($config['dir']) && is_dir($config['dir'])){
						if(!isset($config['limit']) || $file['size'] <= $config['limit'] * 1024){
							if(!move_uploaded_file($file['tmp_name'], $filepath)){
								self::$upload_msg = '文件存储失败';
							}else{
								//存储成功后饭后文件信息
								return array('name'=>$filename,'size'=>$file['size'],'path'=>$filepath,'type'=>$file['type'],'ext'=>$ext,'without_ext'=>$without_ext);
							}
						}else{
							self::$upload_msg = '文件大小超过限制';
						}
					}else{
						self::$upload_msg = '存储目录不存在';
					}
				}else{
					self::$upload_msg = '文件类型不符合';
				}
			}else{
				self::$upload_msg = '找不到指定的上传文件';
			}
		}else{
			self::$upload_msg = '缺少必要的参数';
		}
		return FALSE;
	}

	/**
	 * 获取上传文件出错的提示信息
	 */
	static public function upload_error(){
		return self::$upload_msg;
	}
}