<?php
/**********************************************************
* File         : init_mail_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/init_mail_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class init_mail_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function setInitMailAcc($mail_acc)
	{
		$args = $this->getSqlArgs();

		$args[0] = $this->getSessionData('LOGIN_USER_ID');
		$args['MAIL_ACC'] = $this->sqlItemChar($mail_acc);

		$sql = $this->getQuery('SET_INIT_MAIL_ACC', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			return false;
		}

		$this->relationUserMailAddr('add');

		return true;
	}

}

?>
