<?php
/**********************************************************
* File         : index_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/index_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class index_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function checkADLoginAuth(&$request)
	{
		$user_id = $request['login_id'];
		$login_passwd = $request['login_passwd'];

        //接続開始
        $ldap_conn = ldap_connect(LDAP_AD_HOST_1, LDAP_AD_PORT);
        if (!$ldap_conn)
        {
                $ldap_conn = ldap_connect("ldaps://".LDAP_AD_HOST_2);
        }

        if (!$ldap_conn)
        {
                Debug_Trace("接続失敗");
                return false;
        }
        if($ldap_conn)
        {
                ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
                $ldap_bind = ldap_bind($ldap_conn, "CN=".$user_id . "," . LDAP_AD_DN, $login_passwd);
                if($ldap_bind)
                {
                        Debug_Trace("ADの認証に成功しました", 3);
                        ldap_close($ldap_conn);
                        //return true;
                }
                else
                {
                        Debug_Trace("ADの認証に失敗しました", 3);
                        Debug_Trace($user_id, 3);
                        ldap_close($ldap_conn);
                        return false;
                }
        }
		//接続終了
        ldap_close($ldap_conn);


		$args = $this->getSqlArgs();

		$args[0] = $request['login_id'];

		$sql = $this->getQuery("LOGINADAUTH", $args);

		$user_id = $this->oDb->getOne($sql);

		if ($user_id != "")
		{
			$request['user_id'] = $user_id;
			return true;
		}

		return false;
	}

	function checkLoginAuth(&$request)
	{
		$args = $this->getSqlArgs();

		$args[0] = $request['login_id'];
		$args[1] = $this->passwordEncrypt($request['login_passwd']);
//		$args[1] = md5($request['passwd']);

		$sql = $this->getQuery("LOGINAUTH", $args);

		$user_id = $this->oDb->getOne($sql);

		if ($user_id != "")
		{
			$request['user_id'] = $user_id;
			return true;
		}

		return false;
	}

	function setLoginData($user_id, &$chk)
	{
		$sql = $this->getQuery("LOGINUSER", $user_id);

		$ary = $this->oDb->getRow($sql);

		// セッション開始
		if (!isset($_SESSION))
		{
			$this->cacheControl();
			session_start();
		}

		$_SESSION['LOGINCHK'] = true;
		$_SESSION['LOGIN_USER_ID'] = $user_id;
		$_SESSION['LOGIN_LOGIN_ID'] = $ary['login_id'];
		$_SESSION['LOGIN_STAFFCODE'] = $ary['staffcode'];
		$_SESSION['LOGIN_USER_NAME']  = $ary['kanjisei'] . "　" . $ary['kanjimei'];
		if ($ary['belong_dep_name'] != "")
		{
			$_SESSION['LOGIN_BELONG_NAME']  = $ary['belong_dep_name'];
		}
		else if ($ary['belong_sec_name'] != "")
		{
			$_SESSION['LOGIN_BELONG_NAME']  = $ary['belong_sec_name'];
		}
		else if ($ary['belong_chg_name'] != "")
		{
			$_SESSION['LOGIN_BELONG_NAME']  = $ary['belong_chg_name'];
		}
		else if ($ary['belong_div_name'] != "")
		{
			$_SESSION['LOGIN_BELONG_NAME']  = $ary['belong_div_name'];
		}
		else if ($ary['belong_class_name'] != "")
		{
			$_SESSION['LOGIN_BELONG_NAME']  = $ary['belong_class_name'];
		}
		$_SESSION['LAST_LOGIN_TIME']  = $ary['last_logintime'];
		$_SESSION['LOGIN_TIME'] = date("Y/m/d H:i:s");

		// 履歴を残す
		$sql = $this->getQuery('UPDATE_LAST_LOGINTIME', $user_id);
		$this->oDb->query($sql);

		return true;
	}
}
?>
