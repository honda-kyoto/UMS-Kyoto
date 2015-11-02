<?php
/**********************************************************
* File         : mlists_agree.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/mlists_regist_common.class.php");
require_once("mgr/mlists_agree_mgr.class.php");

class mlists_agree extends mlists_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "メーリングリスト承認待ち詳細";
		$this->header_file = "mlists_agree_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new mlists_agree_mgr();

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
		$this->setEntryData();

		return 1;
	}

	function runAgree()
	{
		// 入力値チェック
		//$ret = $this->checkInputdata();

		// エラーあり
		//if (!$ret)
		//{
		//	$this->setEntryData();
		//	return 1;
		//}

		$ret = $this->oMgr->agreeMlistEntry($this->request);

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

		$ret = $this->oMgr->rejectMlistEntry($this->request);

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
