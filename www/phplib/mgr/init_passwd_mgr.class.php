<?php
/**********************************************************
* File         : init_passwd_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/init_passwd_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class init_passwd_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function escapePasswd()
	{
		return $this->setInitPasswd();
	}

	function updatePasswd($passwd)
	{
		return $this->setInitPasswd($passwd);
	}

	function setInitPasswd($passwd="")
	{
		// メールアカウントがあるか確認
		//$ary = $this->getUserData();
		//$has_mail_acc = false;;
		//if ($ary['mail_acc'] != "")
		//{
		//	$has_mail_acc = true;
		//}

		$args = $this->getSqlArgs();

		$args[0] = $this->getSessionData('LOGIN_USER_ID');
		$args['LOGIN_PASSWD'] = 'login_passwd';
		if ($passwd != "")
		{
			$login_passwd = $this->passwordEncrypt($passwd);
			$args['LOGIN_PASSWD'] = $this->sqlItemChar($login_passwd);
		}

		$sql = $this->getQuery('SET_INIT_PASSWD', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			return false;
		}

		if ($passwd != "")
		{
			// ADを更新
			$this->setPasswordAd();

			// メールアカウントを更新
			//if ($has_mail_acc)
			//{
				$this->relationUserMailAddr('edit');
			//}
		}

		return true;
	}


}

?>
