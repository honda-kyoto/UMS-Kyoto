<?php
/**********************************************************
* File         : vpns_edit.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/vpns_regist_common.class.php");
require_once("mgr/vpns_edit_mgr.class.php");

class vpns_edit extends vpns_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "VPN編集";
		$this->header_file = "vpns_edit_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new vpns_edit_mgr();
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
			return 1;
		}

		$ret = $this->oMgr->updateVpnData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}
}

?>
