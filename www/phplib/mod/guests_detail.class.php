<?php
/**********************************************************
* File         : guests_detail.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/guests_detail_mgr.class.php");

class guests_detail extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "ゲスト詳細";
		$this->header_file = "guests_detail_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new guests_detail_mgr();
	}

	/*======================================================
	 * Name         : runDelete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runDelete()
	{
		$ret = $this->oMgr->deleteGuestData($this->request['guest_id']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$param = array();
		$param['mode'] = 'return';
		$param['complete'] = "1";
		$this->oMgr->postTo('guests_search.php', $param);
	}


	/*======================================================
	 * Name         : runInit
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runView()
	{
		$this->setDetailData();

		if ($this->request['complete'] != "")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}

		return 1;
	}

	function setDetailData()
	{
		$aryTmp = $this->oMgr->getGuestData($this->request['guest_id']);

		if ($aryTmp['mac_addr'] != "")
		{
			$mac_addr = substr($aryTmp['mac_addr'], 0, 2);
			$mac_addr .= ":";
			$mac_addr .= substr($aryTmp['mac_addr'], 2, 2);
			$mac_addr .= ":";
			$mac_addr .= substr($aryTmp['mac_addr'], 4, 2);
			$mac_addr .= ":";
			$mac_addr .= substr($aryTmp['mac_addr'], 6, 2);
			$mac_addr .= ":";
			$mac_addr .= substr($aryTmp['mac_addr'], 8, 2);
			$mac_addr .= ":";
			$mac_addr .= substr($aryTmp['mac_addr'], 10, 2);
			$aryTmp['mac_addr'] = $mac_addr;
		}

		$this->setOutputData($aryTmp);


		$this->output['entry_name'] = $this->oMgr->getUserName($this->output['make_id']);
	}


	function runViewGuestPasswd()
	{
		$aryTmp = $this->oMgr->getGuestData($this->request['guest_id']);
		$password = htmlspecialchars($this->oMgr->passwordDecrypt($aryTmp['password']));

		echo $password;
	}

}

?>
