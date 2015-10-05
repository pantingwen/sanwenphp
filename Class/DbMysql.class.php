<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-8
class DbMysql {
	//申明的时候需要添加上$，使用的时候不要$
	var $con;
	var $db_info;
	var $crud;
	function __construct() {
		if(defined('CONFIG_DB')){
			if(is_file(CONFIG_DB)){
				$this->db_info = include CONFIG_DB;
			}else {
				exit(get_langage_message('system.lang.php', 'DB_CONFIG_NOT_FOUND'));
			}
		}else{
			exit(get_langage_message('system.lang.php', 'DB_CONFIG_NOT_STE'));
		}
	}
	//私有方法是不别的类来引用的
	private function get_connect() {
		$connect = mysql_connect ( $this->db_info ['host'], $this->db_info ['user'], $this->db_info ['password'] );
		if (! $connect) {
			die ( 'Could not connect:' . mysql_error () );
		}
		//add by pantingwen@hotmail.com add for:中文乱码
		mysql_query("SET NAMES 'utf8'",$connect);//设置字符编码，这里必须是utf8不是utf-8
		return $connect;
	}
	
	//创建数据
	public function create_db($db_name) {
		$this->con = $this->get_connect ();
		if (mysql_query ( "CREATE DATABASE " . $db_name, $this->con )) {
			$this->log_message("Database created");
		} else {
			$this->log_message("Error creating database: " . mysql_error ());
		}
		mysql_close ( $this->con );
	}
	
	public function select_db($db_name) {
		if (empty ( $this->con )) {
			$this->con = $this->get_connect ();
		}
		if (mysql_select_db ( $db_name, $this->con )) {
		
		} else {
			//如果配置文件配置了可以自动生成数据库
			if ($this->db_info ['generate_flag'] == 'Y') {
				$this->create_db ( $db_name );
				mysql_select_db ( $db_name, $this->con );
			} else {
				$this->log_message("Database not selectd");
			}
		}
	}
	
	public function execue_sql_no_result($sql) {
		$this->con = $this->get_connect ();
		$this->select_db ( $this->db_info ['db_name'] );
		if(mysql_query ( $sql, $this->con )){
			
		}else{
			$this->log_message("shibai". mysql_error ());
		}
		mysql_close ( $this->con);
	}
	
	//add by pantingwen@gmail.com 20140827 begin
	//主要返回表查询的结果，以二维数组的形式
	public function execue_sql_with_result($sql) {
		$result_array=array();
		$row=array();
		$this->con = $this->get_connect ();
		$this->select_db ( $this->db_info ['db_name'] );
		$res=mysql_query ( $sql, $this->con );
		if($res){
			//获取第一行
			$row=mysql_fetch_array($res);
			//一直循环，直到没有数据
			while(!empty($row)) {
					array_push($result_array, $row);
					$row=mysql_fetch_array($res);
				}
 			mysql_free_result($res);
		}else{
			$this->log_message($sql.'<br/>');
			$this->log_message("shibai". mysql_error ());
		}
		mysql_close ( $this->con);
		return $result_array;
	}
	//add by pantingwen@gmail.com 20140827 end
	
	//add by pantingwen@gmail.com 20140827 begin
	//返回数据查询的句柄
	public function execue_sql_with_table_fields($sql) {
		$result_array=array();
		$row=null;
		$row=array();
		$property=array();
		$this->con = $this->get_connect ();
		$this->select_db ( $this->db_info ['db_name'] );
		$res=mysql_query ( $sql, $this->con );
		//循环出所有的字段
		//zend studio中assignment in condition解决方法 
		//zend studio中while ($row = mysql_fetch_array($result))出现assignment in condition提示
		//解决方法：
		//将之改为：while (($row = mysql_fetch_array($result)) != false)即可。
		//while($property=mysql_fetch_field($res)){
		while(($property=mysql_fetch_field($res))!=false){
			//$property=mysql_fetch_field($res);
			//$row=array("filed_name"=>$property->name,"filed_require"=>$property->not_null,"filed_type"=>$property->type);
 			array_push($result_array, $property);
		}
		mysql_close ($this->con);
		return $result_array;
	}
	//add by pantingwen@gmail.com 20140827 end
	
	//add by pantingwen@gmail.com 20141126 
	function log_message($message){
		if(DEBUG_MODE){
			echo $message;
		}
	}
}
?>