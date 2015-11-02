<?php
/**********************************************************
* File         : guests_add_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/guests_add_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class guests_add_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function insertGuestData(&$request)
	{
		$request['mac_addr'] = $this->getFlatMacAddr($request['mac_addr']);
		$request['wireless_id'] = microtime(true);
		$request['password'] = $this->createPassword();

		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$password = $this->passwordEncrypt($request['password']);

		$this->oDb->begin();

		// ユーザID取得
		$guest_id = $this->oDb->getSequence('guest_id_seq');

		// 基本情報
		$args = $this->getSqlArgs();
		$args['GUEST_ID'] = $this->sqlItemInteger($guest_id);
		$args['GUEST_NAME'] = $this->sqlItemChar($request['guest_name']);
		$args['COMPANY_NAME'] = $this->sqlItemChar($request['company_name']);
		$args['BELONG_NAME'] = $this->sqlItemChar($request['belong_name']);
		$args['TELNO'] = $this->sqlItemChar($request['telno']);
		$args['MAC_ADDR'] = $this->sqlItemChar($request['mac_addr']);
		$args['WIRELESS_ID'] = $this->sqlItemChar($request['wireless_id']);
		$args['PASSWORD'] = $this->sqlItemChar($password);
		$args['USAGE'] = $this->sqlItemChar($request['usage']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);

		$sql = $this->getQuery('INSERT_GUEST_HEAD', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// エントリーテーブルを更新
		$ret = $this->updateUserEntryFlg($user_id, 'guest_entry_flg');

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// トランザクション終了
		$this->oDb->end();

		// AD連携
		$this->relationAd($request);

		$request['guest_id'] = $guest_id;
		return true;
	}

	function relationAd($request)
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

		$wireless_id = $request['wireless_id'];
		$passwd = $request['password'];

		// 追加時のパラメータ
		$add["cn"] = $wireless_id;
		$add["objectClass"] = "user";
		$add["sAMAccountName"] = $wireless_id;
		$add["userPrincipalName"] = $wireless_id . AD_LOCAL_DOMAIN;
		$add["sAMAccountName"] = $wireless_id;
		$add['unicodePwd'] = mb_convert_encoding("\"$passwd\"", "UTF-16LE");
		//$add["UserAccountControl"] = "512";
		$add["UserAccountControl"] = "66048";

		$userDn = "CN=".$wireless_id.",".WLESS_ID_DN;

		// 登録
		if (ldap_add($ldap_conn,$userDn,$add))
		{
			Debug_Trace("追加は成功しました", 912);
		}
		else
		{
			Debug_Trace("追加は失敗しました", 912);
			return;
		}

		// グループへの追加
		$group_info["member"] = $userDn;

		if (ldap_mod_add($ldap_conn,GUEST_DN,$group_info))
		{
			Debug_Trace("グループ参加は成功しました", 913);
		}
		else
		{
			Debug_Trace("グループ参加は失敗しました", 913);
			return;
		}

		return;
	}

}

?>
