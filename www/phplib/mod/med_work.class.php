<?php
/**
 * med_work.class.php
 * 
 * @author		hiroyuki honda
 * @date		2015-10-21
 * @copyright	
 * @version		1.0.0
 */

require_once("mod/common.class.php");
require_once("mgr/common_mgr.class.php");

class med_work extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "診療従事管理";
		//$this->header_file = "inout_room_head.tpl";
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
