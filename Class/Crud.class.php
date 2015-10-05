<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
//Description:该类主要用来完成简单的增删改查的操作


class Crud {
	private $table_name;
	private $table_fields = array ();
	private $db;
	
	function __construct() {
		$this->db = new DbMysql ();
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
				//modify by pantingwen@gmail.com 20140812 修复bug begin
				/*
				if (is_numeric ( $value )) {
					$sql = $sql . ' and ' . $key . '=' . $value . ',';
				} else {
					$sql = $sql . ' and ' . $key . '="' . $value . '",';
				}
				*/
				$sql=$sql . ' and ' . $key . '="' . $value . '"';
				//modify by pantingwen@gmail.com 20140812 修复bug end
			}
			//$sql = substr ( $sql, 0, strlen ( $sql ) - 1 ); ---delete by pantingwen@gmail.com 20140812 修复bug 
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
	
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//简单查询数据，针对单独的一张表,获取指定字段的数据信息,指定当前页数，每页大小
	function get_by_page($table_name, $table_fields,$not_table_condition_fields,$order_by_contidion,$page_now,$page_size) {
		$sql = 'select * from ' . $table_name . ' where 1=1';
		if (!empty ( $table_fields )) {
			foreach ( $table_fields as $key => $value ) {
				//modify by pantingwen@gmail.com 20140812 修复bug begin
				/*
				if (is_numeric ( $value )) {
					$sql = $sql . ' and ' . $key . '=' . $value . ',';
				} else {
					$sql = $sql . ' and ' . $key . '="' . $value . '",';
				}
				*/
				$sql=$sql . ' and ' . $key . '="' . $value . '"';
				//echo "the sql is ".$sql;
				//modify by pantingwen@gmail.com 20140812 修复bug end
			}
			//$sql = substr ( $sql, 0, strlen ( $sql ) - 1 ); ---delete by pantingwen@gmail.com 20140812 修复bug 
		}
		//排除条件
		if (!empty($not_table_condition_fields)){
			foreach ( $not_table_condition_fields as $key => $value ) {
				$sql=$sql . ' and ' . $key . '!="' . $value . '"';
			}
		}
		
		if(!empty($order_by_contidion)){
			$sql=$sql . ' order by ';
			foreach ( $order_by_contidion as $key => $value ) {
				$sql=$sql. $key . ' ' . $value;
			}
		}
		$sql=$sql.' limit '.($page_now-1)*$page_size.','.$page_size;
		//echo $sql;
		return $this->db->execue_sql_with_result($sql);
	}
	
	
//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//简单查询数据，针对单独的一张表,获取指定字段的数据信息,指定当前页数，每页大小
	function get_by_in($table_name, $table_fields) {
		$tmp_sql=null;
		//print_r($table_fields);
		$sql = 'select * from ' . $table_name . ' where 1=1';
		if (!empty ( $table_fields )) {
			foreach ( $table_fields as $key => $value ) {
				$tmp_sql=" and ".$key." in (";
				foreach ($value as $my_key=>$my_value){
					$tmp_sql=$tmp_sql.'"'.$my_value.'",';
				}
				$tmp_sql=substr($tmp_sql, 0,strlen($tmp_sql)-1).')';
				$sql=$sql.$tmp_sql;
			}
		}
		//echo $sql;
		return $this->db->execue_sql_with_result($sql);
	}
	
	
//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-9
	//简单查询数据，针对单独的一张表,获取指定字段的数据信息,指定当前页数，每页大小
	function get_total_count($table_name, $table_fields,$not_table_condition_fields) {
		$sql = 'select count(1) from ' . $table_name . ' where 1=1';
		if (!empty ( $table_fields )) {
			foreach ( $table_fields as $key => $value ) {
				//modify by pantingwen@gmail.com 20140812 修复bug begin
				/*
				if (is_numeric ( $value )) {
					$sql = $sql . ' and ' . $key . '=' . $value . ',';
				} else {
					$sql = $sql . ' and ' . $key . '="' . $value . '",';
				}
				*/
				$sql=$sql . ' and ' . $key . '="' . $value . '"';
				//echo "the sql is ".$sql;
				//modify by pantingwen@gmail.com 20140812 修复bug end
			}
			//$sql = substr ( $sql, 0, strlen ( $sql ) - 1 ); ---delete by pantingwen@gmail.com 20140812 修复bug 
		}
		//排除条件
		if (!empty($not_table_condition_fields)){
			foreach ( $not_table_condition_fields as $key => $value ) {
				$sql=$sql . ' and ' . $key . '!="' . $value . '"';
			}
		}
		//echo $sql;
		return $this->db->execue_sql_with_result($sql);
	}
}
?>