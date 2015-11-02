<?php
/**********************************************************
* File         : apps_pending.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/apps_regist_common.class.php");
require_once("mgr/apps_pending_mgr.class.php");

class apps_pending extends apps_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "機器詳細（申請中）";
		$this->header_file = "apps_pending_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new apps_pending_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runView()
	{
		$this->setEditData();

		return 1;
	}

	function runCancel()
	{
		$ret = $this->oMgr->cancelAppEntry($this->request['app_id'], $this->request['entry_no']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_CANCEL);
	}

}

?>
