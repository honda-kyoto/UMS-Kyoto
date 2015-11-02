<?php
/**********************************************************
* File         : init_passwd.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/init_passwd_mgr.class.php");

class init_passwd extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "パスワード初期設定";
		$this->header_file = "init_passwd_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new init_passwd_mgr();
	}


	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		return 1;
	}

	function runEscape()
	{
		$ret = $this->oMgr->escapePasswd();

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		header("Location: menu.php");
		exit;
	}

	function runUpdate()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->mode = "input";
			return 1;
		}

		$ret = $this->oMgr->updatePasswd($this->request['login_passwd']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		header("Location: menu.php");
		exit;
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		if (!$this->oMgr->checkEmpty($this->request['login_passwd']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"新しいパスワード");
		}
		else if (!$this->oMgr->checkEmpty($this->request['login_passwd_conf']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"新しいパスワード（確認用）");
		}
		else if ($this->request['login_passwd'] != $this->request['login_passwd_conf'])
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E501');
		}
		else
		{
			$passwd = $this->request['login_passwd'];
			if (!string::checkAlphanumWide($passwd, 6, 20) || !ereg("[0-9]", $passwd) || !ereg("[a-z]", $passwd) || !ereg("[A-Z]", $passwd))
			{
				$param = array();
				$param[0] = "パスワード";
				$param[1] = "数字、英字大文字、英字小文字を各１文字以上使用し、6～20文字";

				// エラーメッセージをセット
				$this->oMgr->setErr('E004', $param);
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
}

?>
