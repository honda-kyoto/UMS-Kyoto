<?php
/**********************************************************
* File         : ncvc_history_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/users_regist_common_mgr.class.php");
require_once("sql/ncvc_history_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：ncvc
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class ncvc_history_mgr extends users_regist_common_mgr
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


	function getUserNcvcHistoryData($user_id, $history_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		$sql = $this->getQuery('GET_USER_NCVC_HISTORY_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		if ($ret['login_passwd'] != "")
		{
			$ret['login_passwd'] = $this->passwordDecrypt($ret['login_passwd']);
		}

		return $ret;

	}


	function getRoleHistoryData($user_id, $history_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		$sql = $this->getQuery('GET_ROLE_HISTORY_DATA', $args);

		$tmp = $this->oDb->getCol($sql);

		$ret = array();
		if (is_array($tmp))
		{
			foreach ($tmp AS $val)
			{
				if ($val < 10)
				{
					$ret['user_type_id'] = $val;
				}
				else
				{
					$ret['user_role_id'][$val] = '1';
				}
			}
		}

		return $ret;
	}

	function getNcvcHistoryList($user_id)
	{
		$sql = $this->getQuery('GET_NCVC_HISTORY_LIST', $user_id);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}





}
?>
