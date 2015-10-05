<?php
//Author: pantingwen pantingwen@hotmail.com
//Date: 2014-7-10
//模板解析类
class Parse {
	private $_tpl; //存放静态模板文件的内容
	//初始化构造方法：读取模板文件内容保存到到$_tpl变量中
	public function __construct($tpl_file) {
		//如果文件是空的话读取失败
		if (! $this->_tpl = file_get_contents ( $tpl_file )) { //读取静态模板文件内容到$_tpl变量中
			exit ( '模板内容读取失败!' );
		}
	}
	//compile()方法：完成静态模板的解析并生成编译文件
	public function compile($parse_file, $tpl_file) {
		$this->parse (); //调用私有方法parse()完成各种标签的解析
		//如果编译文件不存在或是模板文件被修改过就生成编译文件
		if (! file_exists ( $parse_file ) || filemtime ( $tpl_file ) >= filemtime ( $parse_file )) {
			if (! file_put_contents ( $parse_file, $this->_tpl )) {
				exit ( '编译文件生成失败!' );
			}
		}
	}
	//parseVar()方法：解析普通变量
	private function parseVar() {
		$mode = '/\{\$([\w]+)\}/'; //普通变量模式
		//在模板文件中匹配模式,如果匹配成功,则替换成index.php文件中注入的变量
		if (preg_match ( $mode, $this->_tpl )) {
			$this->_tpl = preg_replace ( $mode, "<?php echo \$this->_vars['$1'];?>", $this->_tpl ); //替换成index.php文件中注入的变量
		}
	}
	//parseIf()方法：解析if语句bool
	private function parseIf() { //if语句模式
		$modeIf = '/\{if\s+\$([\w]+)\}/';
		$modeEndIf = '/\{\/if\}/';
		$modeElse = '/\{else\}/';
		//在模板文件中匹配模式,如果匹配成功,则替换成相应的php语言中的if语句
		if (preg_match ( $modeIf, $this->_tpl )) {
			if (preg_match ( $modeEndIf, $this->_tpl )) {
				$this->_tpl = preg_replace ( $modeIf, "<?php if(\$this->_vars['$1']){?>", $this->_tpl );
				$this->_tpl = preg_replace ( $modeEndIf, "<?php }?>", $this->_tpl );
				if (preg_match ( $modeElse, $this->_tpl )) {
					$this->_tpl = preg_replace ( $modeElse, "<?php }else{?>", $this->_tpl );
				}
			} else {
				exit ( 'If语句没有关闭!' );
			}
		}
	}
	//parseInclude()方法：解析普通文件包含标签
	private function parseInclude() {
		$mode = '/\{include\s+file=\"(.+)\"\}/'; //普通文件包含标签模式
		//在模板文件中匹配模式,如果匹配成功,则替换成相应的php语言中的include包含语句
		preg_match_all( $mode, $this->_tpl, $files );
		foreach($files[1] as $key=>$value){
			$tmp_mode = '/\{include\s+file=\"'.$value.'\"}/'; //普通文件包含标签模式
			$include_file=str_replace(TPL_SPLIT, '/', $value);
			if(!file_exists ( TPL_DIR .$include_file)){
				exit ( '包含文件出错!' );
			}else{
				$include_file_content=file_get_contents(TPL_DIR.$include_file);
				//加载包含文件的内容
				$this->_tpl = preg_replace ($tmp_mode,$include_file_content, $this->_tpl ); //替换成相应的php语言中的include包含语句
			}
		}
	}
	//parseCommon()方法：解析注释标签
	private function parseCommon() {
		$mode = '/{#\s+(.*)\s+#}/'; //注释标签模式
		//在模板文件中匹配模式,如果匹配成功,则替换成相应的php语言中的注释
		if (preg_match ( $mode, $this->_tpl )) {
			$this->_tpl = preg_replace ( $mode, "<?php /* '$1' */?>", $this->_tpl ); //替换成相应的php语言中的注释
		}
	}
	//parseConfig()方法：解析系统变量
	private function parseConfig() {
		$mode = '/\{\$config\.([\w]+)\}/'; //系统变量匹配模式
		//在模板文件中匹配模式,如果匹配成功,则替换成读入的系统变量的值
		//print_r($this->_configs);
		if (preg_match ( $mode, $this->_tpl )) {
			$this->_tpl = preg_replace ( $mode, "<?php echo \$this->_configs['$1'];?>", $this->_tpl ); //替换成读入的系统变量的值
		}
	}
	//parse()方法：内部调用各种解析方法
	private function parse() {
		$this->parseInclude ();
		$this->parseConfig ();
		$this->parseCommon ();
		$this->parseForeach ();
		$this->parseArray();
		$this->parseVar ();
		$this->parseIf ();
	}
	//parseForeach()方法：解析foreach语句
	private function parseForeach() {
		//foreach语句匹配模式
		$modeForeach = '/\{foreach\s+key=([\w]+)\s+item=([\w]+)\s+from=\$([\w]+)\}/';
		$modeEndForeach = '/\{\/foreach\}/';
		$modeVar = '/\{@([\w]+)\}/';
		//在模板文件中匹配模式,如果匹配成功,则替换成相应的php语言中的foreach语句
		if (preg_match ( $modeForeach, $this->_tpl )) {
			if (preg_match ( $modeEndForeach, $this->_tpl )) {
				$this->_tpl = preg_replace ( $modeForeach, "<?php foreach(\$this->_vars['$3'] as \$$1=>\$$2){?>", $this->_tpl );
				$this->_tpl = preg_replace ( $modeVar, "<?php echo \$$1;?>", $this->_tpl );
				$this->_tpl = preg_replace ( $modeEndForeach, "<?php }?>", $this->_tpl );
			} else {
				exit ('Foreach语句没有关闭!' );
			}
		}
	}
	
	//parseForeach()方法：解析foreach语句
	private function parseArray() {
		//{@value[user_id]}
		$mode = '/\{\@([\w]+)\[(\w+)\]\}/'; //普通变量模式
		//在模板文件中匹配模式,如果匹配成功,则替换成index.php文件中注入的变量
		if (preg_match ( $mode, $this->_tpl )) {
			$this->_tpl = preg_replace ( $mode, '<?php echo \$$1["$2"];?>', $this->_tpl); //替换成index.php文件中注入的变量
		}
	}
}
?>