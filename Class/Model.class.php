<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
class Model {
	var $db;
	var $crud;
	var $jump_url;
	var $SUCCESS;
	var $FAILURE;
	
	function __construct() {
		$this->db = new DbMysql ();
		$this->crud = new Crud ();
		$this->SUCCESS = 'success';
		$this->FAILURE = 'failure';
		$this->jump_url = $_SERVER ['HTTP_REFERER'];
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//Description:验证字段属性
	function validate() {
		return null;
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-08-06
	//Description:自动填充字段的数据
	function autoassisn(){
		return null;
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//Description:这个方法主要是用来保存数据
	function save($table_name, $table_fields) {
		$validate = $this->validate ();
		$autoassisn=$this->autoassisn();
		$return_array = array ();
		//修改validate的逻辑，防止空验证的时候报错
		if (! empty ( $validate )) {
			foreach ( $validate as $key => $value ) {
				if (is_array ( $value )) {
					foreach ( $value as $sub_key => $sub_value ) {
						//验证该字段是不是需要填写
						if ($sub_value == 'required') {
							if (empty ( $table_fields [$key] )) {
								array_push ( $return_array, get_langage_message ( 'system.lang.php', 'FIELD_REQUIRED', array ('FIELD_NAME' => $key ) ) );
							}
						}
						//验证字段是不是是数字
						if ($sub_value == 'numeral') {
							if (! is_numeric ( $table_fields [$key] ) && ! empty ( $table_fields [$key] )) {
								array_push ( $return_array, get_langage_message ( 'system.lang.php', 'NUMERAL_REQUIRED', array ('FIELD_NAME' => $key ) ) );
							}
						}
						//验证字段是不是唯一，主要针对用户名注册等等
						if ($sub_value == 'uniqued') {
							$return_arr = $this->get ( $table_name, array ($key => $table_fields [$key] ) );
							if (! empty ( $return_arr ) && ! empty ( $table_fields [$key] )) {
								array_push ( $return_array, get_langage_message ( 'system.lang.php', 'UNIQUED_REQUIRED', array ('FIELD_NAME' => $key ) ) );
							}
						}
					}
				} else {
					if ($value == 'required') {
						if (empty ( $table_fields [$key] )) {
							array_push ( $return_array, get_langage_message ( 'system.lang.php', 'FIELD_REQUIRED', array ('FIELD_NAME' => $key ) ) );
						}
					}
					if ($value == 'numeral') {
						if (! is_numeric ( $table_fields [$key] ) && ! empty ( $table_fields [$key] )) {
							array_push ( $return_array, get_langage_message ( 'system.lang.php', 'NUMERAL_REQUIRED', array ('FIELD_NAME' => $key ) ) );
						}
					}
					
					if ($value == 'uniqued') {
						$return_arr = $this->get ( $table_name, array ($key => $table_fields [$key] ) );
						if (! empty ( $return_arr ) && ! empty ( $table_fields [$key] )) {
							array_push ( $return_array, get_langage_message ( 'system.lang.php', 'UNIQUED_REQUIRED', array ('FIELD_NAME' => $key ) ) );
						}
					}
				}
			}
		}
		
		//自动赋值的逻辑
		if(!empty($autoassisn)){
			foreach ($autoassisn as $key=>$value){
				//数组里面添加值
				$table_fields[$key]=$value;
			}
		}
		
		if (empty ( $return_array )) {
			$this->crud->save ( $table_name, $table_fields );
			return array ('jump_type' => $this->SUCCESS, 'jump_message' => get_langage_message ( "system.lang.php", "SAVE_SUCCESSED" ) );
		} else {
			$jump_message = null;
			foreach ( $return_array as $key => $value ) {
				$jump_message = $jump_message . $value . ',';
			}
			$jump_message = substr ( $jump_message, 0, strlen ( $jump_message ) - 1 ); //去除最后的一个,
			return array ('jump_type' => $this->FAILURE, 'jump_message' => $jump_message );
		}
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//Description:这个方法主要是用来获取数据
	function get($table_name, $table_fields = NULL) {
		return $this->crud->get ( $table_name, $table_fields );
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//Description:这个方法主要是用来删除数据
	function remove($table_name, $table_fields) {
		$this->crud->remove ( $table_name, $table_fields );
		return array ('jump_type' => $this->SUCCESS, 'jump_message' => get_langage_message ( "system.lang.php", "REMOVE_SUCCESSED" ) );
	}
	
	

	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-08-09
	//Description:这个方法主要是用来修改数据
	function modify($table_name, $table_fields,$table_condition_files) {
		//Author: pantingwen pantingwen@hotmail.com
	    //Date: 2014-09-27
		$autoassisn=$this->autoassisn();
		//自动赋值的逻辑
		if(!empty($autoassisn)){
			foreach ($autoassisn as $key=>$value){
				//数组里面添加值,最后更新日期
				if($key=='last_update_date'){
					$table_fields[$key]=$value;
				}
				if($key=='last_update_by'){
					$table_fields[$key]=$value;
				}
			}
		}
		$this->crud->modify($table_name, $table_fields,$table_condition_files);
		return array ('jump_type' => $this->SUCCESS, 'jump_message' => get_langage_message ( "system.lang.php", "MODIFY_SUCCESSED" ) );
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//Description:这个方法主要是用来获取数据
	function get_by_page($table_name, $table_fields = NULL,$not_table_condition_fields=NULL,$order_by_contidion=null,$page_now, $page_size) {
		//print_r($not_table_condition_fields);
		return $this->crud->get_by_page($table_name, $table_fields,$not_table_condition_fields,$order_by_contidion, $page_now, $page_size);
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//Description:这个方法主要是用来获取数据
	function get_by_in($table_name, $table_fields) {
		//print_r($not_table_condition_fields);
		return $this->crud->get_by_in($table_name, $table_fields);
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//Description:这个方法主要是用来获取页数的大小
	function get_total_page($table_name, $table_fields=null,$not_table_condition_fields=null,$order_by_contidion=null,$page_now,$page_size) {
		//print_r($not_table_condition_fields);
		$return_arr=$this->crud->get_total_count($table_name, $table_fields,$not_table_condition_fields,$order_by_contidion, $page_now, $page_size);
		if(!empty($return_arr[0])){
			$total_count=$return_arr[0][0];
			return ceil($total_count/$page_size);
		}
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//Description:这个方法主要是用来获取页数的大小
	function get_total_count($table_name, $table_fields=null,$not_table_condition_fields=null,$order_by_contidion=null,$page_now=null,$page_size=null) {
		//print_r($not_table_condition_fields);
		$return_arr=$this->crud->get_total_count($table_name, $table_fields,$not_table_condition_fields,$order_by_contidion, $page_now, $page_size);
		if(!empty($return_arr[0])){
			$total_count=$return_arr[0][0];
			return $total_count;
		}
	}
}
?>