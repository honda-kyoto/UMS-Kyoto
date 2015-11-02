<?php
/**********************************************************
* File         : apps_regist_common_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_regist_common_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getAppHead($app_id, $entry_no="")
	{
		$args = array();
		$args[0] = $app_id;
		$args['TYPE'] = "tbl";
		$args['COL'] = ", AH.app_user_id";
		$args['COND'] = "";
		if ($entry_no != "")
		{
			$args['TYPE'] = "entry";
			$args['COL'] = <<< SQL
, AH.entry_kbn
, AH.entry_status
, TO_CHAR(AH.entry_time, 'YYYY/MM/DD HH24:MI:SS') AS entry_time
, AH.entry_id
, AH.entry_id AS app_user_id
, TO_CHAR(AH.agree_time, 'YYYY/MM/DD HH24:MI:SS') AS agree_time
, AH.agree_id
, AH.agree_note
SQL;
			$args['COND'] = " AND AH.entry_no = " . string::replaceSql($entry_no);
		}
		else
		{
			$args['COL'] = ",AH.app_user_id";
		}

		$sql = $this->getQuery('GET_APP_HEAD_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function getAgreedEntryData($app_id)
	{
		// 最後に承認された申請データを取ってくる
		$args = array();
		$args['ENTRY_STATUS_AGREE'] = $this->sqlItemChar(ENTRY_STATUS_AGREE);
		$args[0] = $app_id;
		$sql = $this->getQuery('GET_LAST_AGREE_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function getAppList($app_id, $entry_no="")
	{
		$args = array();
		$args[0] = $app_id;
		$args['TYPE'] = "tbl";
		$args['COND'] = "";
		$args['AGREE_FLG'] = "'1'::varchar AS agree_flg";
		if ($entry_no != "")
		{
			$args['TYPE'] = "entry";
			$args['COND'] = " AND entry_no = " . string::replaceSql($entry_no);
			$args['AGREE_FLG'] = "agree_flg";
		}

		$sql = $this->getQuery('GET_APP_LIST_DATA', $args);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function existsMacAddr($mac_addr, $app_id="")
	{
		$mac_addr = $this->getFlatMacAddr($mac_addr);

		$args = array();
		$args[0] = $mac_addr;
		$args['COND'] = "";
		if ($app_id != "")
		{
			$args['COND'] = " AND app_id != " . string::replaceSql($app_id);
		}

		// 実データでチェック
		$args['TYPE'] = "tbl";
		$sql = $this->getQuery('EXISTS_MAC_ADDR', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		// 申請データでチェック
		$args['TYPE'] = "entry";
		$args['COND'] = " AND entry_status = '0'";

		$sql = $this->getQuery('EXISTS_MAC_ADDR', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}
		return false;
	}

	function isUnknownUsersApp($mac_addr, $vlan_id, &$aryOrg)
	{
		$mac_addr = $this->getFlatMacAddr($mac_addr);

		$args = array();
		$args[0] = $mac_addr;
		$args['ENTRY_STATUS_ENTRY'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);
		$args['ENTRY_STATUS_REJECT'] = $this->sqlItemChar(ENTRY_STATUS_REJECT);

		// 該当の機器の情報を取得
		$sql = $this->getQuery('GET_USER_ID_BY_MAC', $args);

		$aryApp = $this->oDb->getRow($sql);

		// VLANが違う場合処理しない
		if ($vlan_id != $aryApp['vlan_id'])
		{
			return false;
		}

		$user_id = $aryApp['app_user_id'];

		// 「不明ユーザ」かチェック
		if ($user_id != APP_UNKNOWN_USER_ID)
		{
			return false;
		}

		$aryOrg = $aryApp;

		return true;
	}

	function joinAppList($vlan_id, $user_id="")
	{
		$busy_flg = '0';
		if (!isset($_SESSION['WL_VLAN_LIST']))
		{
			$busy_flg = '1';
		}

		$aryTmp = array();
		$aryTmp['vlan_area_name'] = $this->getVlanAreaName($vlan_id);
		$aryTmp['agree_flg'] = "0";
		$aryTmp['busy_flg'] = $busy_flg;

		$_SESSION['WL_VLAN_LIST'][$vlan_id] = $aryTmp;
	}

	function setTempVlanList($vlan_id, $aryData)
	{
		$_SESSION['WL_VLAN_LIST'][$vlan_id] = $aryData;
	}

	function defectAppList($vlan_id)
	{
		unset($_SESSION['WL_VLAN_LIST'][$vlan_id]);
	}

	function getTempVlanList()
	{
		return @$_SESSION['WL_VLAN_LIST'];
	}

	function clearTempVlanList()
	{
		if (isset($_SESSION['WL_VLAN_LIST']))
		{
			unset($_SESSION['WL_VLAN_LIST']);
		}
	}

	function makeWirelessId($user_id)
	{
		$aryUser = $this->getUserData($user_id);
		$login_id = $aryUser['login_id'];

		$wireless_id = $this->createWirelessId($login_id);

		$sql = $this->getQuery('EXISTS_WIRELESS_ID', $user_id);

		$ext_id = $this->oDb->getOne($sql);

		if ($ext_id != "")
		{
			$sql_id = 'UPDATE_WIRELESS_ID';
		}
		else
		{
			$sql_id = 'INSERT_WIRELESS_ID';
		}

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $wireless_id;

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}


	function createWirelessId($login_id, &$cnt=0)
	{
		list ($eijisei, $eijimei, $buff) = explode(".", $login_id);

		$pwelemstr = "abcdefghijkmnpqrstuvwxyz123456789";
		$pwelem = preg_split("//", $pwelemstr, 0, PREG_SPLIT_NO_EMPTY);
		$one = $pwelem[array_rand($pwelem, 1)];

		$id = $eijisei . "." . $eijimei . ".w" . $one;

		$cnt++;
		// 存在チェック
		if ($this->checkExistsWirelessId($id))
		{
			if ($cnt > 30)
			{
				return false;
			}
			$id = self::createWirelessId($login_id, &$cnt);
		}

		return $id;
	}

	function checkExistsWirelessId($wireless_id)
	{
		// 共用無線IDから存在チェック
		$sql = $this->getQuery('EXISTS_USER_WIRELESS_ID', $wireless_id);

		$user = $this->oDb->getOne($sql);

		if ($user != "")
		{
			return true;
		}

		// 無線IDをチェック
		$sql = $this->getQuery('EXISTS_APP_WIRELESS_ID', $wireless_id);

		$user = $this->oDb->getOne($sql);

		if ($user != "")
		{
			return true;
		}

		return false;
	}

	function makeAppAdErrLog($app_id)
	{
		// 同じ機器のものがないかチェック
		$sql = $this->getQuery('EXISTS_APP_AD_ERR', $app_id);

		$log_cd = $this->oDb->getOne($sql);

		if ($log_cd == "")
		{
			// なければ記録する

			// ログコード作成　ランダム数字2文字＋マイクロタイムの数字部分のみ
			$rand = rand(10, 99);
			$time = microtime(true);

			list($mae, $ushiro) = explode(".", $time);

			$log_cd = $rand . $mae . str_pad($ushiro, 4, "0");

			$args = array();
			$args[0] = $log_cd;
			$args[1] = $app_id;
			$sql = $this->getQuery('INSERT_APP_AD_ERR', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Trace("エラーログの記録に失敗しました[ID：" . $app_id . "]", 859);
			}
		}
	}


	function relationAd($wire_kbn, $aryOrg, $aryEnt, $aryOrgList=array(), $aryEntList=array(), $make_wireless_id=false)
	{
		if (!defined("LDAP_HOST_1"))
		{
			return true;
		}

		if ($wire_kbn == WIRE_KBN_WIRED)
		{
			$joinDn = WIRED_DN;
			$joinGr = MAC_WIRED_DN;
		}
		else
		{
			$joinDn = WLESS_DN;
			$joinGr = MAC_WLESS_DN;

			// 旧使用VLAN
			$org_wl_vlan_id = "";
			if (is_array($aryOrgList))
			{
				foreach ($aryOrgList AS $vlan_id => $data)
				{
					if ($data['busy_flg'] == '1')
					{
						$org_wl_vlan_id = $vlan_id;
						break;
					}
				}
			}

			// 新使用VLAN
			$new_wl_vlan_id = "";
			if (is_array($aryEntList))
			{
				foreach ($aryEntList AS $vlan_id => $data)
				{
					if ($data['busy_flg'] == '1')
					{
						$new_wl_vlan_id = $vlan_id;
						break;
					}
				}
			}
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
			return false;
		}

		// バインド
		$ldap_bind  = ldap_bind($ldap_conn, LDAP_DN, LDAP_PASS);

		if (!$ldap_bind)
		{
			Debug_Trace("バインド失敗", 9);
			return false;
		}

		$old_mac = @$aryOrg['mac_addr'];
		$mac_addr = @$aryEnt['mac_addr'];

		// 有線で変更されていない場合のみ存在チェック
		if ($old_mac == $mac_addr && $mac_addr != "" && $wire_kbn == WIRE_KBN_WIRED)
		{
			// 存在チェック
			$target = "CN=".$mac_addr;
			$filter = array("cn", "sAMAccountName");

			$result = ldap_search($ldap_conn, MAC_WIRED_DN, $target, $filter);
			$data = ldap_get_entries($ldap_conn, $result);

			// ない場合新規の状態にセット
			if ($data['count'] != 1)
			{
				$old_mac = "";
			}
		}

		// 変わっている場合
		if ($old_mac != $mac_addr)
		{
			// 変更時前のデータを削除
			if ($old_mac != "")
			{
				$ret = $this->delUserFromAd(&$ldap_conn, $old_mac, $joinDn);

				if (!$ret)
				{
					return false;
				}
			}

			// 変更後のデータを登録
			if ($mac_addr != "")
			{
				$aryGroupDn = array();
				$aryGroupDn[] = $joinGr;

				// VLANグループ追加
				if ($wire_kbn == WIRE_KBN_WIRED)
				{
					$vlan_name = $this->getVlanName($aryEnt['vlan_id']);
					$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);
					$aryGroupDn[] = $joinVl;
				}
				else
				{
					// 無線の場合は無線用接続IDをVLANグループに参加させる
				}

				$ret = $this->addUserToAd(&$ldap_conn, $mac_addr, $mac_addr, $joinDn, $aryGroupDn);

				if (!$ret)
				{
					Debug_Trace($aryEnt, 114);
					return false;
				}
			}
		}
		else
		{
			$is_vlan_changed = false;
			// 変わっていない場合
			// 有線の場合でVLANが変わった場合
			if ($wire_kbn == WIRE_KBN_WIRED)
			{
				if ($aryOrg['vlan_id'] != $aryEnt['vlan_id'])
				{
					$old_vlan_id = $aryOrg['vlan_id'];
					$new_vlan_id = $aryEnt['vlan_id'];
					$is_vlan_changed = true;
				}
			}
			else
			{
				// 無線の場合はユーザが統合IDなので別で処理する
			}

			if ($is_vlan_changed)
			{
				$group_info["member"] = "CN=".$mac_addr.",".$joinDn;

				// 古いVLANグループから抜ける
				$vlan_name = $this->getVlanName($old_vlan_id);
				$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);

				if (ldap_mod_del($ldap_conn,$joinVl,$group_info))
				{
					Debug_Trace("グループ脱退は成功しました", 123);
					Debug_Trace($aryEnt, 123);
				}
				else
				{
					Debug_Trace("グループ脱退は失敗しました", 123);
					Debug_Trace($aryEnt, 123);
					return false;
				}

				// 新しいVLANグループに参加
				$vlan_name = $this->getVlanName($new_vlan_id);
				$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);

				if (ldap_mod_add($ldap_conn,$joinVl,$group_info))
				{
					Debug_Trace("グループ参加は成功しました", 124);
					Debug_Trace($aryEnt, 124);
				}
				else
				{
					Debug_Trace("グループ参加は失敗しました", 124);
					Debug_Trace($aryEnt, 124);
					return false;
				}
			}
		}

		if ($wire_kbn == WIRE_KBN_WIRED)
		{
			// 有線の場合ここで終了
			return true;
		}

		$aryGroupDn = array();

		// 申請者の統合IDのパスワードを取得
		$entry_id = $aryEnt['app_user_id'];
		if ($entry_id == "")
		{
			$entry_id = $aryEnt['entry_id'];
		}
		$aryUser = $this->getUserData($entry_id);
		$passwd = $aryUser['login_passwd'];

		$old_wl_id = @$aryOrg['wireless_id'];
		$new_wl_id = $aryEnt['wireless_id'];

		// 新規の場合
		if ($new_wl_id != "")
		{
			// 登録
			$vlan_name = $this->getVlanName($new_wl_vlan_id);
			$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);
			$aryGroupDn[] = $joinVl;

			$ret = $this->addUserToAd(&$ldap_conn, $new_wl_id, $passwd, WLESS_ID_DN, $aryGroupDn);
		}
		else if ($org_wl_vlan_id != $new_wl_vlan_id)
		{
			// VLANが変わった
			$group_info["member"] = "CN=".$new_wl_id.",".WLESS_ID_DN;

			// 旧があればグループから抜ける
			if ($org_wl_vlan_id != "")
			{
				// 古いVLANグループから抜ける
				$vlan_name = $this->getVlanName($org_wl_vlan_id);
				$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);

				if (ldap_mod_del($ldap_conn,$joinVl,$group_info))
				{
					Debug_Trace("グループ脱退は成功しました", 125);
					Debug_Trace($aryEnt, 125);
				}
				else
				{
					Debug_Trace("グループ脱退は失敗しました", 125);
					Debug_Trace($aryEnt, 125);

					return false;
				}
			}

			// 新しいグループに参加
			if ($new_wl_vlan_id != "")
			{
				// 新しいVLANグループに参加
				$vlan_name = $this->getVlanName($new_wl_vlan_id);
				$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);

				if (ldap_mod_add($ldap_conn,$joinVl,$group_info))
				{
					Debug_Trace("グループ参加は成功しました", 126);
					Debug_Trace($aryEnt, 126);
				}
				else
				{
					Debug_Trace("グループ参加は失敗しました", 126);
					Debug_Trace($aryEnt, 126);

					return false;
				}
			}
		}

		// 共用無線VLAN用IDが登録された場合
		if ($make_wireless_id)
		{
			$wireless_id = $this->getUserWirelessId($entry_id);

			$aryGroupDn = array();
			$aryGroupDn[] = VLAN300_DN;

			$ret = $this->addUserToAd(&$ldap_conn, $wireless_id, $passwd, WLESS_ID_DN, $aryGroupDn);

			if (!$ret)
			{
				return false;
			}
		}

		if ($old_wl_id != "")
		{
			$this->delUserFromAd(&$ldap_conn, $old_wl_id, WLESS_ID_DN);

			$vlan_name = $this->getVlanName($new_wl_vlan_id);
			$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);
			$aryGroupDn = array();
			$aryGroupDn[] = $joinVl;

			$ret = $this->addUserToAd(&$ldap_conn, $old_wl_id, $passwd, WLESS_ID_DN, $aryGroupDn);

			$wireless_id = $this->getUserWirelessId($entry_id);

			$this->delUserFromAd(&$ldap_conn, $wireless_id, WLESS_ID_DN);

			$aryGroupDn = array();
			$aryGroupDn[] = VLAN300_DN;

			$ret = $this->addUserToAd(&$ldap_conn, $wireless_id, $passwd, WLESS_ID_DN, $aryGroupDn);

			if (!$ret)
			{
				return false;
			}
		}

		ldap_close($ldap_conn);

		return true;
	}

	function delUserFromAd(&$ldap_conn, $user_name, $userDn)
	{
		// 存在チェック
		$target = "CN=".$user_name;
		$filter = array("cn", "sAMAccountName");

		$result = ldap_search($ldap_conn, $userDn, $target, $filter);
		$data = ldap_get_entries($ldap_conn, $result);

		// ない場合
		if ($data['count'] != 1)
		{
			return true;
		}

		// 削除
		$deleteDn = "CN=".$user_name.",".$userDn;

		if (ldap_delete($ldap_conn,$deleteDn))
		{
			Debug_Trace("削除は成功しました", 512);
			Debug_Trace($deleteDn, 512);
		}
		else
		{
			Debug_Trace("削除は失敗しました", 512);
			Debug_Trace($deleteDn, 512);
			return false;
		}

		return true;
	}

	function addUserToAd(&$ldap_conn, $user_name, $passwd, $userDn, $aryGroupDn)
	{
		// 存在チェック
		$target = "CN=".$user_name;
		$filter = array("cn", "sAMAccountName");

		$result = ldap_search($ldap_conn, $userDn, $target, $filter);
		$data = ldap_get_entries($ldap_conn, $result);

		// あれば一旦削除
		if ($data['count'] == 1)
		{
			// 削除
			$deleteDn = "CN=".$user_name.",".$userDn;

			if (ldap_delete($ldap_conn,$deleteDn))
			{
				Debug_Trace("削除は成功しました", 512);
				Debug_Trace($deleteDn, 512);
			}
			else
			{
				Debug_Trace("削除は失敗しました", 512);
				Debug_Trace($deleteDn, 512);
				return false;
			}
		}


		// 追加時のパラメータ
		$add["cn"] = $user_name;
		$add["objectClass"] = "user";
		$add["sAMAccountName"] = $user_name;
		$add["userPrincipalName"] = $user_name;
		$add['unicodePwd'] = mb_convert_encoding("\"$passwd\"", "UTF-16LE");
		$add["UserAccountControl"] = "66048";

		$userDn = "CN=".$user_name.",".$userDn;

		// 登録
		if (ldap_add($ldap_conn,$userDn,$add))
		{
			Debug_Trace("追加は成功しました", 112);
			Debug_Trace($userDn, 112);
			$add['unicodePwd'] = "******";
			Debug_Trace($add, 112);
		}
		else
		{
			Debug_Trace("追加は失敗しました", 112);
			Debug_Trace($userDn, 112);
			Debug_Trace($add, 112);
			return false;
		}

		// グループへの追加
		if (is_array($aryGroupDn))
		{
			foreach ($aryGroupDn AS $groupDn)
			{
				$group_info["member"] = $userDn;

				if (ldap_mod_add($ldap_conn,$groupDn,$group_info))
				{
					Debug_Trace("グループ参加は成功しました", 113);
					Debug_Trace($groupDn, 113);
					Debug_Trace($group_info, 113);
				}
				else
				{
					Debug_Trace("グループ参加は失敗しました", 113);
					Debug_Trace($groupDn, 113);
					Debug_Trace($group_info, 113);
					return false;
				}

			}
		}

		return true;
	}

	function checkVdiApp($app_type_id)
	{
		$sql = $this->getQuery('CHECK_VDI_APP', $app_type_id);

		$cnt = $this->oDb->getOne($sql);

		if ($cnt > 0)
		{
			return true;
		}

		return false;
	}
}

?>
