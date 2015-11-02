<?php
/**********************************************************
* File         : apps_edit.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/apps_regist_common.class.php");
require_once("mgr/apps_edit_mgr.class.php");

class apps_edit extends apps_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "機器詳細";
		$this->header_file = "apps_edit_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new apps_edit_mgr();
	}

	function runInput()
	{
		$this->oMgr->clearTempVlanList();

		if ($this->request['complete'] != "")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}

		$this->setEntryData();

		return 1;
	}

	function runReload()
	{
		$this->setInputData();

		return 1;
	}

	function runEdit()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setInputData();
			return 1;
		}

		$ret = $this->oMgr->updateAppData($this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_DIRECT);

	}

}

?>
