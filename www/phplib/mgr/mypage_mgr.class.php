<?php
/**********************************************************
* File         : mypage_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/mypage_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mypage_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getUserMailAcc()
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$sql = $this->getQuery('GET_USER_MAIL_ACC', $user_id);

		$mail_acc = $this->oDb->getOne($sql);

		return $mail_acc;
	}

	function checkCurrentPasswd($passwd)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$args = array();
		$args[0] = $user_id;
		$args[1] = $this->passwordEncrypt($passwd);

		$sql = $this->getQuery("CHECK_CURRENT_PASSWD", $args);

		$user_id = $this->oDb->getOne($sql);

		if ($user_id != "")
		{
			return true;
		}

		return false;
	}

	function existsSendonAddr($sendon_addr)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$args = array();
		$args[0] = $user_id;
		$args[1] = $sendon_addr;

		$sql = $this->getQuery('EXISTS_SENDON_ADDR', $args);

		$no = $this->oDb->getOne($sql);

		if ($no != "")
		{
			return true;
		}

		return false;

	}

	function updateSendonType($sendon_type)
	{
		if ($sendon_type == "")
		{
			$sendon_type = "0";
		}
		return $this->setSendonType($sendon_type);
	}

	function setSendonType($sendon_type="")
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		// レコードがあるかチェック
		$sql = $this->getQuery('EXISTS_USER_SENON_HEAD', $user_id);

		$type = $this->oDb->getOne($sql);

		$type = (string)$type;

		if ($type != "")
		{
			if ($sendon_type == "")
			{
				return true;
			}
			$sql_id = "UPDATE_SENDON_TYPE";
		}
		else
		{
			$sql_id = "INSERT_SENDON_TYPE";
		}

		$args = $this->getSqlArgs();
		$args[0] = $user_id;

		$args['SENDON_TYPE'] = $this->sqlItemFlg($sendon_type);

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			return false;
		}

		$this->relationUserMailAddr('edit');

		return true;
	}

	function insertSendonAddr($sendon_addr)
	{
		$this->oDb->begin();

		// ヘッダがなければ作る
		$ret = $this->setSendonType();

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args['SENDON_ADDR'] = $this->sqlItemChar($sendon_addr);

		$sql = $this->getQuery('INSERT_SENDON_ADDR', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();


		$this->relationUserMailAddr('edit');

		return true;
	}

	function deleteSendonAddr($list_no)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $list_no;

		$sql = $this->getQuery('DELETE_SENDON_ADDR', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			return false;
		}

		$this->relationUserMailAddr('edit');

		return true;
	}

	function updatePasswd($passwd)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$args = $this->getSqlArgs();
		$args[0] = $user_id;

		$login_passwd = $this->passwordEncrypt($passwd);
		$args['LOGIN_PASSWD'] = $this->sqlItemChar($login_passwd);

		$sql = $this->getQuery('UPDATE_PASSWD', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		// メールアカウントを更新
		$this->relationUserMailAddr('edit');

		// ADを更新
		$this->setPasswordAd();

		return true;
	}

	function updatePasswdSalary($passwd)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		// レコードがあるかチェック
		$sql = $this->getQuery('EXISTS_USER_SALARY_PASSWD', $user_id);

		$history_no = $this->oDb->getOne($sql);

		if ($history_no == "")
		{
			// データがなければ、1から開始
			$history_no = 1;
		}
		else
		{
			// 履歴番号を最大値に
			$history_no = (int)$history_no + 1;
		}

		$this->oDb->begin();

		$args = $this->getSqlArgs();
		$args[0] = $user_id;

		$salary_passwd = $this->passwordEncrypt($passwd);
		$args['HISTORY_NO']    = $this->sqlItemInteger($history_no);
		$args['SALARY_PASSWD'] = $this->sqlItemChar($salary_passwd);

		$sql = $this->getQuery("INSERT_USER_SALARY_PASSWD", $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}
		$this->oDb->end();

		return true;
	}

	function getUsersAppList()
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$args = array();
		$args['ENTRY_STATUS_ENTRY'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);
		$args['ENTRY_STATUS_REJECT'] = $this->sqlItemChar(ENTRY_STATUS_REJECT);
		$args['WIRE_KBN_WLESS'] = $this->sqlItemChar(WIRE_KBN_WLESS);
		$args['WIRE_KBN_FREE'] = $this->sqlItemChar(WIRE_KBN_FREE);
		$args[0] = $user_id;

		// 無線だけとってくる
		$sql = $this->getQuery('GET_USERS_APP_LIST', $args);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}

	function getAppVlanList($app_id)
	{
		$sql = $this->getQuery('GET_APP_LIST_DATA', $app_id);

		$aryRet = $this->oDb->getAssoc2Ary($sql);

		return $aryRet;
	}

	function changeWirelessVlan($app_id, $new_vlan_id)
	{
		$aryList = $this->getAppVlanList($app_id);

		if (!is_array($aryList))
		{
			return false;
		}

		foreach ($aryList AS $vlan_id => $busy_flg)
		{
			if ($busy_flg == "1")
			{
				$org_vlan_id = $vlan_id;
				break;
			}
		}

		if ($org_vlan_id == $new_vlan_id)
		{
			return true;
		}

		$this->oDb->begin();

		// 古いほうのフラグをはずす
		$args = $this->getSqlArgs();
		$args[0] = $app_id;

		if ($org_vlan_id != "")
		{
			$args[1] = $org_vlan_id;
			$args[2] = "0";
			$sql = $this->getQuery('UPDATE_VLAN_BUSY_FLG', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				$this->oDb->rollback();
				return false;
			}
		}

		// 新しいほうにフラグをたてる
		$args[1] = $new_vlan_id;
		$args[2] = "1";
		$sql = $this->getQuery('UPDATE_VLAN_BUSY_FLG', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}


		$this->oDb->end();

		// 無線用ID取得
		$aryApp = $this->getUsersAppList();
		$wireless_id = $aryApp[$app_id]['wireless_id'];

		// AD連携
		$ret = $this->changeWirelessVlanAd($wireless_id, $org_vlan_id, $new_vlan_id);

		// エラーを記録
		if (!$ret)
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');

			// 同じものがないかチェック
			$args = array();
			$args[0] = $user_id;
			$args[1] = $wireless_id;
			$sql = $this->getQuery('EXISTS_WIRELESS_AD_ERR', $args);

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
				$args[1] = $user_id;
				$args[2] = $wireless_id;
				$sql = $this->getQuery('INSERT_WIRELESS_AD_ERR', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Trace("エラーログの記録に失敗しました[ID：" . $user_id . "、無線ID：" . $wireless_id . "]", 859);
				}
			}

		}

		return true;
	}

	function changeWirelessVlanAd($wireless_id, $org_vlan_id, $new_vlan_id)
	{
		if (!defined("LDAP_HOST_1"))
		{
			return true;
		}

		//接続開始
		$ldap_conn = ldap_connect(LDAP_HOST_1, LDAP_PORT);
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect(LDAP_HOST_2, LDAP_PORT);
		}

		if (!$ldap_conn)
		{
			Debug_Trace("接続失敗", 1);
			return false;
		}

		// バインド
		$ldap_bind  = ldap_bind($ldap_conn, LDAP_DN, LDAP_PASS);

		if (!$ldap_bind)
		{
			Debug_Trace("バインド失敗", 9);
			return false;
		}


		// 申請者の統合IDのパスワードを取得
		$user_id = $this->getSessionData('LOGIN_USER_ID');
		$aryUser = $this->getUserData($user_id);
		$passwd = $aryUser['login_passwd'];


		$group_info["member"] = "CN=".$wireless_id.",".WLESS_ID_DN;

		// 旧があればグループから抜ける
		if ($org_vlan_id != "")
		{
			// 古いVLANグループから抜ける
			$vlan_name = $this->getVlanName($org_vlan_id);
			$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);

			if (ldap_mod_del($ldap_conn,$joinVl,$group_info))
			{
				Debug_Trace("グループ脱退は成功しました", 125);
			}
			else
			{
				Debug_Trace("グループ脱退は失敗しました", 125);
				return false;
			}
		}

		// 新しいグループに参加
		if ($new_vlan_id != "")
		{
			// 新しいVLANグループに参加
			$vlan_name = $this->getVlanName($new_vlan_id);
			$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);

			if (ldap_mod_add($ldap_conn,$joinVl,$group_info))
			{
				Debug_Trace("グループ参加は成功しました", 126);
			}
			else
			{
				Debug_Trace("グループ参加は失敗しました", 126);
				return false;
			}
		}

		return true;
	}

	function getUserJoukinKbn()
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$sql = $this->getQuery('GET_JOUKIN_KBN', $user_id);

		$joukin_kbn = $this->oDb->getOne($sql);

		return $joukin_kbn;
	}

}

?>
