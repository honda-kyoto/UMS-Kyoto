<?php
/**********************************************************
* File         : guests_detail_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/guests_regist_common_mgr.class.php");
require_once("sql/guests_detail_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class guests_detail_mgr extends guests_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

}
?>
