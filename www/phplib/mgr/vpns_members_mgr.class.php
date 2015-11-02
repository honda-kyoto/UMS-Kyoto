<?php
/**********************************************************
* File         : vpns_members_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/vpns_regist_common_mgr.class.php");
require_once("sql/vpns_members_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vpns_members_mgr extends vpns_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	/*
	 * getList
	*/
	function getList($vpn_id)
	{
		$sql = $this->getQuery('GETLIST', $vpn_id);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}

	function addMember($request)
	{
		$vpn_id = $request['vpn_id'];

		$this->oDb->begin();

		// ヘッダのデータを取得
		$aryHead = $this->getVpnData($vpn_id);

		$vpn_kbn = $aryHead['vpn_kbn'];
		$group_name = $aryHead['group_name'];
		$group_code = $aryHead['group_code'];

		$request['passwd'] = $this->createPassword();
		$passwd = $this->passwordEncrypt($request['passwd']);

		// VPNユーザ連番取得（ロック）
		$sql = $this->getQuery('VPN_USER_ID_LOCK', $vpn_id);
		$this->oDb->query($sql);

		$sql = $this->getQuery('GET_VPN_USER_ID_LOCK', $vpn_id);

		$idno = $this->oDb->getOne($sql);

		$vpn_user_id = $group_code . sprintf("%08d", $idno);
		$request['vpn_user_id'] = $vpn_user_id;


		$args = $this->getSqlArgs();
		$args['VPN_ID'] = $this->sqlItemInteger($vpn_id);
		$args['VPN_USER_ID'] = $this->sqlItemChar($vpn_user_id);
		$args['MAIL_ADDR'] = $this->sqlItemChar($request['mail_addr']);
		$args['PASSWD'] = $this->sqlItemChar($passwd);
		$args['KANJINAME'] = $this->sqlItemChar($request['kanjiname']);
		$args['KANANAME'] = $this->sqlItemChar($request['kananame']);
		$args['COMPANY'] = $this->sqlItemChar(@$request['company']);
		$args['CONTACT'] = $this->sqlItemChar(@$request['contact']);
		$args['NOTE'] = $this->sqlItemChar(@$request['note']);
		$args['GROUP_CODE'] = $this->sqlItemChar($group_code);
		$args['EXPIRY_DATE'] = $this->sqlItemChar($request['expiry_date']);

		$sql = $this->getQuery('INSERT_VPN_MEMBER', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// 同期
		if ($vpn_kbn == VPN_KBN_VPN)
		{
			$this->addVpnMembers($group_name, $request);
		}
		else
		{
			$this->addPasslogicMembers($group_name, $request);
		}

		return true;
	}

	function deleteMember($vpn_id, $vpn_user_id)
	{
		// ヘッダのデータを取得
		$aryHead = $this->getVpnData($vpn_id);

		$vpn_kbn = $aryHead['vpn_kbn'];
		$group_name = $aryHead['group_name'];

		$args = $this->getSqlArgs();
		$args[0] = $vpn_id;
		$args[1] = $vpn_user_id;
		$sql = $this->getQuery('DELETE_VPN_MEMBER', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// 同期
			if ($vpn_kbn == VPN_KBN_VPN)
		{
			$this->delVpnMembers($vpn_user_id);
		}
		else
		{
			$this->delPasslogicMembers($vpn_user_id);
		}

		return true;

	}

	function addVpnMembers($group_name, $request)
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

		// 追加時のパラメータ
		$add["cn"] = $vpn_user_id;
		$add["objectClass"] = "user";
		$add["sAMAccountName"] = $vpn_user_id;
		$add["userPrincipalName"] = $vpn_user_id . AD_LOCAL_DOMAIN;
		$add["sAMAccountName"] = $vpn_user_id;
		$add["mail"] = $request['mail_addr'];
		$add['unicodePwd'] = mb_convert_encoding("\"$passwd\"", "UTF-16LE");
		$add["UserAccountControl"] = "66048";
		$add["accountExpires"] = $this->convToAccountExpirys($expiry_date);

		$userDn = "CN=".$vpn_user_id.",".VPN_DN;

		// 登録
		if (ldap_add($ldap_conn,$userDn,$add))
		{
			Debug_Trace("追加は成功しました", 312);
		}
		else
		{
			Debug_Trace("追加は失敗しました", 312);
			return;
		}

		// グループへの追加
		$group_info["member"] = $userDn;
		$groupDn = str_replace("###GROUP_NAME###", $group_name, VPN_GROUP_DN);

		if (ldap_mod_add($ldap_conn,$groupDn,$group_info))
		{
			Debug_Trace("グループ参加は成功しました", 313);
		}
		else
		{
			Debug_Trace("グループ参加は失敗しました", 313);
			return;
		}

		return;
	}


	function addPasslogicMembers($group_name, $request)
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

		// 追加時のパラメータ
		$add["cn"] = $vpn_user_id;
		$add["objectClass"] = "user";
		$add["sAMAccountName"] = $vpn_user_id;
		$add["userPrincipalName"] = $vpn_user_id . AD_LOCAL_DOMAIN;
		$add["sAMAccountName"] = $vpn_user_id;
		$add["mail"] = $request['mail_addr'];
		$add["physicalDeliveryOfficeName"] = $group_name;
		$add['unicodePwd'] = mb_convert_encoding("\"$passwd\"", "UTF-16LE");
		$add["UserAccountControl"] = "66048";
		$add["accountExpires"] = $this->convToAccountExpirys($expiry_date);

		$userDn = "CN=".$vpn_user_id.",".PASSLOGIC_DN;

		// 登録
		if (ldap_add($ldap_conn,$userDn,$add))
		{
			Debug_Trace("追加は成功しました", 412);
		}
		else
		{
			Debug_Trace("追加は失敗しました", 412);
			return;
		}

		return;
	}

	function delVpnMembers($vpn_user_id)
	{
		if (!defined("LDAP_HOST_1"))
		{
			return;
		}

		//接続開始
		$ldap_conn = ldap_connect(LDAP_HOST_1, LDAP_PORT);
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect(LDAP_HOST_2, LDAP_PORT);
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

		// 削除
		$userDn = "CN=".$vpn_user_id.",".VPN_DN;

		if (ldap_delete($ldap_conn,$userDn))
		{
			Debug_Trace("削除は成功しました", 812);
		}
		else
		{
			Debug_Trace("削除は失敗しました", 812);
			return false;
		}

		return true;
	}


	function delPasslogicMembers($vpn_user_id)
	{
		if (!defined("LDAP_HOST_1"))
		{
			return;
		}

		//接続開始
		$ldap_conn = ldap_connect(LDAP_HOST_1, LDAP_PORT);
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect(LDAP_HOST_2, LDAP_PORT);
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

		// 削除
		$userDn = "CN=".$vpn_user_id.",".PASSLOGIC_DN;

		if (ldap_delete($ldap_conn,$userDn))
		{
			Debug_Trace("削除は成功しました", 912);
		}
		else
		{
			Debug_Trace("削除は失敗しました", 912);
			return false;
		}

		return true;
	}

	function getPrintVpnMembers($vpn_id, $vpn_user_id)
	{
		$args = $this->getSqlArgs();
		$args[0] = $vpn_id;
		$args[1] = $vpn_user_id;

		$sql = $this->getQuery('GET_PRINT_MEMBER', $args);

		$ret = $this->oDb->getRow($sql);

		if ($ret !== false)
		{
			if ($ret['wardstatus'] != "")
			{
				$ret['wardname'] = $GLOBALS['wardstatus'][$ret['wardstatus']] . "　" . $ret['wardname'];
			}
			if ($ret['passwd'] != "")
			{
				$ret['password'] = $this->passwordDecrypt($ret['passwd']);
			}
			if ($ret['password'] != "")
			{
				$ret['password_furigana'] = $this->makePasswordFurigana($ret['password']);
			}
		}

		return $ret;
	}

	function getVpnMembersData($vpn_id, $vpn_user_id)
	{
		$args = array();
		$args[0] = $vpn_id;
		$args[1] = $vpn_user_id;

		$sql = $this->getQuery('GET_VPN_MEMBERS_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}


	function updateVpnMembersExpiry(&$request)
	{
		$vpn_id       = $request['vpn_id'];
		$aryVpnUserId = $request['checked_id'];
		$aryVpnUserIdUpdated = array();

		$this->oDb->begin();

		// ヘッダのデータを取得
		$aryHead = $this->getVpnData($vpn_id);

		$vpn_kbn = $aryHead['vpn_kbn'];

		foreach ($aryVpnUserId AS $index => $vpn_user_id)
		{
			$before = $this->getVpnMembersData($vpn_id, $vpn_user_id);

			if ($before['expiry_date'] == $request['update_expiry_date'])
			{
				// 更新前の期限日と比較し、変更が無ければ更新しない
				continue;
			}

			// 変更があったidを保持し、このリストでAD連携を行う
			$aryVpnUserIdUpdated[] = $vpn_user_id;

			$args = $this->getSqlArgs();

			$args['VPN_ID']      = $this->sqlItemInteger($vpn_id);
			$args['VPN_USER_ID'] = $this->sqlItemChar($vpn_user_id);
			$args['EXPIRY_DATE'] = $this->sqlItemChar($request['update_expiry_date']);

			$sql = $this->getQuery('UPDATE_VPN_MEMBERS_EXPIRY', $args);
			Debug_Trace($sql, 444);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				$this->oDb->rollback();
				return false;
			}
		}

		$this->oDb->end();

		// AD連携
		if ($vpn_kbn == VPN_KBN_VPN)
		{
			$this->modifyExpiryVpnMembers($aryVpnUserIdUpdated, $request['update_expiry_date']);
		}
		else
		{
			$this->modifyExpiryPasslogicMembers($aryVpnUserIdUpdated, $request['update_expiry_date']);
		}

		return true;
	}

	function modifyExpiryVpnMembers($ary_vpn_user_id, $expiry_date)
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

		foreach ($ary_vpn_user_id AS $index => $vpn_user_id)
		{
			// 更新時のパラメータ
			$mod["accountExpires"] = $this->convToAccountExpirys($expiry_date);

			$userDn = "CN=".$vpn_user_id.",".VPN_DN;

			// 更新
			if (ldap_modify($ldap_conn,$userDn,$mod))
			{
				Debug_Trace("更新は成功しました", 315);
			}
			else
			{
				Debug_Trace("更新は失敗しました", 315);
				Debug_Trace($userDn, 315);
				Debug_Trace($mod, 315);
				return;
			}

		}

		ldap_close($ldap_conn);

		return;
	}


	function modifyExpiryPasslogicMembers($ary_vpn_user_id, $expiry_date)
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

		foreach ($ary_vpn_user_id AS $index => $vpn_user_id)
		{

			// 更新時のパラメータ
			$mod["accountExpires"] = $this->convToAccountExpirys($expiry_date);

			$userDn = "CN=".$vpn_user_id.",".PASSLOGIC_DN;

			// 更新
			if (ldap_modify($ldap_conn,$userDn,$mod))
			{
				Debug_Trace("更新は成功しました", 415);
			}
			else
			{
				Debug_Trace("更新は失敗しました", 415);
				Debug_Trace($userDn, 415);
				Debug_Trace($mod, 415);
				return;
			}
		}

		ldap_close($ldap_conn);

		return;
	}

}
?>
