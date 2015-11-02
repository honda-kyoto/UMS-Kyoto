<?php
/**********************************************************
* File         : vpns_members_regist_common.class.php
* Authors      : sumio imoto
* Date         : 2013.06.19
* Last Update  : 2013.06.19
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");

class vpns_members_regist_common extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

	}

	function postComplete($status)
	{
		$param = array();
		$param['complete'] = "1";
		$param['mode'] = 'input';
		$param['vpn_id'] = $this->request['vpn_id'];
		$param['vpn_user_id'] = $this->request['vpn_user_id'];
		$page = 'vpns_members_edit.php';

		$this->oMgr->postTo($page, $param);
	}

	function setEditData()
	{
		$aryTmp = $this->oMgr->getVpnMembersData($this->request['vpn_id'], $this->request['vpn_user_id']);
		
		$aryTmp['passwd'] = $this->oMgr->passwordDecrypt($aryTmp['passwd']);
		
		$this->setRequestData($aryTmp);

	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		// メールアドレス
		if (!$this->oMgr->checkEmpty($this->request['mail_addr']))
		{
			//
			$this->oMgr->setErr('E001',"メールアドレス");
		}
		else if (!string::checkMailAddr($this->request['mail_addr']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E006', "メールアドレス");
		}
		//else if ($this->oMgr->existsMailAddr($this->request['mail_addr'], $this->request['vpn_id']))
		//{
		//	$this->oMgr->setErr('E017',"メールアドレス");
		//}

		// パスワード
		// 新規登録の場合は、パスワードのチェックは不要
		if (!$this->oMgr->checkEmpty($this->request['passwd']))
		{
			// 編集の場合
			if ($this->request['vpn_user_id'] != "")
			{
				$this->oMgr->setErr('E001',"パスワード");
			}
			
		}
		else
		{
			$passwd = $this->request['passwd'];
			if (!string::checkAlphanumWide($passwd, 6, 20) || !ereg("[0-9]", $passwd) || !ereg("[a-z]", $passwd) || !ereg("[A-Z]", $passwd))
			{
				$param = array();
				$param[0] = "パスワード";
				$param[1] = "数字、英字大文字、英字小文字を各１文字以上使用し、6～20文字";

				// エラーメッセージをセット
				$this->oMgr->setErr('E004', $param);
			}
		}
		
		// 有効期限
		if (!$this->oMgr->checkEmpty($this->request['expiry_date']))
		{
			//
			$this->oMgr->setErr('E001',"有効期限");
		}
		else if (!$this->oMgr->checkDateFormat($this->request['expiry_date']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "有効期限";
			$param[1] = "yyyy/mm/dd";
			$this->oMgr->setErr('E004', $param);
			$has_date_err = true;
		}
		else if (!$this->oMgr->checkDate($this->request['expiry_date']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E013',"有効期限");
			$has_date_err = true;
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
