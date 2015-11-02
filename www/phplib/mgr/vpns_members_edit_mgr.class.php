<?php
/**********************************************************
* File         : vpns_members_edit_mgr.class.php
* Authors      : sumio imoto
* Date         : 2013.06.19
* Last Update  : 2013.06.19
* Copyright    :
***********************************************************/
require_once("mgr/vpns_members_regist_common_mgr.class.php");
require_once("sql/vpns_members_edit_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vpns_members_edit_mgr extends vpns_members_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function updateVpnMembersData(&$request)
	{
		$vpn_id      = $request['vpn_id'];
		$vpn_user_id = $request['vpn_user_id'];
		
		// パスワード暗号化
		$passwd = $this->passwordEncrypt($request['passwd']);
		
		// 更新前にDBデータを取得し比較
		$aryTmp = $this->getVpnMembersData($request['vpn_id'], $request['vpn_user_id']);
		
		$aryTmp['passwd'] = $this->passwordDecrypt($aryTmp['passwd']);
				
		$this->oDb->begin();

		$args = $this->getSqlArgs();

		$args['VPN_ID']      = $this->sqlItemInteger($vpn_id);
		$args['VPN_USER_ID'] = $this->sqlItemChar($vpn_user_id);
		$args['MAIL_ADDR']   = $this->sqlItemChar($request['mail_addr']);
		$args['PASSWD']      = $this->sqlItemChar($passwd);
		$args['KANJINAME']   = $this->sqlItemChar($request['kanjiname']);
		$args['KANANAME']    = $this->sqlItemChar($request['kananame']);
		$args['COMPANY']     = $this->sqlItemChar($request['company']);
		$args['CONTACT']     = $this->sqlItemChar($request['contact']);
		$args['EXPIRY_DATE'] = $this->sqlItemChar($request['expiry_date']);
		$args['NOTE']        = $this->sqlItemChar($request['note']);
		
		$sql = $this->getQuery('UPDATE_VPN_MEMBERS_LIST', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// 有効期限・メールアドレス・パスワードの何れかが変更されたらAD連携する
		if ($aryTmp['expiry_date'] != $request['expiry_date']
			|| $aryTmp['mail_addr'] != $request['mail_addr'] 
			|| $aryTmp['passwd'] != $request['passwd'] ) 
		{
			// AD連携
			if ($aryTmp['vpn_kbn'] == VPN_KBN_VPN)
			{
				$this->modifyVpnMembers($request);
			}
			else
			{
				$this->modifyPasslogicMembers($request);
			}
		}
		return true;
	}
	
	function modifyVpnMembers($request)
	{
		if (!defined("LDAP_HOST_1"))
		{
			return;
		}

		//接続開始
		$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
		}

		if (!$ldap_conn)
		{
			Debug_Trace("接続失敗", 9);
			return;
		}

		// バインド
		$ldap_bind  = ldap_bind($ldap_conn, LDAP_DN, LDAP_PASS);

		if (!$ldap_bind)
		{
			Debug_Trace("バインド失敗", 9);
			return;
		}

		$vpn_user_id = $request['vpn_user_id'];
		$passwd = $request['passwd'];
		$expiry_date = $request['expiry_date'];
		
		
		// 更新時のパラメータ
		$mod["mail"] = $request['mail_addr'];
		$mod['unicodePwd'] = mb_convert_encoding("\"$passwd\"", "UTF-16LE");
		$mod["accountExpires"] = $this->convToAccountExpirys($expiry_date);


		$userDn = "CN=".$vpn_user_id.",".VPN_DN;

		// 更新
		if (ldap_modify($ldap_conn,$userDn,$mod))
		{
			Debug_Trace("更新は成功しました", 314);
		}
		else
		{
			Debug_Trace("更新は失敗しました", 314);
			Debug_Trace($userDn, 314);
			Debug_Trace($mod, 314);
			return;
		}

		ldap_close($ldap_conn);
		
		return;
	}


	function modifyPasslogicMembers($request)
	{
		if (!defined("LDAP_HOST_1"))
		{
			return;
		}

		//接続開始
		$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
		}

		if (!$ldap_conn)
		{
			Debug_Trace("接続失敗", 9);
			return;
		}

		// バインド
		$ldap_bind  = ldap_bind($ldap_conn, LDAP_DN, LDAP_PASS);

		if (!$ldap_bind)
		{
			Debug_Trace("バインド失敗", 9);
			return;
		}

		$vpn_user_id = $request['vpn_user_id'];
		$passwd = $request['passwd'];
		$expiry_date = $request['expiry_date'];

		// 更新時のパラメータ
		$mod["mail"] = $request['mail_addr'];
		$mod['unicodePwd'] = mb_convert_encoding("\"$passwd\"", "UTF-16LE");
		$mod["accountExpires"] = $this->convToAccountExpirys($expiry_date);
		
		$userDn = "CN=".$vpn_user_id.",".PASSLOGIC_DN;

		// 更新
		if (ldap_modify($ldap_conn,$userDn,$mod))
		{
			Debug_Trace("更新は成功しました", 414);
		}
		else
		{
			Debug_Trace("更新は失敗しました", 414);
			Debug_Trace($userDn, 414);
			Debug_Trace($mod, 414);
			return;
		}

		ldap_close($ldap_conn);
		
		return;
	}
	
}
?>
