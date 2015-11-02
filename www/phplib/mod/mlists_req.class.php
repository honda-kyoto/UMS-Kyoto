<?php
/**********************************************************
* File         : mlists_req.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/mlists_list_common.class.php");
require_once("mgr/mlists_req_mgr.class.php");

class mlists_req extends mlists_list_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->req_only = true;

		$this->page_title = "メーリングリスト承認待ち一覧";
		$this->header_file = "mlists_req_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new mlists_req_mgr();
	}


}

?>
