<?php

/*
	 * 这是邮箱发送邮件的基本类
	 * */
class Phone {
	//	function send($phoneNumberTo, $sendMessage) {
	//		$url = "http://211.154.154.151/smsSend.do";
	//		$username = "xxkj";
	//		$password = "ybKbbQJX";
	//		$post_data = array ("username" => $username, "password" => md5 ( $username . md5 ( $password ) ), "mobile" => $phoneNumberTo, "content" => $sendMessage, "dstime" => '', "charset" => "" );
	//		$ch = curl_init ();
	//		curl_setopt ( $ch, CURLOPT_URL, $url );
	//		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	//		// post数据
	//		curl_setopt ( $ch, CURLOPT_POST, 1 );
	//		// post的变量
	//		print_r($post_data);
	//		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
	//		$output = curl_exec ( $ch );
	//		curl_close ( $ch );
	//		//打印获得的数据
	//		print_r ( $output );
	//	}
	

	function send($phoneNumberTo, $sendMessage) {
		$user_name = "xxkj";
		$password = "ybKbbQJX";
		$password = md5 ( $user_name . md5 ( $password ) );
		//初始化
		$ch = curl_init ();
		//设置选项，包括URL
		$exe_url = "http://211.154.154.151/smsSend.do?username=" . $user_name . "&password=" . $password . "&mobile=" . $phoneNumberTo . "&content=" . $sendMessage . "&dstime=";
		///echo $exe_url;
		curl_setopt ( $ch, CURLOPT_URL, $exe_url );
		
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		//执行并获取HTML文档内容
		$output = curl_exec ( $ch );
		//释放curl句柄
		curl_close ( $ch );
		//打印获得的数据
		return $output;
	}
}

?>