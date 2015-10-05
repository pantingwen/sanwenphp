<?php

/*
	 * 这是邮箱发送邮件的基本类
	 * */
class Mail {
	/**
	 * Enter send 这是邮件发送的方法
	 * @param unknown_type $sendHost 发件人host,比如qq是smtp.qq.com
	 * @param unknown_type $sendFrom 发件人的邮箱号
	 * @param unknown_type $sendFromPassword 发件人的密码
	 * @param unknown_type $sendTo 接受方的邮件地址
	 * @param unknown_type $mailSubject 发送的主题内容
	 * @param unknown_type $mailBody 发送的主题内容
	 * @param unknown_type $mailAltBody body提示
	 */
	function send($sendHost, $sendFrom, $sendFromName, $sendFromPassword, $sendTo, $mailSubject, $mailBody, $mailAltBody) {
		$sendMailBody = '<html><head><meta http-equiv="Content-Language" content="zh-cn"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
		require_once 'Mail/class.smtp.php';
		require_once 'Mail/class.phpmailer.php';
		$mail = new PHPMailer ();
		$address = $sendTo;
		$mail->isSMTP ();
		$mail->Host = $sendHost;
		$mail->Charset = 'utf-8';
		$mail->Encoding = "base64";
		$mail->SMTPAuth = true;
		$mail->Username = $sendFrom;
		$mail->Password = $sendFromPassword;
		$mail->setFrom ( $sendFrom );
		$mail->FromName = "=?utf-8?B?" . base64_encode ($sendFromName) . "?=";
		$mail->AddAddress ( $address ); //
		$mail->AddReplyTo ( "", "" );
		$mail->IsHTML ( true );
		$mail->Subject = $mailSubject;
		$mail->Subject = "=?utf-8?B?" . base64_encode ( $mailSubject ) . "?=";
		$sendMailBody = $sendMailBody . $mailBody;
		$sendMailBody = $sendMailBody . '</body></html>';
		//echo $sendMailBody;
		$mail->Body = $mailBody; //邮件内容 
		$mail->AltBody = $mailAltBody;
		//附加信息，可以省略 
		if (! $mail->Send ()) {
			echo "Send  Error<p>";
			echo "Error Message： " . $mail->ErrorInfo;
			return $mail->ErrorInfo;
			exit ();
		}else{
			return true;
		}
		
	}
}

?>