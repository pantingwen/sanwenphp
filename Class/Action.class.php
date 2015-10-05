<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
	class Action{
		var $view_data;
	 	var $URL;
	 	var $SUCCESS;
		var $FAILURE;
	 	//初始化数据
	 	function __construct(){
	 		$this->SUCCESS = 'success';
			$this->FAILURE = 'failure';
			$this->URL=$_SERVER['HTTP_REFERER'];
	 	}
	 	
		public function execute(){
			
		}
		
		////Author: pantingwen pantingwen@hotmail.com
		//Date: 2014-7-9
		//展示的模板:可以是View的混编模式
		
		public function display($app=null,$view=null){
			//add by pantingwen@hotmail.com 2014-08-05 begin
			//如果没有指定模板的话，可以默认获取
			if(empty($app)){
				$app=$_GET['app'];
			}
			if(empty($view)){
				$view=$_GET['action'];
				//add by pantingwen@gmail.com 2014-08-27 begin
				//修复在指定应用，没有指定action时的bug
				if(empty($view)){
					$view="Index";
				}
				//add by pantingwen@gmail.com 2014-08-27 end
			}
			//add by pantingwen@hotmail.com 2014-08-05 end
			//判断当前是不是开启了模板模式
			if (!defined('TEMPLATE_OPEN')||!TEMPLATE_OPEN){
				$view_file=APP.'/'.$app.'/View/'.$view.'.view.php';
				if (defined("LANG_VIEW")&&LANG_VIEW==true){
					//如果语言没定义，就使用英文的语言
					if(!defined("LANG")){
						$view_file=APP.'/'.$app.'/View/'.$view.'_EN_US.view.php';
					}else{
						$view_file=APP.'/'.$app.'/View/'.$view.'_'.LANG.'.view.php';
					}
				}
				if(file_exists($view_file)){
					include_once $view_file;
				}else{
					echo get_langage_message('system.lang.php','VIEW_NOT_FOUND',array('VIEW_FILE'=>$view_file));
				}
			}else{
				if(!is_file(CONFIG_TEMPLATE)){
					echo get_langage_message('template.lang.php','TEMPLATE_NEED_CONFIG_FILE');
				}else{
					include_once CONFIG_TEMPLATE;
					$tpl=new Templates();
					$tpl->set_vars($this->view_data);
					$tpl->display($app.'/'.$view);
				}
			}
		}
		
		//存入键值对的数据，用于模板的显示
		public function assign($assign_name,$assign_value){
			//判断数据都不是空的，再转入数据
			if(!empty($assign_name)){
				$this->view_data[$assign_name]=$assign_value;
			}
		}
		
		public  function getModel($model_name){
			$model_name=$model_name.'_Model';
			//new 函数不支持变量+常数的形式
			return new $model_name;
		}
		
		public function jump_url($jump_type,$jump_message){
			jump_url($jump_type, $jump_message, $this->URL, 3);
		}
		
		////Author: pantingwen pantingwen@gmail.com
		//Date: 2014-08-05
		//添加指定url的调转方式
		public function jump_with_url($jump_type,$jump_message,$jump_url){
			$jump_urls=explode("/", $jump_url);
			if(count($jump_urls)>1){
				$jump_url="index.php?app=".$jump_urls[0]."&action=".$jump_urls[1];
			}
			
			jump_url($jump_type, $jump_message, $jump_url, 3);
		}
		
		//Author: pantingwen pantingwen@gmail.com
		//Date: 2014-11-26
		//处理null数据为""
		function handlenulldata($data){
			if(empty($data)){
				return '';
			}else{
				return $data;
			}
		}
		
		
		//Author: pantingwen pantingwen@gmail.com
		//Date: 2014-11-26
		//处理null数据为""
		function get_day_for_time($time){
			$times=explode(" ", $time);
			return $times[0];
		}
	}
?>