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
			die ( 'Could not connect: ' . mysql_error () );
		}
		//add by pantingwen@hotmail.com add for:中文乱码
		mysql_query("SET NAMES 'utf8'",$connect);//设置字符编码，这里必须是utf8不是utf-8
		return $connect;
	}
	
	//创建数据
	public function create_db($db_name) {
		$this->con = $this->get_connect ();
		if (mysql_query ( "CREATE DATABASE " . $db_name, $this->con )) {
			echo "Database created";
		} else {
			echo "Error creating database: " . mysql_error ();
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
				echo "Database not selectd";
			}
		}
	}
	
	public function execue_sql_no_result($sql) {
		$this->con = $this->get_connect ();
		$this->select_db ( $this->db_info ['db_name'] );
		if(mysql_query ( $sql, $this->con )){
			
		}else{
			echo "shibai". mysql_error ();
		}
		mysql_close ( $this->con);
	}
	
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
			echo $sql.'<br/>';
			echo "shibai". mysql_error ();
		}
		mysql_close ( $this->con);
		return $result_array;
	}
}
?>