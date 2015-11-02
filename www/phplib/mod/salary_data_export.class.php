<?php
/**********************************************************
* File         : salary_data_export.class.php
* Authors      : sumio imoto
* Date         : 2013.05.23
* Last Update  : 2013.05.23
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/salary_data_export_mgr.class.php");

class salary_data_export extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "給与明細データ出力";
		$this->header_file = "salary_data_export_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new salary_data_export_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInit()
	{
		$this->makeViewData();

		return 1;
	}

	function runOutput()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			echo "0|".$this->errMsg;
			exit;
		}

		$this->oMgr->outputData($this->request['start_date'], $this->request['end_date']);
		exit;
	}

	function runDownload()
	{
		$this->oMgr->downloadData($this->request);
		exit;
	}

	function makeViewData()
	{
		// 前回出力時の日時を条件に設定
		$this->request['start_date'] = $this->oMgr->getLastOutputData();
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 
	=======================================================*/
	function checkInputdata()
	{
		$has_date_err = false;

		// パスワード変更期間
		if (!$this->oMgr->checkEmpty($this->request['start_date']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"パスワード変更期間(開始日)");
			$has_date_err = true;
		}
		else if (!$this->oMgr->checkDateFormat($this->request['start_date']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "パスワード変更期間(開始日)";
			$param[1] = "yyyy/mm/dd";
			$this->oMgr->setErr('E004', $param);
			$has_date_err = true;
		}
		else if (!$this->oMgr->checkDate($this->request['start_date']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E013',"パスワード変更期間(開始日)");
			$has_date_err = true;
		}

		// パスワード変更期間
		if ($this->oMgr->checkEmpty($this->request['end_date']))
		{
			if (!$this->oMgr->checkDateFormat($this->request['end_date']))
			{
				// エラーメッセージをセット
				$param = array();
				$param[0] = "パスワード変更期間(終了日)";
				$param[1] = "yyyy/mm/dd";
				$this->oMgr->setErr('E004', $param);
				$has_date_err = true;
			}
			else if (!$this->oMgr->checkDate($this->request['end_date']))
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('E013',"パスワード変更期間(終了日)");
				$has_date_err = true;
			}
		}

		if (!$has_date_err && $this->oMgr->checkEmpty($this->request['end_date']))
		{
			if (!$this->oMgr->checkDateTerm($this->request['start_date'], $this->request['end_date']))
			{
				// エラーメッセージをセット
				$param = array();
				$param[0] = "パスワード変更期間(開始日)";
				$param[1] = "パスワード変更期間(終了日)";
				$this->oMgr->setErr('E012',$param);
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
