<?php
/**********************************************************
* File         : printer_driver_add.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/printer_driver_add_mgr.class.php");

class printer_driver_add extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "プリンタドライバ名割り当て";
		$this->header_file = "printer_driver_add_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new printer_driver_add_mgr();
	}

	/*======================================================
	 * Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		$this->request['unallocated_only'] = "1";
		return 1;
	}

	/*======================================================
	 * Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runSearch()
	{
		return 1;
	}

	/*======================================================
	 * Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runSelect()
	{
		$this->request['driver_name'] = $this->oMgr->getPrinterDriverName($this->request['app_id']);

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

		$this->request['unallocated_only'] = "1";
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


	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		if ($this->request['app_id'] == "")
		{
			$this->oMgr->setErr('E007', 'プリンタ');
		}

		if ($this->request['driver_name'] == "")
		{
			//$this->oMgr->setErr('E007', 'ドライバ名');
			if ($this->request['app_id'] != "")
			{
				if ($this->oMgr->checkDeviceUsed($this->request['app_id']))
				{
					$this->request['driver_name'] = $this->oMgr->getPrinterDriverName($this->request['app_id']);
					$this->oMgr->setErr('D001');
				}
			}
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

	function getPrinterList()
	{
		return $this->oMgr->makePrinterList($this->request);
	}

	function getDriverList()
	{
		return $this->oMgr->makeDriverList(@$this->request['driver_name']);
	}
}

?>
