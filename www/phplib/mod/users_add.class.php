<?php
/**********************************************************
* File         : users_add.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/users_regist_common.class.php");
require_once("mgr/users_add_mgr.class.php");

class users_add extends users_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "利用者登録";
		$this->header_file = "users_add_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new users_add_mgr();
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

		// サブ属性
		$this->request['sub_belong_chg_id'] = array();
		$this->request['sub_belong_chg_id'][1] = "";
		$this->request['sub_job_id'] = array();
		$this->request['sub_job_id'][1] = "";
		$this->request['sub_post_id'] = array();
		$this->request['sub_post_id'][1] = "";

		$aryTmp = $this->oMgr->getUserTypeAry();
		$this->request['user_type_id'] = key($aryTmp);

		$this->request['his_init'] = "1";
		$this->request['send_date'] = date("Y/m/d");

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

		$ret = $this->oMgr->insertUserData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

}

?>
