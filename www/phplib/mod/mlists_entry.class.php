<?php
/**********************************************************
* File         : mlists_entry.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/mlists_regist_common.class.php");
require_once("mgr/mlists_entry_mgr.class.php");

class mlists_entry extends mlists_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "メーリングリスト申請";
		$this->header_file = "mlists_entry_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new mlists_entry_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		// 初期化
		$this->request = array();

		$this->request['sender_kbn'] = SENDER_KBN_LIMIT;

		$this->request['admin_id'][1] = $this->oMgr->getSessionData('LOGIN_USER_ID');
		$this->setAdminName();

		return 1;
	}

	function runRetry()
	{
		$this->setEditData();

		return 1;
	}

	function runReload()
	{
		$this->setAdminName();

		return 1;
	}

	function runEntry()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setAdminName();
			return 1;
		}

		$ret = $this->oMgr->insertMlistData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_ENTRY);

	}

}

?>
