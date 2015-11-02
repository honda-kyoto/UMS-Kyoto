<?php
/**********************************************************
* File         : init_mail.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/init_mail_mgr.class.php");

class init_mail extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "メールアカウント設定";
		$this->header_file = "init_mail_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new init_mail_mgr();
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

		$ret = $this->oMgr->setInitMailAcc($this->request['mail_acc']);

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
		$user_id = $this->oMgr->getSessionData['LOGIN_USER_ID'];
		$mailMsg = "";

		if (!$this->oMgr->checkEmpty($this->request['mail_acc']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"メールアカウント");
		}
		else if (!$this->oMgr->checkNcvcMailAcc($this->request['mail_acc'], "メールアカウント", &$mailMsg))
		{
			// エラーメッセージをセット
			$this->oMgr->pushError($mailMsg);
		}
		else if ($this->oMgr->existsMailAcc($this->request['mail_acc'], $user_id))
		{
			$this->oMgr->setErr('E017',"メールアカウント");
		}
		else if ($this->oMgr->existsMlistAcc($this->request['mail_acc']))
		{
			// メーリングリストとして使用中
			$this->oMgr->setErr('E017',"メールアカウント");
		}
		else if ($this->oMgr->existsOldMail($this->request['mail_acc']))
		{
			// エイリアスのアカウントとして使用中
			$this->oMgr->setErr('E017',"メールアカウント");
		}
		else if ($this->oMgr->existsLoginId($this->request['mail_acc']))
		{
			$aryUser = $this->oMgr->getUserData($user_id);
			if ($aryUser['login_id'] == $this->request['mail_acc'])
			{
				$this->oMgr->setErr('E001', '統合ID以外のメールアカウント');
			}else{
				// 統合IDとして使用中
				$this->oMgr->setErr('E017',"メールアカウント");
			}
		}
		else
		{
			$aryUser = $this->oMgr->getUserData($user_id);
			if ($aryUser['login_id'] == $this->request['mail_acc'])
			{
				$this->oMgr->setErr('E001', '統合ID以外のメールアカウント');
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
