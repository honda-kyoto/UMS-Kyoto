<?php
/**********************************************************
* File         : apps_search.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/apps_list_common.class.php");
require_once("mgr/apps_search_mgr.class.php");

class apps_search extends apps_list_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "機器検索";
		$this->header_file = "apps_search_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new apps_search_mgr();
	}

	function runDelEntry()
	{
		$entry_no = "";
		$ret = $this->oMgr->delAppEntry($this->request['app_id'], &$entry_no);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$param['mode'] = 'view';
		$param['app_id'] = $this->request['app_id'];
		$param['entry_no'] = $entry_no;
		$page = 'apps_pending.php';

		$this->oMgr->postTo($page, $param);
	}

	function runDelete()
	{
		$ret = $this->oMgr->deleleAppData($this->request['app_id']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$param['mode'] = 'return';
		$param['app_id'] = $this->request['app_id'];
		$page = 'apps_search.php';

		$this->oMgr->postTo($page, $param);

	}
}

?>
