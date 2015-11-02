<?php
/**********************************************************
* File         : apps_req.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/apps_list_common.class.php");
require_once("mgr/apps_req_mgr.class.php");

class apps_req extends apps_list_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->req_only = true;

		$this->page_title = "機器承認待ち一覧";
		$this->header_file = "apps_req_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new apps_req_mgr();
	}


}

?>
