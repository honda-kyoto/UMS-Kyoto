<?php
/**********************************************************
* File         : mypage.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/mypage_mgr.class.php");

class mypage extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "マイページ";
		$this->header_file = "mypage_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new mypage_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInit()
	{
		// ユーザー基本情報
		$this->setUserBaseData();

		$this->setJoukinKbnData();

		return 1;
	}

	/*======================================================
	 * Name         : runWireless
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runWireless()
	{
		$this->display_mode = $this->mode;

		$this->output['common_wireless_id'] = $this->oMgr->getUserWirelessId();

		$aryTmp = $this->oMgr->getUsersAppList();

		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $app_id => $aryApp)
			{
				$this->output['app_name'][$app_id] = $aryApp['app_name'];
				$this->output['wireless_id'][$app_id] = $aryApp['wireless_id'];

				$aryVlan = $this->oMgr->getAppVlanList($app_id);
				$cnt = count($aryVlan);

				$this->output['wl_vlan_cnt'][$app_id] = $cnt;

				if ($cnt == 1)
				{
					$vlan_id = key($aryVlan);
					$this->output['vlan_area_name'][$app_id] = $this->oMgr->getVlanAreaName($vlan_id);
				}
			}
		}

		$this->setJoukinKbnData();

		return 1;
	}

	function runVlan()
	{
		$app_id = $this->request['app_id'];
		$ret = $this->oMgr->changeWirelessVlan($app_id, $this->request['vlan_id'][$app_id]);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete('wireless');
	}

	/*======================================================
	 * Name         : runOldmail
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runOldmail()
	{
		$this->display_mode = $this->mode;

		$this->output['oldmail_list'] = $this->oMgr->getUserOldmailList();

		return 1;
	}


	/*======================================================
	 * Name         : runSalary
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runSalary()
	{
		$this->setJoukinKbnData();

		$this->setSalaryData();

		return 1;
	}

	function runChange()
	{
		$ret = $this->oMgr->updateSendonType($this->request['sendon_type']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

	function runAdd()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setUserBaseData();
			return 1;
		}

		$ret = $this->oMgr->insertSendonAddr($this->request['sendon_addr']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

	function runDelete()
	{
		$ret = $this->oMgr->deleteSendonAddr($this->request['list_no']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

	function runPasswd()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setUserBaseData();
			$this->setJoukinKbnData();
			return 1;
		}

		$ret = $this->oMgr->updatePasswd($this->request['new_login_passwd']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

	function runPasswdSalary()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setSalaryData();
			$this->setJoukinKbnData();
			return 1;
		}

		$ret = $this->oMgr->updatePasswdSalary($this->request['new_salary_passwd']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete('salary');
	}

	function postComplete($mode="init")
	{
		$params = array();
		$params['mode'] = $mode;
		$params['complete'] = "1";
		$this->oMgr->postTo($_SERVER['SCRIPT_NAME'], $params);
	}

	function setUserBaseData()
	{
		$this->display_mode = 'base';

		$this->output['mail_acc'] = $this->oMgr->getUserMailAcc();

		if ($this->mode != 'change')
		{
			$this->request['sendon_type'] = $this->oMgr->getUserSendonHead();
		}

		$this->output['sendon_list'] = $this->oMgr->getUserSendonList();

		$this->output['oldmail_list'] = $this->oMgr->getUserOldmailList();

		// 完了メッセージ
		if ($this->request['complete'] == "1")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}
	}

	function setSalaryData()
	{
		$this->display_mode = 'salary';

		// 完了メッセージ
		if ($this->request['complete'] == "1")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}
	}

	function setJoukinKbnData()
	{
		$this->output['joukin_kbn'] = $this->oMgr->getUserJoukinKbn();

	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		switch ($this->mode)
		{
			case 'add':
				if (!$this->oMgr->checkEmpty($this->request['sendon_addr']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E001',"転送先アドレス");
				}
				else if (!string::checkMailAddr($this->request['sendon_addr']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E006',"転送先アドレス");
				}
				else if ($this->oMgr->existsSendonAddr($this->request['sendon_addr']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E018',"転送先アドレス");
				}

				break;
			case 'passwd':
				if (!$this->oMgr->checkEmpty($this->request['login_passwd']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E001',"現在のパスワード");
				}
				else if (!$this->oMgr->checkCurrentPasswd($this->request['login_passwd']))
				{
					$this->oMgr->setErr('E006', "現在のパスワード");
				}
				else if (!$this->oMgr->checkEmpty($this->request['new_login_passwd']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E001',"新しいパスワード");
				}
				else if (!$this->oMgr->checkEmpty($this->request['new_login_passwd_conf']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E001',"新しいパスワード（確認用）");
				}
				else if ($this->request['new_login_passwd'] != $this->request['new_login_passwd_conf'])
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E501');
				}
				else
				{
					$passwd = $this->request['new_login_passwd'];
					if (!string::checkAlphanumWide($passwd, 6, 20) || !ereg("[0-9]", $passwd) || !ereg("[a-z]", $passwd) || !ereg("[A-Z]", $passwd))
					{
						$param = array();
						$param[0] = "パスワード";
						$param[1] = "数字、英字大文字、英字小文字を各１文字以上使用し、6～20文字";

						// エラーメッセージをセット
						$this->oMgr->setErr('E004', $param);
					}
				}
				break;
			case 'passwdSalary':
				if (!$this->oMgr->checkEmpty($this->request['new_salary_passwd']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E001',"新しいパスワード");
				}
				else if (!$this->oMgr->checkEmpty($this->request['new_salary_passwd_conf']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E001',"新しいパスワード（確認用）");
				}
				else if ($this->request['new_salary_passwd'] !== $this->request['new_salary_passwd_conf'])
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E501');
				}
				else
				{
					$passwd = $this->request['new_salary_passwd'];
					if (!string::checkAlphanumWide($passwd, 6, 20) || !ereg("[0-9a-zA-Z]", $passwd))
					{
						$param = array();
						$param[0] = "パスワード";
						$param[1] = "数字、英字大文字、英字小文字を使用し、6～20文字";

						// エラーメッセージをセット
						$this->oMgr->setErr('E004', $param);
					}
				}
				break;
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

	function hasOldMailList()
	{
		$ary = $this->oMgr->getUserOldmailList();

		if (is_array($ary) && count($ary) > 0)
		{
			return true;
		}

		return false;
	}

	function getWirelessVlanList($app_id)
	{
		$aryVlan = $this->oMgr->getAppVlanList($app_id);
		$aryVlanName = array();
		if (is_array($aryVlan))
		{
			foreach ($aryVlan AS $vlan_id => $busy_flg)
			{
				if ($busy_flg == "1")
				{
					$selected = $vlan_id;
				}

				$aryVlanName[$vlan_id] = $this->oMgr->getVlanAreaName($vlan_id);
			}
		}

		$ret = $this->oMgr->makeSelectOptions($aryVlanName, $selected);

		return $ret;
	}
}

?>
