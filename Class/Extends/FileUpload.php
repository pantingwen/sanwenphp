<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-14
//文件上传的类
class FileUpload {
	var $file_types = array (); //文件类别设置
	var $file_extends = array (); //文件扩展名
	var $file_size; //文件大小设置
	var $file_base_path;
	var $file_path; //文件存放路径
	

	function __construct() {
	
	}
	
	public function upload($file_name) {
		//验证文件
		$result = $this->validate_file ();
		//开始上传文件
		if (empty ( $result )) {
		
		}
	}
	
	private function validate_file() {
		foreach ( $_FILES as $key => $value ) {
			//如果限制类型
			if (! empty ( $this->file_types )) {
				echo 'type' . $value ['type'];
				if (! in_array ( $value ['type'], $this->file_types )) {
					return array ('state' => - 1, 'message' => '文件类型不正确' );
					exit ();
				}
			}
			if (! empty ( $this->file_size )) {
				if ($value ['size'] > $this->file_size) {
					return array ('state' => - 2, 'message' => '文件超出最大大小的限制' );
					exit ();
				}
			}
			if (! empty ( $this->file_extends )) {
				$strs = explode ( '.', $value ['name'] );
				if (! empty ( $this->file_extends )) {
					if (count ( $strs ) > 1) {
						$extention = $strs [count ( strs )];
						echo '$extention' . $extention;
						if (! in_array ( $extention, $this->file_extends )) {
							return array ('state' => - 3, 'message' => '文件后缀名不允许上传' );
							exit ();
						}
					}
				}
			}
		}
	}
	
	//图片上传的方法
	function upload_imgage($files, $save_file_path) {
		$upload_url = '';
		if ($files ["error"] > 0) {
			echo "Error: " . $files ["error"];
		} else {
			if (! empty ( $files ["type"] )) {
				if ((($files ["type"] == "image/gif") || ($files ["type"] == "image/jpeg") || ($files ["type"] == "image/pjpeg"))|| ($files ["type"] == "image/png")) {
					$name = explode ( '.', $files ["name"] );
					$upload_url = $save_file_path . time () . '_' . rand ( 100, 999 ) . '.' . $name [1];
					move_uploaded_file ( $files ["tmp_name"], $upload_url );
				} else {
					echo $files ["type"];
					echo "<br/>";
					echo "file type not allowed";
				}
			}else{
				$file_names=explode(".", $files['name']);
				if(in_array($file_names[1], array('jpg','png','gif'))){
					$upload_url = $save_file_path . time () . '_' . rand ( 100, 999 ) . '.' . $file_names [1];
					move_uploaded_file ( $files ["tmp_name"], $upload_url );
				}else{
					echo $file_names[1];
					echo "<br/>";
					echo "file type not allowed";
				}
			}
		}
		return $upload_url;
	}
}
?>