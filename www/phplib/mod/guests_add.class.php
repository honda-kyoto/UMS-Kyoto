<?php
/**********************************************************
* File         : guests_add.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/guests_add_mgr.class.php");

class guests_add extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "ゲスト登録";
		$this->header_file = "guests_add_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new guests_add_mgr();
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

		return 1;
	}

	function runAdd()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			return 1;
		}

		$ret = $this->oMgr->insertGuestData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$param = array();
		$param['complete'] = "1";
		$param['mode'] = 'view';
		$param['guest_id'] = $this->request['guest_id'];
		$page = 'guests_detail.php';

		$this->oMgr->postTo($page, $param);
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		// 名称
		if (!$this->oMgr->checkEmpty($this->request['guest_name']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"ゲスト氏名");
		}

		// 会社名
		if (!$this->oMgr->checkEmpty($this->request['company_name']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"会社名");
		}

		// 所属名
		if (!$this->oMgr->checkEmpty($this->request['belong_name']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"所属名");
		}

		// 電話番号
		if (!$this->oMgr->checkEmpty($this->request['telno']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"電話番号");
		}

		// MACアドレス
		if (!$this->oMgr->checkEmpty($this->request['mac_addr']))
		{
			// エラーメッセージをセット
			//$this->oMgr->setErr('E001',"MACアドレス");
		}
		else if (!string::checkMacAddr($this->request['mac_addr']))
		{
			$this->oMgr->setErr('E006', 'MACアドレス');
		}

		// 用途
		if (!$this->oMgr->checkEmpty($this->request['usage']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"用途");
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
}

?>
