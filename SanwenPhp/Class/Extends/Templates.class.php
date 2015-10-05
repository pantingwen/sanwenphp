<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-10
//模板类
class Templates {
	private $_vars = array (); //存放模板引擎注入的普通变量
	private $_configs = array (); //载入的系统变量
	private $tpl_file; //模板文件路径
	private $parse_file; //编译文件路径
	private $cache_file; //缓存文件路径
	//模板构造方法,主要完成相关目录是否存在的检测,以及将系统变量的值读入到$_configs变量中
	public function __construct() {
		$this->is_dir_exists ();
		$this->getConfig ();
	}
	
	//Author: pantingwen pantingwen@hotmail.com
	//Date: 2014-7-10
	//Description:设置数据
	public function set_vars($vars){
		$this->_vars=$vars;
	}
	//display()方法：完成与编译,缓存相关的一些功能
	public function display($file) {
		$this->tpl_file = TPL_DIR . $file.TPL_EXTENDTION; //设置模板文件路径
		if (! file_exists ( $this->tpl_file )) {
			exit (  get_langage_message("template.lang.php", 'TEMPLATE_FILE_NOT_FOUND',array('TEMPLATE_FILE'=>$this->tpl_file)) );
		}
		$this->parse_file = TPL_C_DIR . md5 ( $file ) . '.php'; //设置编译文件路径
		$parse = new Parse ( $this->tpl_file ); //初始化模板解析类
		$parse->compile ( $this->parse_file, $this->tpl_file ); //解析静态模板文件,生成编译文件
		

		//判断是否需要重新生成缓存文件
		$this->cache ($file);
	}
	//assign()方法：接收从index.php文件分配过来的变量的值,将它们保存在$_vars变量中
	public function assign($var, $value) {
		if (isset ( $var ) && ! empty ( $var )) { //判断模板变量是否有设置,且不能为空
			$this->_vars [$var] = $value; //接收从文件分配过来的变量的值,将它们保存在$_vars变量中
		} else {
			exit ( get_langage_message("template.lang.php", 'TEMPLATE_FILE_NOT_FOUND') );
		}
	
	}
	//getConfig()方法：将系统变量的值读入到$_configs变量中
	private function getConfig() {
		if (file_exists(CONFIG.'/system.config.xml' )) { //判断系统配置文件是否存在
			$sxe = simplexml_load_file ( CONFIG . '/system.config.xml' ); //载入系统配置文件
			$taglib = $sxe->xpath ( '/root/taglib' ); //使用xpath()方法读取相关节点
			foreach ( $taglib as $value ) {
				$this->_configs ["$value->name"] = $value->value; //将系统变量的值读入到$_configs变量中
			}
		}
	}
	//is_dir_exists()方法：相关目录是否存在的检测
	private function is_dir_exists() {
		if (! is_dir ( TPL_DIR )) { //检测是否存在模板文件夹
			exit (get_langage_message('template.lang.php', 'TEMPLATE_DIR_NOT_FOUND') );
		}
		if (! is_dir ( TPL_C_DIR )) { //检测是否存在编译文件夹
			exit ( '编译文件夹不存在!' );
		}
		if (! is_dir ( CACHE_DIR )) { //检测是否存在缓存文件夹
			exit ( '缓存文件夹不存在!' );
		}
	}
	//cache()方法：完成与缓存相关的一些功能
	private function cache($file) {
		$request_url=$_SERVER['REQUEST_URI'];
		$request_url=strstr($request_url, '?');
		$this->cache_file = CACHE_DIR . md5 ( $file. $request_url) . '.html'; //设置缓存文件路径
		//如果开启缓存,缓存文件存在且模板文件没有被修改过,直接载入缓存文件
		if (IS_CACHE) {
			//filemtime() 函数返回文件内容上次的修改时间(并且不是调试模式)
			$cache_time=-1;
			if(defined("CACHE_TIME")){
				$cache_time=CACHE_TIME;
			}
			if (file_exists ( $this->cache_file ) && filemtime ( $this->cache_file ) >= filemtime ( $this->parse_file )&&!DEBUG_MODE) {
				//如果文件的生成时间+缓存的时间大于当前时间
				//add by pantingwen@hotmail.com 为了添加缓存时间的限制
				if(filemtime($this->cache_file)+$cache_time>=time()){
					include $this->cache_file; //载入缓存文件
					return;
				}
			}
		}
		//判断是否开启缓存,如果开启就生成静态html文件,否则,直接载入编译文件
		if (IS_CACHE) {
			IS_CACHE ? ob_start () : null;
			include $this->parse_file;
			file_put_contents ( $this->cache_file, ob_get_contents () ); //生成静态html缓存文件
			ob_end_clean ();
			include $this->cache_file; //载入静态html缓存文件
		} else {
			include $this->parse_file; //载入编译文件
		}
	}
}
?>