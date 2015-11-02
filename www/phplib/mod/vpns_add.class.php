<?php
/**********************************************************
* File         : vpns_add.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/vpns_regist_common.class.php");
require_once("mgr/vpns_add_mgr.class.php");

class vpns_add extends vpns_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "VPN登録";
		$this->header_file = "vpns_add_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new vpns_add_mgr();
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

		$this->request['vpn_kbn'] = VPN_KBN_VPN;

		$this->request['admin_id'][1] = $this->oMgr->getSessionData('LOGIN_USER_ID');
		$this->setAdminName();

		return 1;
	}

	function runAdd()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setAdminName();
			return 1;
		}

		$ret = $this->oMgr->insertVpnData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

}

?>
