<?php
/**********************************************************
* File         : data_export.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/data_export_mgr.class.php");

class data_export extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "データ出力";
		$this->header_file = "data_export_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new data_export_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInit()
	{
		$this->makeTargetData();

		return 1;
	}

	function runOutput()
	{
		$this->oMgr->outputData($this->request['user_role_id']);
		exit;
	}

	function runDownload()
	{
		$this->oMgr->downloadData($this->request['user_role_id'], $this->request['file_name']);
		exit;
	}

	function makeTargetData()
	{
		// ユーザの属性によって表示できるデータを作成
		// あとで作る
		$aryTmp = $this->oMgr->getTargetList();

		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $id => $name)
			{
				$this->output['target_name'][$id] = $name . "用データ";
			}
		}
	}
}

?>
