<?php
/**********************************************************
* File         : user_printer_add.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/user_printer_add_mgr.class.php");

class user_printer_add extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "仮想環境プリンタ割り付け";
		$this->header_file = "user_printer_add_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new user_printer_add_mgr();
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

		return 1;
	}

	/*======================================================
	 * Name         : runComplete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runComplete()
	{
		// 完了メッセージをセット
		$this->oMgr->setErr('C002');
		$this->errMsg = $this->oMgr->getErrMsg();

		$this->setEditData();

		return 1;
	}

	/*======================================================
	 * Name         : runUpdate
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runUpdate()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラー
		if (!$ret)
		{
			return 1;
		}

		// 登録処理
		$ret = $this->oMgr->updateData($this->request);

		// エラー
		if (!$ret)
		{
			$this->oMgr->setErr('E999');
			$this->errMsg = $this->oMgr->getErrMsg();
			return 1;
		}

		// リストへ
		$this->postComplete();
	}


	function setEditData()
	{
		$this->request['device_id'] = $this->oMgr->getUserDeviceList();
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		if ($this->request['device_id'] == "")
		{
			//$this->oMgr->setErr('E007', 'プリンタ');
		}

		// エラーなし
		if (sizeof($this->oMgr->aryErrMsg) == 0)
		{
			return true;
		}

		// エラー発生
		$this->errMsg = $this->oMgr->getErrMsg();
		return false;
	}

	function postComplete()
	{
		$param = array();
		$param['mode'] = "complete";
		$this->oMgr->postTo($_SERVER['SCRIPT_NAME'], $param);
	}

	function getDeviceList()
	{
		return $this->oMgr->makeDeviceList($this->request);
	}

	function getDeviceSelectedList()
	{
		return $this->oMgr->makeDeviceSelectedList($this->request);
	}
}

?>
