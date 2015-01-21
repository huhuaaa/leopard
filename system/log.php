<?php
namespace system;
/**
 * 日志类
 */
class log{
	/**
	 * 写日志函数
	 *@param string $type 日志信息类型
	 *@param string $msg 日志内容
	 */
	static public function write($type, $msg){
		$TYPE = strtoupper($type);
		//非开发环境，不写debug日志
		if($TYPE == 'DEBUG' && EVIRONMENT != 'develop'){
			return TRUE;
		}
		$logPath = APPPATH.'logs/log-'.date('Y-m-d',TIMESTAMP).'.php';
		$message = $TYPE.' - '.date('H:i:s').' --> '.$msg."\n";
		if(!$fp = @fopen($logPath, 'a')){
			return FALSE;
		}
		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);
		return TRUE;
	}
}