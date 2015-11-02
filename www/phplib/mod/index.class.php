<?php
/**********************************************************
* File         : index.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/index_mgr.class.php");

class index extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "ログイン";
		$this->header_file = "index_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new index_mgr();
	}


	/*======================================================
	* Name         : run
	* IN           :
	* OUT          : 画面番号
	* Discription  : メインの関数
	=======================================================*/
	function run()
	{
		$myMode = ucfirst($this->mode);
		return $this->{"run$myMode"}();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		// すでにログイン済みの場合
		if ($this->oMgr->loginCheck())
		{
			header("Location: menu.php");
			exit;
		}

		return 1;
	}

	/*======================================================
	* Name         : runLogin
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  : ログイン処理
	=======================================================*/
	function runLogin()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->mode = "input";
			return 1;
		}

		// ログイン処理
		$chk = array();
		$this->oMgr->setLoginData($this->request['user_id'], &$chk);

		header("Location: menu.php");
		exit;
	}

	/*======================================================
	* Name         : runLogout
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  : ログアウト処理
	=======================================================*/
	function runLogout()
	{
		// ログアウト処理
		$this->oMgr->logout();
	}

	/*======================================================
	* Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		// 入力された値をチェックする
		// ID未入力
		if (!$this->oMgr->checkEmpty($this->request['login_id']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"ログインID");
		}

		if (!$this->oMgr->checkEmpty($this->request['login_passwd']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"パスワード");
		}

		// AD認証(KING_ID)
		if (!$this->oMgr->checkADLoginAuth(&$this->request))
		{
			// ID/パスワード認証チェック
			if (!$this->oMgr->checkLoginAuth(&$this->request))
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('L001');
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
