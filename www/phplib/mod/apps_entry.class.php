<?php
/**********************************************************
* File         : apps_entry.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/apps_regist_common.class.php");
require_once("mgr/apps_entry_mgr.class.php");

class apps_entry extends apps_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "機器申請";
		$this->header_file = "apps_entry_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new apps_entry_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		$this->request = array();

		$this->oMgr->clearTempVlanList();

		$this->setInputData();

		return 1;
	}

	function runRetry()
	{
		$this->oMgr->clearTempVlanList();

		$this->setEntryData();

		return 1;
	}

	function runReload()
	{
		$this->setInputData();

		return 1;
	}

	function runEntry()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setInputData();
			return 1;
		}

		$ret = $this->oMgr->insertAppData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_ENTRY);

	}

}

?>
