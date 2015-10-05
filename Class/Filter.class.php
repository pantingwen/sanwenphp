<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-9
class Filter {
	
	//Author: pantingwen pantingwen@gmail.com
	//Date: 2014-08-05
	//申明后面函数需要实现的方法
	function file_url_with_rule() {
		//默认返回的null
		return null;
	}
	
	//拦截所有的浏览地址
	//return false 表示关闭网站中。。。
	function filter_all_url() {
		//d5f69f00b0604999035c49c212081949 apple_client
		//93469beb5cbdf77fa78debe115fb6c76 web_client
		//6606e98ce4b0b76b11c5f2de80a6c2d7 android_client
		$client_infos = array ('d5f69f00b0604999035c49c212081949', '93469beb5cbdf77fa78debe115fb6c76', '6606e98ce4b0b76b11c5f2de80a6c2d7' );
		$client_info = $_REQUEST ['client_info'];
		if (empty($client_info)) {
			return array ('errcode' => '-1', 'msg' => '客户端信息不能为空!' );
		} else {
			if (in_array ( $client_info, $client_infos )) {
				return true;
			} else {
				return array ('errcode' => '-2', 'msg' => '客户端信息错误，不能访问!' );
			}
		}
	}
}
?>