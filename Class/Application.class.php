<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-11
//应用的主要入口
	class Application{
		public function run(){	
			include_once SANWEN_LIB.'/Common/functions.php';
			include_used_file (); //include the file that used
		        Log::write('begin initApp');
			$this->initApp();
			//use the filter
			$filter=new Filter();
			//filter all url
			Log::write('begin to filter url');
			if($filter->filter_all_url()){
				$include_file=get_include_file();
				if (file_exists ($include_file)) {
					Log::write('get_include_file:'.$include_file);
					include_once $include_file;
					execute (null,null);
				} else {
					//对应地址的类不存在的时候，执行默认首页
					if (file_exists ( APP . '/Index/Action/Index.action.php' )) {
						include_once APP . '/Index/Action/Index.action.php';
						execute ("Index","Index");
					} else {
						//默认首页不存在的时候，抛出错误信息
						echo get_langage_message ( 'system.lang.php', 'DEFAULT_INDEX_NOT_FOUND');
					}
				}
			}else{
				echo get_langage_message ( 'system.lang.php','CAN_NOT_ACCESS');
			}
		}
		
		
		private function initApp(){
			if (!defined("LANG")){
				define("LANG", 'EN_US');
			}
			if(!defined("TEMPLATE_OPEN")){
				define("TEMPLATE_OPEN", FALSE);
			}
			define("INDEX_FILE", get_execute_file());
		}
	}
?>
