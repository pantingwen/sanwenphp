<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-9
	class Filter{
		
		//Author: pantingwen pantingwen@gmail.com
	    //Date: 2014-08-05
	    //申明后面函数需要实现的方法
		function file_url_with_rule(){
			return FALSE;
		}
		
		//拦截所有的浏览地址
		function filter_all_url(){
			return true;
		}
	}
?>