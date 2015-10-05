<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
	 define('SANWEN_LIB', './SanwenPhp');//设置框架程序入口
	 define('SANWEN_LIB_CLASS', SANWEN_LIB.'/Class/');//引用的类
	 define('SANWEN_LIB_CLASS_EXTENDS', SANWEN_LIB_CLASS.'/Extends/');//引入扩展类
	 define('SANWEN_LIB_LANG', SANWEN_LIB.'/Lang/');//设置语言文件入口
	 define('SANWEN_LIB_LANG_EXTENDS', SANWEN_LIB_LANG.'/Extends/');//设置语言文件入口
	 define('SANWEN_LIB_CONFIG', SANWEN_LIB.'/Config/');//设置配置文件入口
	 
	 include_once SANWEN_LIB_CLASS.'Application.class.php';
	 $app=new Application();
	 $app->run();
?>