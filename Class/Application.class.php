<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-11
//应用的主要入口
class Application {
	public function run() {
		include_once SANWEN_LIB . '/Common/functions.php';
		include_used_file (); //引入需要使用到的文件
		$this->initApp ();
		//加载拦截器
		$filter = new Filter ();
		//拦截全部的地址
		
		//根据返回的状态来控制输出的东西
		$return_data=$filter->filter_all_url ();
		if(!is_array($return_data)){
			if ($filter->filter_all_url ()) {
				if (file_exists ( get_include_file () )) {
					include_once get_include_file ();
					execute ( null, null );
				} else {
					//对应地址的类不存在的时候，执行默认首页
					if (file_exists ( APP . '/Index/Action/Index.action.php' )) {
						include_once APP . '/Index/Action/Index.action.php';
						execute ( "Index", "Index" );
					} else {
						//默认首页不存在的时候，抛出错误信息
						echo get_langage_message ( 'system.lang.php', 'DEFAULT_INDEX_NOT_FOUND' );
					}
				}
			} else {
				echo get_langage_message ( 'system.lang.php', 'CAN_NOT_ACCESS' );
			}
		}else{
			echo json_encode($return_data);
		}
	}
	
	//初始化引用信息
	private function initApp() {
		include_once SANWEN_LIB . '/Common/functions.php';
		//初始化数据LANG语言环境
		if (! defined ( "LANG" )) {
			define ( "LANG", 'EN_US' );
		}
		//初始化是不是打开模板FALSE
		if (! defined ( "TEMPLATE_OPEN" )) {
			define ( "TEMPLATE_OPEN", FALSE );
		}
		//初始化数据库配置文件CONFIG_DB
		if (! defined ( "CONFIG_DB" )) {
			//判断默认的文件是不是存在
			if (! is_file ( "config/db.config.php" )) {
				echo get_langage_message ( "system.lang.php", "DB_CONFIG_NOT_STE" );
			} else {
				//定义数据配置文件
				define("CONFIG_DB","config/db.config.php");
			}
		}
		
		if(!defined("APP_GENERATE")){
			define("APP_GENERATE",false);
		}
		
		
		if(!defined("LOG_LEVEL")){
			//定义为最高级别的调试输出
			define("APP_GENERATE",7);
		}
		
		define ( "INDEX_FILE", get_execute_file () );
	}
}
?>