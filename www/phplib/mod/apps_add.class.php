<?php
/**********************************************************
* File         : apps_add.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/apps_regist_common.class.php");
require_once("mgr/apps_add_mgr.class.php");

class apps_add extends apps_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "機器登録";
		$this->header_file = "apps_add_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new apps_add_mgr();
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

	function runReload()
	{
		$this->setInputData();

		return 1;
	}

	function runAdd()
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
		$this->postComplete(COMP_STATUS_DIRECT);

	}

}

?>
