<?php
/**********************************************************
* File         : apps_agree.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/apps_regist_common.class.php");
require_once("mgr/apps_agree_mgr.class.php");

class apps_agree extends apps_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "機器承認待ち詳細";
		$this->header_file = "apps_agree_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new apps_agree_mgr();

		$this->is_agree_mode = true;
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runView()
	{
		$this->setEditData();

		return 1;
	}

	function runAgree()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setEditData();
			return 1;
		}

		$ret = $this->oMgr->agreeAppEntry($this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_AGREE);
	}

	function runReject()
	{
		if (!$this->oMgr->checkEmpty($this->request['agree_note']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"却下理由");
			$this->errMsg = $this->oMgr->getErrMsg();
			$this->setEditData();
			return 1;
		}

		$ret = $this->oMgr->rejectAppEntry($this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(COMP_STATUS_AGREE);
	}
	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		$aryTmp = $this->oMgr->getAppHead($this->request['app_id'], @$this->request['entry_no']);

		list ($wire_kbn, $ip_kbn) = $this->oMgr->getAppTypeKbns($aryTmp['app_type_id']);

		if ($ip_kbn == IP_KBN_FREE)
		{
			$ip_kbn = @$aryTmp['ip_kbn'];
		}

		if ($ip_kbn == IP_KBN_FIXD)
		{
			if (!$this->oMgr->checkEmpty($this->request['ip_addr']))
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('E001',"固定IPアドレス");
			}
			else if (!string::checkIpAddr($this->request['ip_addr']))
			{
				$this->oMgr->setErr('E006', '固定IPアドレス');
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
