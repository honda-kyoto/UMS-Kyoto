<?php
/**********************************************************
* File         : vpns_members_detail.class.php
* Authors      : sumio imoto
* Date         : 2013.06.19
* Last Update  : 2013.06.19
* Copyright    :
***********************************************************/

require_once("mod/vpns_members_regist_common.class.php");
require_once("mgr/vpns_members_edit_mgr.class.php");

class vpns_members_edit extends vpns_members_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "VPN参加者詳細";
		$this->header_file = "vpns_members_edit_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new vpns_members_edit_mgr();
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

		if ($this->request['complete'] == "1")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}
		
		return 1;
	}
	
	
	function runEdit()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			return 1;
		}

		$ret = $this->oMgr->updateVpnMembersData($this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_DIRECT);

	}

}

?>
