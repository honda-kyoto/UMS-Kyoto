<?php
/**********************************************************
* File         : apps_pending_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/apps_regist_common_mgr.class.php");
require_once("sql/apps_pending_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_pending_mgr extends apps_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function cancelAppEntry($app_id, $entry_no)
	{
		$args = $this->getSqlArgs();
		$args[0] = $app_id;
		$args[1] = $entry_no;
		$args['ENTRY_STATUS'] = $this->sqlItemChar(ENTRY_STATUS_CANCEL);

		$sql = $this->getQuery('CANCEL_APP_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}
}
?>
