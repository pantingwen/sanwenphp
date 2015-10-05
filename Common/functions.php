<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8


function get_app(){
	if(array_key_exists('app',$_GET)){
             $app = $_GET ['app'];
        }else{
             $app='Index';
        }
	return $app;
}

function get_action(){
	if(array_key_exists('action',$_GET)){
	     $action=$_GET['action'];
	}else{
	     $action="Index";	
	}
	return $action;
}
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
//Description:设置要引入的文件路径
function get_include_file() {
        $app=get_app();
	$action=get_action();	
	return APP . '/' . $app . '/Action/' . $action . '.action.php';
}

//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
//Description:执行主方法
function execute($app,$action) {
	//传递进来的参数为空的话，才去获取URL的地址
	if(empty($app)){
		$app = get_app();
	}
	if(empty($action)){
		$action =get_action();
	}
	$new_action = $action . '_Action';
	$ac = new $new_action ();
	$ac->execute ();
}

//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
//Description:获取语言文件的语言信息
//Parameters:1.$lang_file 对应的语言文件
//           2.$lang 语言环境
//           3.$short_message 语言短码
//           4.$parameters 参数，以{}最为变量替换
function get_langage_message($lang_file, $short_message, $parameters=null) {
	$lang=LANG;
	if (file_exists ( SANWEN_LIB_LANG . $lang_file )) {
		//对应于返回字符串的文件，需要每次都重新include，不能使用include_once,要不然后面的数据就获取不到
		$langs = include(SANWEN_LIB_LANG . $lang_file);
		$message = $langs [$lang] [$short_message];
		if (! empty ( $parameters )) {
			foreach ( $parameters as $key => $value ) {
				$message = str_replace ( '{' . $key . '}', $value, $message );
			}
		}
		return $message;
	}elseif (file_exists ( SANWEN_LIB_LANG_EXTENDS . $lang_file )){
		$langs = include(SANWEN_LIB_LANG_EXTENDS . $lang_file);
		$message = $langs [$lang] [$short_message];
		if (! empty ( $parameters )) {
			foreach ( $parameters as $key => $value ) {
				$message = str_replace ( '{' . $key . '}', $value, $message );
			}
		}
		return $message;
	} else {
		switch (LANG){
			case 'ZH_CN':
				return "语言文件没找到";
				break;
			case 'ZH_TW':
				return "語言文件沒找到";
				break;
			case 'EN_US':
				return "Language file not found";
				break;
			default:
					return 'Unkown The Language';
		}
	}
}

//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
//Description:引入需要使用到的文件
function include_used_file() {
	//注释，在引用文件的时候，使用费require的话，文件不存在就会报错，是include不存在的时候
	//只是报一个警告，使用include_once，如果之前引入了就不会再引入
	if (is_dir ( SANWEN_LIB_CLASS )) {
		$libs = opendir ( SANWEN_LIB_CLASS );
		while ( ($lib_file = readdir ( $libs )) !== false ) {
			if (strlen ( $lib_file ) > 3) { //大于3去除.和..的限制
				if (strlen ( strstr ( $lib_file, 'class.php' ) ) > 0) {
					include_once SANWEN_LIB_CLASS . $lib_file; //程序主要要引入的类
				} else {
					$extend_libs = opendir ( SANWEN_LIB_CLASS . $lib_file );
					while ( ($extend_lib_file = readdir ( $extend_libs )) !== false ) {
						if (strlen ( $extend_lib_file ) > 3) {
							include_once SANWEN_LIB_CLASS . $lib_file . '/' . $extend_lib_file; //程序主要要引入的类
						}
					}
				}
			
			}
		}
	}
	//include APP.'/Index/Model/Index.model.php';//程序使用的Model类
	
	//modify by pantingwen@gmail.com begin for:Notice: Undefined index: app
	if(array_key_exists('app',$_POST)){
		$app=$_GET['app'];
	}
	//modify by pantingwen@gmail.com end for:Notice: Undefined index: app
	//处理地址里面没有应用的情况
	if (empty ( $app )) {
		$app = 'Index';
	}
	//引入对应的使用到的Model文件
	$tmp_dir = APP . '/' . $app . '/Model/';
	if (is_dir ( $tmp_dir )) {
		$dh = opendir ( $tmp_dir );
		while ( ($file = readdir ( $dh )) !== false ) {
			if (strlen ( $file ) > 3) { //大于3去除.和..的限制
				include_once $tmp_dir . $file;
			}
		}
		closedir ( $dh );
	}
	
	//引入拦截器Filter
	//add by pantingwen@gmail.com 2014-08-05
	$tmp_dir = APP . '/' . $app . '/Filter/';
	if (is_dir ( $tmp_dir )) {
		$dh = opendir ( $tmp_dir );
		while ( ($file = readdir ( $dh )) !== false ) {
			if (strlen ( $file ) > 3) { //大于3去除.和..的限制
				include_once $tmp_dir . $file;
			}
		}
		closedir ( $dh );
	}
	
	//引入配置文件
	include_once SANWEN_LIB_CONFIG.'constant.config.php';
}

//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
//Description:URL地址跳转
//Parameters:1.跳转信息
//           2.跳转的地址
//           3.跳转的时间
function jump_url($jump_type, $jump_message, $jump_url, $jump_time) {
	$jump_types = array ('success', 'failure' );
	if (in_array ( $jump_type, $jump_types )) {
		$file_content = file_get_contents ( SANWEN_LIB . '/Template/jump_url.html' );
		$file_content = str_replace ( "{jump_type}", $jump_type, $file_content );
		$file_content = str_replace ( "{jump_message}", $jump_message, $file_content );
		$file_content = str_replace ( "{jump_url}", $jump_url, $file_content );
		$file_content = str_replace ( "{jump_time}", $jump_time, $file_content );
		echo $file_content;
	} else {
		echo get_langage_message('system.lang.php','JUMP_TYPE_NOT_ALLOWED',array('JUMP_TYPE'=>$jump_type));
	}
}

 function get_execute_file(){
 	//获取正在执行的文件
 	$execute_file=$_SERVER['SCRIPT_NAME'];
	$strings=explode('/', $execute_file);
	return $strings[count($strings)-1];
}

?>
