<?php
/**********************************************************
* File         : mlists_pending.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/mlists_regist_common.class.php");
require_once("mgr/mlists_pending_mgr.class.php");

class mlists_pending extends mlists_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "メーリングリスト申請（申請中）";
		$this->header_file = "mlists_pending_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new mlists_pending_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runView()
	{
		$this->setEntryData();

		return 1;
	}

	function runCancel()
	{
		$ret = $this->oMgr->cancelMlistEntry($this->request['mlist_id'], $this->request['entry_no']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_CANCEL);
	}

}

?>
