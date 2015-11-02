<?php
/**********************************************************
* File         : system_error.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/system_error_mgr.class.php");

class system_error extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "システムエラー";
		$this->header_file = "system_error_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new system_error_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInit()
	{

		return 1;
	}

}

?>
