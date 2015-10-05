<?php
     class Log{
	private static function get_time_string($level,$message){
		return '['.$level.']'.(date('Y-m-d H:i:s',time())).'  '.$message."\n";
	}
	private static function init_fp(){
		$log_dir=RUN_TIME_DIR.'/logs';
		if(!file_exists($log_dir)){
		     mkdir($log_dir);
		}

		$log_file_name=$log_dir.'/log_'.date("Y-m-d",time()).'.log';
	        $fp=fopen($log_file_name,'a');//a append  w write r read
		return $fp;
	}	
	
	public static function write($message,$level='INFO'){
		if(DEBUG_MODE){
			$fp=self::init_fp();
			fwrite($fp,self::get_time_string($level,$message));
			fclose($fp);
		}
	}
     }
?>
