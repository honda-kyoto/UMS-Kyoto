<?php
/**********************************************************
* File         : prepare.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/common_mgr.class.php");

class prepare extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "準備中";
		$this->header_file = "prepare_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new common_mgr();
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
