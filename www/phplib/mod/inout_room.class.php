<?php
/**********************************************************
* File         : inout_room.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/common_mgr.class.php");

class inout_room extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "入退室カード管理";
		$this->header_file = "inout_room_head.tpl";
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
