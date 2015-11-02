<?php
/**********************************************************
* File         : menu.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/menu_mgr.class.php");

class menu extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "メインメニュー";
		$this->header_file = "menu_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new menu_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runView()
	{
		$chk = $this->oMgr->chkShiftPage();

		// 移動先をチェック
		if ($chk['init_passwd_flg'] == '0')
		{
			header("Location: init_passwd.php");
			exit;
		}
		else if ($chk['mail_acc'] == "" && $chk['mail_disused_flg'] == "0")
		{
			header("Location: init_mail.php");
			exit;
		}
		else if ($chk['mail_reissue_flg'] == "1")
		{
			header("Location: mail_reissue.php");
			exit;
		}

		return 1;
	}

	function getMenuList()
	{
		return $this->oMgr->makeMenuList();
	}

}

?>
