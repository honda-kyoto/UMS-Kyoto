<?php
/**********************************************************
* File         : base_history_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/users_regist_common_mgr.class.php");
require_once("sql/base_history_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class base_history_mgr extends users_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getLastHistoryNo($user_id)
	{
		$sql = $this->getQuery('GET_LAST_HISTORY_NO', $user_id);

		$history_no = $this->oDb->getOne($sql);

		return $history_no;
	}

	function getUserBaseHistoryData($user_id, $history_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		$sql = $this->getQuery('GET_USER_BASE_HISTORY_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		if ($ret['birthday'] != "")
		{
			list($by, $bm, $bd) = explode("-", $ret['birthday']);
			$ret['birth_year'] = $by;
			$ret['birth_mon'] = (int)$bm;
			$ret['birth_day'] = (int)$bd;
		}

		return $ret;
	}

	function getSubBelongHistoryData($user_id, $history_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		$sql = $this->getQuery('GET_SUB_BELONG_HISTORY_DATA', $args);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubJobHistoryData($user_id, $history_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		$sql = $this->getQuery('GET_SUB_JOB_HISTORY_DATA', $args);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubPostHistoryData($user_id, $history_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		$sql = $this->getQuery('GET_SUB_POST_HISTORY_DATA', $args);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubStaffIdHistoryData($user_id, $history_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		$sql = $this->getQuery('GET_SUB_STAFF_ID_HISTORY_DATA', $args);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getBaseHistoryList($user_id)
	{
		$sql = $this->getQuery('GET_BASE_HISTORY_LIST', $user_id);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}


}
?>
