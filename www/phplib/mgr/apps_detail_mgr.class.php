<?php
/**********************************************************
* File         : apps_detail_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/apps_regist_common_mgr.class.php");
require_once("sql/apps_detail_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_detail_mgr extends apps_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function changeSbcFlg($request)
	{
		$args = $this->getSqlArgs();
		$args['APP_ID'] = $this->sqlItemInteger($request['app_id']);
		$args['USE_SBC'] = $this->sqlItemFlg(@$request['use_sbc']);

		$sql = $this->getQuery('UPDATE_APP_USE_SBC', $args);

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
