<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-14
//文件上传的类
	class FileUpload{
		var $file_types=array();//文件类别设置
		var $file_extends=array();//文件扩展名
		var $file_size;//文件大小设置
		var $file_base_path;
		var $file_path;//文件存放路径
		
		function __construct(){
			
		}
		
		public function upload($file_name){
			//验证文件
			$result=$this->validate_file();
			//开始上传文件
			if(empty($result)){
				
			}
		}
		
		private function validate_file(){
		foreach ($_FILES as $key=>$value){
				//如果限制类型
				if(!empty($this->file_types)){
					echo 'type'.$value['type'];
					if(!in_array($value['type'], $this->file_types)){
						return array('state'=>-1,'message'=>'文件类型不正确');
						exit();
					}
				}
				if(!empty($this->file_size)){
					if($value['size']>$this->file_size){
						return array('state'=>-2,'message'=>'文件超出最大大小的限制');
						exit();
					}
				}
				if(!empty($this->file_extends)){
					$strs=explode('.', $value['name']);
					if(!empty($this->file_extends)){
						if(count($strs)>1){
							$extention=$strs[count(strs)];
							echo '$extention'.$extention;
							if(!in_array($extention, $this->file_extends)){
								return array('state'=>-3,'message'=>'文件后缀名不允许上传');
								exit();
							}
						}
					}
				}
			}
		}
	}
?>