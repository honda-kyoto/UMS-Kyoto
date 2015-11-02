<?php
/**********************************************************
* File         : mlists_search.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/mlists_list_common.class.php");
require_once("mgr/mlists_search_mgr.class.php");

class mlists_search extends mlists_list_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "メーリングリスト検索";
		$this->header_file = "mlists_search_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new mlists_search_mgr();
	}

	function runDelEntry()
	{
		$entry_no = "";
		$ret = $this->oMgr->delMlistEntry($this->request['mlist_id'], &$entry_no);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$param['mode'] = 'view';
		$param['mlist_id'] = $this->request['mlist_id'];
		$param['entry_no'] = $entry_no;
		$page = 'mlists_pending.php';

		$this->oMgr->postTo($page, $param);
	}

	/*======================================================
	 * Name         : runDelete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runDelete()
	{
		$ret = $this->oMgr->deleteMlistData(&$this->request['mlist_id']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$param = array();
		$param['mode'] = 'return';
		$param['complete'] = "1";
		$this->oMgr->postTo('mlists_search.php', $param);
	}


}

?>
