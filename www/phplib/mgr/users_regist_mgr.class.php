<?php
/**********************************************************
* File         : users_regist_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/users_regist_common_mgr.class.php");
require_once("sql/users_regist_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class users_regist_mgr extends users_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

}
?>
