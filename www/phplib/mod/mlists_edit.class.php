<?php
/**********************************************************
* File         : mlists_edit.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/mlists_regist_common.class.php");
require_once("mgr/mlists_edit_mgr.class.php");

class mlists_edit extends mlists_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "メーリングリスト編集";
		$this->header_file = "mlists_edit_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new mlists_edit_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		$this->setEditData();

		if ($this->request['complete'] != "")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}

		return 1;
	}

	function runUpdate()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setAdminName();
			return 1;
		}

		$ret = $this->oMgr->updateMlistData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_DIRECT);
	}
}

?>
