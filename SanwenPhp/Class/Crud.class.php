<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
//Description:该类主要用来完成简单的增删改查的操作


class Crud {
	private $table_name;
	private $table_fields = array ();
	private $db;
	
	function __construct($db_type='DbMysql'){
		Log::write('Crud->__construct->$db_type:'.$db_type);
		$this->db = new $db_type;
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//保存数据
	function save($table_name, $table_fields) {
		$sql = 'insert into ' . $table_name . '(';
		foreach ( $table_fields as $key => $value ) {
			$sql = $sql . $key . ',';
		}
		$sql = substr ( $sql, 0, strlen ( $sql ) - 1 ) . ') values (';
		foreach ( $table_fields as $key => $value ) {
			//update by pantingwen@hotmail.com 20140724 begin 防止数据清除前面的0
//			if (is_numeric ( $value )) {
//				$sql = $sql . $value . ',';
//			} else {
//				$sql = $sql . '"' . $value . '",';
//			}
			$sql = $sql . '"' . $value . '",';
			//update by pantingwen@hotmail.com 20140724 begin 
		}
		$sql = substr ( $sql, 0, strlen ( $sql ) - 1 ) . ')';
		//echo $sql;
		$this->db->execue_sql_no_result ( $sql );
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//删除数据(根据record_id)
	public function remove($table_name,$table_fields){
		$sql="delete from ".$table_name.' where 1=1';
		if(!empty($table_fields)){
			foreach ($table_fields as $key=>$value){
				$sql=$sql." and ".$key.'='.$value;
			}
		}else{
			$sql=$sql." and 1=2";
		}
		$this->db->execue_sql_no_result( $sql );
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//简单查询数据，针对单独的一张表,获取指定字段的数据信息
	function get($table_name, $table_fields) {
		$sql = 'select * from ' . $table_name . ' where 1=1';
		if (!empty ( $table_fields )) {
			foreach ( $table_fields as $key => $value ) {
				if (is_numeric ( $value )) {
					$sql = $sql . ' and ' . $key . '=' . $value . ',';
				} else {
					$sql = $sql . ' and ' . $key . '="' . $value . '",';
				}
			
			}
			$sql = substr ( $sql, 0, strlen ( $sql ) - 1 );
		}
		return $this->db->execue_sql_with_result($sql);
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-08-05
	//简单的数据库表的更新
	function modify($table_name, $table_fields,$table_condition_files){
		$sql = 'update ' . $table_name . ' set ';
		foreach ( $table_fields as $key => $value ) {
			$sql = $sql . $key .'="'.$value.'",';
		}
		$sql = substr ( $sql, 0, strlen ( $sql ) - 1 );
		$sql=$sql." where 1=1 ";
		foreach($table_condition_files as $key=>$value){
			$sql=$sql."and ".$key.'="'.$value.'"';
		}
		//echo $sql;
		$this->db->execue_sql_no_result ( $sql );
	}
}
?>
