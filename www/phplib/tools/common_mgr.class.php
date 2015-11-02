<?php
/**********************************************************
* File         : common_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2011.07.26
* Last Update  : 2011.07.26
* Copyright    :
***********************************************************/
require_once("lib/base.class.php");
require_once("Crypt/Blowfish.php");

define('CBF_KEY' , 'This is ncvc password' ) ;


//----------------------------------------------------------
//	クラス名	：common_mgr
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class common_mgr extends base
{
	function __construct()
	{
		parent::__construct();

		// DBオブジェクト初期化
		$this->oDb = new pgsql();
	}

	function getBelongName(&$ary)
	{
		if (@$ary['belong_chg_id'] != "")
		{
			$sql_id = 'GET_BELONGS_BY_CHG';
			$args = $ary['belong_chg_id'];
		}
		else if (@$ary['belong_sec_id'] != "")
		{
			$sql_id = 'GET_BELONGS_BY_SEC';
			$args = $ary['belong_sec_id'];
		}
		else if (@$ary['belong_dep_id'] != "")
		{
			$sql_id = 'GET_BELONG_BY_DEP';
			$args = $ary['belong_dep_id'];
		}
		else if (@$ary['belong_div_id'] != "")
		{
			$sql_id = 'GET_BELONGS_BY_DIV';
			$args = $ary['belong_div_id'];
		}
		else if (@$ary['belong_class_id'] != "")
		{
			$sql_id = 'GET_BELONGS_BY_CLASS';
			$args = $ary['belong_class_id'];
		}
		else
		{
			return;
		}

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->getRow($sql);

		$name = @$ret['belong_class_name'];
		$ary['belong_class_id'] = $ret['belong_class_id'];

		if (@$ret['belong_div_name'] != "")
		{
			$name .= "　" . $ret['belong_div_name'];
			$ary['belong_div_id'] = $ret['belong_div_id'];
		}
		if (@$ret['belong_dep_name'] != "")
		{
			$name .= "　" . $ret['belong_dep_name'];
			$ary['belong_dep_id'] = $ret['belong_dep_id'];
		}
		if (@$ret['belong_sec_name'] != "")
		{
			$name .= "　" . $ret['belong_sec_name'];
			$ary['belong_sec_id'] = $ret['belong_sec_id'];
		}
		if (@$ret['belong_chg_name'] != "")
		{
			$name .= "　" . $ret['belong_chg_name'];
			$ary['belong_chg_id'] = $ret['belong_chg_id'];
		}

		return $name;
	}

	function getFlatMacAddr($mac_addr)
	{
		if ($mac_addr == "")
		{
			return "";
		}

		ereg("^([0-9A-Za-z]{2})[-|:]?([0-9A-Za-z]{2})[-|:]?([0-9A-Za-z]{2})[-|:]?([0-9A-Za-z]{2})[-|:]?([0-9A-Za-z]{2})[-|:]?([0-9A-Za-z]{2})$", $mac_addr, $aryMac);

		array_shift($aryMac);

		$mac_addr = implode("", $aryMac);

		$mac_addr = strtolower($mac_addr);

		return $mac_addr;
	}

	function getMlistAcc($mlist_id)
	{
		$sql = $this->getQuery('GET_MLIST_ACC', $mlist_id);

		$ret = $this->oDb->getOne($sql);

		return $ret;
	}

	function updateUserEntryFlg($user_id, $col, $flg="1")
	{
		$args = $this->getSqlArgs();
		$args['COL'] = $col;
		$args[0] = $user_id;
		$args[1] = $flg;

		$sql = $this->getQuery('UPDATE_USER_ENTRY_FLG', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function setUserAdminRole($user_id, $role_id)
	{
		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $role_id;

		// 存在チェック
		$sql = $this->getQuery('EXISTS_USER_ROLE', $args);

		$del_flg = $this->oDb->getOne($sql);

		$del_flg = (string)$del_flg;
		if ($del_flg == "0")
		{
			return true;
		}

		if ($del_flg == "1")
		{
			$sql_id = 'UPDATE_USER_ROLE';
		}
		else
		{
			$sql_id = 'INSERT_USER_ROLE';
		}

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function setPasswordAd($user_id="", $is_sec=null)
	{
		if (!defined("LDAP_HOST_1"))
		{
			return;
		}

		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}

		$aryData = $this->getUserData($user_id);

		$login_id = $aryData['login_id'];
		$login_passwd = $aryData['login_passwd'];

		//
		// パスワード設定のためにSSLで接続
		//

		if (is_null($is_sec))
		{
			$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
			if (!$ldap_conn)
			{
				$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
			}
		}
		else
		{
			// 上で接続したサーバへ接続
			if (!$is_sec)
			{
				$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
			}
			else
			{
				$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
			}
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

		////
		//// パスワードの更新の際は、エンコードが必要
		////
		//$add["unicodePwd"] = base64_encode(mb_convert_encoding($login_passwd,"UTF-16"));
		//$add["unicodePwd"] = '{md5}' . base64_encode(pack('H*', md5($login_passwd)));
		$add['unicodePwd'] = mb_convert_encoding("\"$login_passwd\"", "UTF-16LE");
		$users_dn = "CN=".$login_id.",".LOGINID_DN;
		//// 更新
		if (ldap_modify($ldap_conn,$users_dn,$add))
		{
			Debug_Trace("パスワード更新は成功しました", 3);
		}
		else
		{
			Debug_Trace("パスワード更新は失敗しました", 3);
			Debug_Trace($users_dn, 3);
			return;
		}


		// 無線IDの登録があるか確認
		$sql = $this->getQuery('GET_WIRELESS_ID_LIST', $user_id);

		$aryWid = $this->oDb->getCol($sql);

		if (is_array($aryWid))
		{
			foreach ($aryWid AS $wireless_id)
			{
				$users_dn = "CN=".$wireless_id.",".WLESS_ID_DN;

				//// 更新
				if (ldap_modify($ldap_conn,$users_dn,$add))
				{
					Debug_Trace("パスワード更新は成功しました", 3);
				}
				else
				{
					Debug_Trace("パスワード更新は失敗しました", 3);
					Debug_Trace($users_dn, 3);
					return;
				}


			}
		}

		ldap_close($ldap_conn);
	}

	function createMailClient(&$client)
	{
		if (!defined ("MAIL_ACCOUNT_WSDL"))
		{
			return;
		}

		$options = array(
				'login'    => MAIL_LOGIN_USER,
				'password' => MAIL_LOGIN_PWD,
//				'proxy_host' => 'pyxis.jun.ncvc.go.jp',
//				'proxy_port' => 8080,
		);

		$client = new SoapClient(MAIL_ACCOUNT_WSDL, $options);

	}

	function createMlistClient(&$client)
	{
		if (!defined ("MAILING_LIST_WSDL"))
		{
			return;
		}

		$options = array(
				'login'    => MAIL_LOGIN_USER,
				'password' => MAIL_LOGIN_PWD,
//				'proxy_host' => 'pyxis.jun.ncvc.go.jp',
//				'proxy_port' => 8080,
		);

		$client = new SoapClient(MAILING_LIST_WSDL, $options);
	}



	function relationUserMailAddr($type="", $user_id="")
	{
		$client = null;
		$this->createMailClient(&$client);

		if (is_null($client))
		{
			return;
		}

		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}

		$aryData = $this->getUserData($user_id);
		$sendon_type = $this->getUserSendonHead($user_id);
		$sendonList = $this->getUserSendonList($user_id);
		$oldmailList = $this->getUserOldmailList($user_id, 'all');

		// メインのアカウントがない場合で統合ID＠のアドレスが存在する場合はこれをメインとする
		$main_mail_addr = "";
		$temp_mail_addr = $aryData["login_id"].USER_MAIL_DOMAIN;
		if ($aryData['mail_acc'] != "")
		{
			$main_mail_addr = $aryData["mail_acc"].USER_MAIL_DOMAIN;
		}
		else if (in_array($temp_mail_addr, $oldmailList))
		{
			$main_mail_addr = $temp_mail_addr;
			$oldMailKey = array_search($temp_mail_addr, $oldmailList);
			unset($oldmailList[$oldMailKey]);
		}

		if ($main_mail_addr == "")
		{
			return;
		}

		$has_sendon_list = false;
		if (is_array($sendonList))
		{
			foreach ($sendonList AS $forward)
			{
				$aryData['forward'][] = $forward;
				$has_sendon_list = true;
			}
		}
		if (is_array($oldmailList))
		{
			foreach ($oldmailList AS $oldmail)
			{
				$aryData['alias'][] = $oldmail;
			}
		}

		if (!$has_sendon_list)
		{
			// 転送なし
			$mailStatus = 'noForward';
		}
		else if ($sendon_type == "1")
		{
			// 残す
			$mailStatus = 'both';
		}
		else
		{
			// 残さない
			$mailStatus = 'noLocal';
		}

		$params = array(
				'uid'           => $aryData['login_id'],
				'userPassword'  => $aryData['login_passwd'],
				'ncvcName'      => $aryData['kanjisei'] . "　" . $aryData['kanjimei'],
				'mail'          => $main_mail_addr,
				'mailAlias'     => $aryData['alias'],
				'mailForward'   => @$aryData['forward'],
				'mailStatus'    => $mailStatus,
		);

		$dparams = array(
				'uid'           => $aryData['login_id'],
		);

		if ($type=="")
		{

		}

		switch ($type)
		{
			case 'add':
				$res = $client->userAddLdap( $params );
				$msg = "追加";
				break;
			case 'edit':
				$res = $client->userEditLdap( $params );
				$msg = "変更";
				break;
			case 'del':
				$res = $client->userDeleteLdap( $dparams );
				$msg = "削除";
				break;
		}

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メールアカウント".$msg."は成功しました", 301);
		}
		else if ( $res->resultCode == 200 )
		{
			Debug_Trace("メールアカウント".$msg."は失敗しました(クライアント側エラー)[".$res->message."]", 301);
			Debug_Trace($aryData, 301);
			Debug_Trace($params, 301);
		}
		else
		{
			Debug_Trace("メールアカウント".$msg."は失敗しました(サーバー側エラー)[".$res->message."]", 301);
		}

		return;
	}

	function delUserMailAddr($login_id)
	{
		$client = null;
		$this->createMailClient(&$client);

		if (is_null($client))
		{
			return;
		}

		$params = array(
				'uid' => $login_id,
		);

		$res = $client->userDeleteLdap( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メールアカウント削除は成功しました", 501);
		}
		else if ( $res->resultCode == 200 )
		{
			Debug_Trace("メールアカウント削除は失敗しました(クライアント側エラー)", 501);
		}
		else
		{
			Debug_Trace("メールアカウント削除は失敗しました(サーバー側エラー)", 501);
		}

		return;
	}

	function relationAutoMembers($mlist_id)
	{
		$cmd = PHP_CMD . ' ' . BACKEND_PATH . 'mlists_auto_members.php ' . escapeshellarg($mlist_id) . ' > /dev/null &';

		exec($cmd);
	}

	function existsMailAcc($mail_acc, $user_id="")
	{
		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}
		$args = array();
		$args[0] = $mail_acc;
		$args['COND'] = "";
		if ($user_id != "")
		{
			$args['COND'] = " AND user_id != " . string::replaceSql($user_id);
		}

		$sql = $this->getQuery('EXISTS_MAIL_ACC', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		return false;

	}

	function getUserName($user_id="")
	{
		$ary = $this->getUserData($user_id);

		$name = $ary['kanjisei'] . "　" . $ary['kanjimei'];

		unset ($ary);

		return $name;
	}

	function getUserData($user_id="")
	{
		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}

		$sql = $this->getQuery('GET_USER_DATA', $user_id);

		$ret = $this->oDb->getRow($sql);

		if ($ret['birthday'] != "")
		{
			list($by, $bm, $bd) = explode("-", $ret['birthday']);
			$ret['birth_year'] = $by;
			$ret['birth_mon'] = (int)$bm;
			$ret['birth_day'] = (int)$bd;
		}

		if ($ret['login_passwd'] != "")
		{
			$ret['login_passwd'] = $this->passwordDecrypt($ret['login_passwd']);
		}

		if ($ret['staffcode'] != "")
		{
			$ret['his_flg'] = '1';
			$ret['has_his_data'] = '1';
		}

		if ($ret['password'] != "")
		{
			$ret['password'] = $this->passwordDecrypt($ret['password']);
		}

		return $ret;
	}

	function getUserSendonHead($user_id="")
	{
		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}

		$sql = $this->getQuery('GET_USER_SENON_HEAD', $user_id);

		$sendon_type = $this->oDb->getOne($sql);

		return $sendon_type;
	}

	function getUserSendonList($user_id="")
	{
		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}

		$sql = $this->getQuery('GET_USER_SENON_LIST', $user_id);

		$aryList = $this->oDb->getAssoc2Ary($sql);

		return $aryList;
	}

	function getUserOldmailList($user_id="", $type="")
	{
		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}

		$args = array();
		$args[0] = $user_id;
		$args['COND'] = "";
		if ($type == "")
		{
			$args['COND'] = " AND hidden_flg = '0'";
		}

		$sql = $this->getQuery('GET_USER_OLDMAIL_LIST', $args);

		$aryList = $this->oDb->getAssoc2Ary($sql);

		return $aryList;
	}

	function getUserWirelessId($user_id="")
	{
		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}

		$sql = $this->getQuery('GET_USER_WIRELESS_ID', $user_id);

		$ret = $this->oDb->getOne($sql);

		return $ret;
	}

	function toWareki($y, $m, $d, &$gengou, &$wadate)
	{
		//年月日を文字列として結合
		$ymd = sprintf("%02d%02d%02d", $y, $m, $d);
		if ($ymd <= "19120729")
		{
			$gengou = "M";
			$yy = $y - 1867;
		} elseif ($ymd >= "19120730" && $ymd <= "19261224") {
			$gengou = "T";
			$yy = $y - 1911;
		} elseif ($ymd >= "19261225" && $ymd <= "19890107") {
			$gengou = "S";
			$yy = $y - 1925;
		} elseif ($ymd >= "19890108") {
			$gengou = "H";
			$yy = $y - 1988;
		}

		$wadate = sprintf("%02d%02d%02d", $yy, $m, $d);

		return;
	}

	function createPassword($length = 8)
	{

		$pwd_strings = array(
				"sletter" => range('a', 'z'),
				"cletter" => range('A', 'Z'),
				"number"  => range('0', '9'),
		);

		$pwd = array();

		while (count($pwd) < $length)
		{
			// 3種類必ず入れる
			if (count($pwd) < 3)
			{
				$key = key($pwd_strings);
				next($pwd_strings);
			}
			else
			{
				// 後はランダムに取得
				$key = array_rand($pwd_strings);
			}
			$pwd[] = $pwd_strings[$key][array_rand($pwd_strings[$key])];
		}

		// 生成したパスワードの順番をランダムに並び替え
		shuffle($pwd);

		return implode($pwd);
	}

	function passwordEncrypt($passwd)
	{
		$blowfish = new Crypt_Blowfish(CBF_KEY);
		$encrypted = $blowfish->encrypt($passwd);
		$encrypt_char = base64_encode( $encrypted );

		return $encrypt_char;
	}

	function passwordDecrypt($encrypt_char)
	{
		$encrypted = base64_decode( $encrypt_char );
		$blowfish = new Crypt_Blowfish(CBF_KEY);
		$passwd = $blowfish->decrypt( $encrypted );

		// 末尾の「\0」を削除
		$passwd = rtrim($passwd, "\0");

		return $passwd;
	}

	function getUserTypeId($user_id="")
	{
		if ($user_id == "")
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
		}

		$sql = $this->getQuery('GET_USER_TYPE_ID', $user_id);

		$id = $this->oDb->getOne($sql);

		return $id;
	}

	function isNormalUser()
	{
		$type_id = $this->getUserTypeId();

		if ($type_id == USER_TYPE_NORMAL)
		{
			return true;
		}

		return false;
	}

	function isAdminUser()
	{
		$type_id = $this->getUserTypeId();

		if ($type_id == USER_TYPE_SYSADM)
		{
			return true;
		}

		return false;
	}

	function getAppTypeKbns($app_type_id)
	{
		$sql = $this->getQuery('GET_APP_TYPE_KBNS', $app_type_id);

		$ret = $this->oDb->getRow($sql);

		return array($ret['wire_kbn'], $ret['ip_kbn']);
	}

	function getVlanName($vlan_id)
	{
		$sql = $this->getQuery('GET_VLAN_NAME', $vlan_id);

		$name = $this->oDb->getOne($sql);

		return $name;
	}

	function getVlanAdminIds($vlan_id)
	{
		$sql = $this->getQuery('GET_VLAN_ADMIN_ID', $vlan_id);

		$ret = $this->oDb->getCol($sql);

		return $ret;
	}

	function isVlanAdmin($vlan_id)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$ret = $this->getVlanAdminIds($vlan_id);

		if (in_array($user_id, $ret))
		{
			return true;
		}

		return false;
	}

	function getVlanAreaName($vlan_id, &$aryId=array())
	{
		$sql = $this->getQuery('GET_VLAN_AREA_NAME', $vlan_id);

		$ret = $this->oDb->getRow($sql);

		$name = $ret['vlan_ridge_name'] . "　" . $ret['vlan_floor_name'] . "　" . $ret['vlan_room_name'] . "　" . $ret['admin_name'];
		$name .= "（VLAN" . $ret['vlan_name'] . "）";

		$aryId['vlan_ridge_id'] = $ret['vlan_ridge_id'];
		$aryId['vlan_floor_id'] = $ret['vlan_floor_id'];
		$aryId['vlan_room_id'] = $ret['vlan_room_id'];

		return $name;
	}

	function getVlanRoomName($vlan_room_id, &$aryId=array())
	{
		$sql = $this->getQuery('GET_VLAN_ROOM_NAME', $vlan_room_id);

		$ret = $this->oDb->getRow($sql);

		$name = $ret['vlan_ridge_name'] . "　" . $ret['vlan_floor_name'] . "　" . $ret['vlan_room_name'];

		$aryId['vlan_ridge_id'] = $ret['vlan_ridge_id'];
		$aryId['vlan_floor_id'] = $ret['vlan_floor_id'];

		return $name;
	}

	function getVlanFloorName($vlan_floor_id, &$vlan_ridge_id="")
	{
		$sql = $this->getQuery('GET_VLAN_FLOOR_NAME', $vlan_floor_id);

		$ret = $this->oDb->getRow($sql);

		$name = $ret['vlan_ridge_name'] . "　" . $ret['vlan_floor_name'];

		$vlan_ridge_id = $ret['vlan_ridge_id'];

		return $name;
	}

	//-----------------------------------------------
	// getMsg
	//
	// 処理概要：エラーメッセージを返す
	//-----------------------------------------------
	function getMessage($id, $args="")
	{
		$msg = $this->Messages[$id];

		if (is_array($args))
		{
			foreach ($args AS $key => $val)
			{
				$repStr = "{" . $key . "}";
				$msg = str_replace($repStr, $val, $msg);
			}
		}
		else if ($args != "")
		{
			$msg = str_replace("{0}", $args, $msg);
		}

		return $msg;
	}

	//--------------------------------------------
	// 関数名		: makeSelectList()
	// 機能			:
	//--------------------------------------------
	function makeSelectList($name, $selected, $args="")
	{
		$ret = "";

		$ary = $this->getFormPartsAry($name, $args);
		$ret = $this->makeSelectOptions($ary, $selected);
		return $ret;
	}

	//--------------------------------------------
	// 関数名		: makeSelectList()
	// 機能			:
	//--------------------------------------------
	function makeCheckBoxList($name, $checked, $limit="", $onClick="", $args="")
	{
		$ret = "";

		$ary = $this->getFormPartsAry($name, $args);

		$ret = $this->makeCheckBoxTable($name, $ary, $checked, $limit, $onClick);

		return $ret;
	}

	function makeRadioButtonList($name, $checked, $limit="", $onClick="")
	{
		$ret = "";

		$ary = $this->getFormPartsAry($name);

		$ret = $this->makeRadioButtonTable($name, $ary, $checked, $limit, $onClick);

		return $ret;
	}

	function makeErrorMessage($msg)
	{
		if ($msg=="")
		{
			return "";
		}
		return $this->getTpl('M001', $msg);
	}

	function makeErrorMessageNoBr($msg)
	{
		if ($msg=="")
		{
			return "";
		}
		return $this->getTpl('M002', $msg);
	}

	function makeErrorMessageAbsolute($msg)
	{
		if ($msg=="")
		{
			return "";
		}
		return $this->getTpl('M003', $msg);
	}

	function makeErrorMessageNoBrAbsolute($msg)
	{
		if ($msg=="")
		{
			return "";
		}
		return $this->getTpl('M004', $msg);
	}

	/*
	 * getFormPartsAry
	 */
	function getFormPartsAry($name, $args="")
	{
		switch ($name)
		{
			case 'birth_year':
				$ary = $this->getYearAryAge();
				break;
			case 'birth_mon':
				$ary = $this->getMonthAry();
				break;
			case 'birth_day':
				$ary = $this->getDayAry();
				break;
			case 'belong_class_id':
			case 'sub_belong_class_id':
				$ary = $this->getBelongClassAry();
				break;
			case 'belong_div_id':
			case 'sub_belong_div_id':
				$ary = $this->getBelongDivAry($args);
				break;
			case 'belong_dep_id':
			case 'sub_belong_dep_id':
				$ary = $this->getBelongDepAry($args);
				break;
			case 'belong_sec_id':
			case 'sub_belong_sec_id':
				$ary = $this->getBelongSecAry($args);
				break;
			case 'belong_chg_id':
			case 'sub_belong_chg_id':
				$ary = $this->getBelongChgAry($args);
				break;
			case 'job_id':
			case 'sub_job_id':
				$ary = $this->getJobAry();
				break;
			case 'post_id':
			case 'sub_post_id':
				$ary = $this->getPostAry();
				break;
			case 'sub_wardstatus':
				$ary = $this->getAry('wardstatus');
				break;
			case 'sub_professionstatus':
				$ary = $this->getAry('professionstatus');
				break;
			case 'sub_deptstatus':
				$ary = $this->getAry('deptstatus');
				break;
			case 'wardcode':
			case 'sub_wardcode':
				$ary = $this->getWardAry($args);
				break;
			case 'professioncode':
			case 'sub_professioncode':
				$ary = $this->getProfessionAry($args);
				break;
			case 'gradecode':
			case 'sub_gradecode':
				$ary = $this->getGradeAry();
				break;
			case 'deptcode':
			case 'sub_deptcode':
				$ary = $this->getDeptAry($args);
				break;
			case 'deptgroupcode':
			case 'sub_deptgroupcode':
				$ary = $this->getDeptgroupAry($args);
				break;
			case 'user_type_id':
				$ary = $this->getUserTypeAry();
				break;
			case 'user_role_id':
				$ary = $this->getUserRoleAry();
				break;
			case 'vlan_ridge_id':
			case 'wl_vlan_ridge_id':
				$ary = $this->getVlanRidgeAry();
				break;
			case 'vlan_floor_id':
			case 'wl_vlan_floor_id':
				$ary = $this->getVlanFloorAry($args);
				break;
			case 'vlan_room_id':
			case 'wl_vlan_room_id':
				$ary = $this->getVlanRoomAry($args);
				break;
			case 'vlan_id':
			case 'wl_vlan_id':
				$ary = $this->getVlanAry($args);
				break;
			case 'app_type_id':
			case 'tmp_app_type_id':
				$ary = $this->getAppTypeAry();
				break;
			default :
				$ary = $this->getAry($name);
				break;
		}

		return $ary;
	}

	/* マスタ配列取得 */

	function getVlanRidgeAry()
	{
		$sql = $this->getQuery('GET_VLAN_RIDGE_ARY');

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getVlanFloorAry($vlan_ridge_id)
	{
		$ret = array();
		if ($vlan_ridge_id == "")
		{
			return $ret;
		}

		$sql = $this->getQuery('GET_VLAN_FLOOR_ARY', $vlan_ridge_id);

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getVlanRoomAry($vlan_floor_id)
	{
		$ret = array();
		if ($vlan_floor_id == "")
		{
			return $ret;
		}

		$sql = $this->getQuery('GET_VLAN_ROOM_ARY', $vlan_floor_id);

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getVlanAry($vlan_room_id)
	{
		$ret = array();
		if ($vlan_room_id == "")
		{
			return $ret;
		}

		$sql = $this->getQuery('GET_VLAN_ARY', $vlan_room_id);

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getAppTypeAry()
	{
		$sql = $this->getQuery('GET_APP_TYPE_ARY');

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getBelongClassAry()
	{
		$sql = $this->getQuery('GET_BELONG_CLASS_ARY');

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getBelongDivAry($belong_class_id="")
	{
		if ($belong_class_id == "")
		{
			$sql = $this->getQuery('GET_BELONG_DIV_ARY_ALL');
		}
		else
		{
			$sql = $this->getQuery('GET_BELONG_DIV_ARY', $belong_class_id);
		}

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getBelongDepAry($belong_div_id="")
	{
		if ($belong_div_id == "")
		{
			$sql = $this->getQuery('GET_BELONG_DEP_ARY_ALL');
		}
		else
		{
			$sql = $this->getQuery('GET_BELONG_DEP_ARY', $belong_div_id);
		}

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getBelongSecAry($belong_dep_id="")
	{
		if ($belong_dep_id == "")
		{
			$sql = $this->getQuery('GET_BELONG_SEC_ARY_ALL');
		}
		else
		{
			$sql = $this->getQuery('GET_BELONG_SEC_ARY', $belong_dep_id);
		}

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getBelongChgAry($belong_sec_id="")
	{
		if ($belong_sec_id == "")
		{
			$sql = $this->getQuery('GET_BELONG_CHG_ARY_ALL');
		}
		else
		{
			$sql = $this->getQuery('GET_BELONG_CHG_ARY', $belong_sec_id);
		}

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getJobAry()
	{
		$sql = $this->getQuery('GET_JOB_ARY');

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getPostAry()
	{
		$sql = $this->getQuery('GET_POST_ARY');

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getWardAry($wardstatus="")
	{
		if ($wardstatus == "")
		{
			$sql = $this->getQuery('GET_WARD_ARY_ALL');
		}
		else
		{
			$sql = $this->getQuery('GET_WARD_ARY', $wardstatus);
		}

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getProfessionAry($professionstatus="")
	{
		if ($professionstatus == "")
		{
			$sql = $this->getQuery('GET_PROFESSION_ARY_ALL');
		}
		else
		{
			$sql = $this->getQuery('GET_PROFESSION_ARY', $professionstatus);
		}

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getGradeAry()
	{
		$sql = $this->getQuery('GET_GRADE_ARY');

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getDeptAry($deptstatus="")
	{
		if ($deptstatus == "")
		{
			$sql = $this->getQuery('GET_DEPT_ARY_ALL');
		}
		else
		{
			$sql = $this->getQuery('GET_DEPT_ARY', $deptstatus);
		}

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getDeptgroupAry($deptcode="")
	{
		if ($deptcode == "")
		{
			$sql = $this->getQuery('GET_DEPTGROUP_ARY_ALL');
		}
		else
		{
			$sql = $this->getQuery('GET_DEPTGROUP_ARY', $deptcode);
		}

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getUserTypeAry()
	{
		$sql = $this->getQuery('GET_USER_TYPE_ARY');

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getUserRoleAry()
	{
		$sql = $this->getQuery('GET_USER_ROLE_ARY');

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	/*
	 * makeHiddenStr
	 */
	function makeHiddenStr($data, $parent="")
	{
		$str = "";
		if (is_array($data))
		{
			foreach ($data AS $key => $value)
			{
				// nameの設定
				if ($parent == "")
				{
					$name = $key;
				}
				else
				{
					$name = $parent . "[" . $key . "]";
				}

				if (is_array($value))
				{
					$str .= $this->makeHiddenStr($value, $name);
				}
				else
				{
					$value = htmlspecialchars($value);

					$str .= '<input type="hidden" name="' . $name . '" value="' . $value . '">' . "\n";
				}
			}
		}

		return $str;
	}

	/*
	 * getSqlArgs
	 */
	function getSqlArgs()
	{
		$args = array();
		if (isset($_SESSION))
		{
			$sql_login_id = $this->sqlItemInteger($_SESSION['LOGIN_USER_ID']);
			$args['UPDATE_ID'] = $sql_login_id;
			$args['MAKE_ID'] = $sql_login_id;
		}
		return $args;
	}


	//--------------------------------------------
	// 関数名		: sessionStart()
	// 機能			:
	//--------------------------------------------
	function sessionStart()
	{
		if (!isset($_SESSION))
		{
			$this->cacheControl();
			session_start();
			session_regenerate_id();
		}
	}

	//--------------------------------------------
	// 関数名		: loginCheck()
	// 機能			:
	//--------------------------------------------
	function loginCheck()
	{
		// セッション開始
		$this->sessionStart();

		if (!isset ($_SESSION['LOGINCHK']))
		{
			return false;
		}

		return true;
	}

	function getSessionData($name)
	{
		$this->sessionStart();

		if (!isset($_SESSION[$name]))
		{
			return "";
		}

		return $_SESSION[$name];
	}

	function logout()
	{
		$this->sessionStart();

		// セッションを破棄
		unset($_SESSION);

		session_destroy();


		// ログイン画面へリダイレクト
		header("Location: index.php");
	}

	function sqlItemInteger($value)
	{
		$value = (string)$value;
		if ($value != "")
		{
			$ret = $value;
		}
		else
		{
			$ret = "NULL";
		}

		return $ret;
	}

	function sqlItemChar($value)
	{
		$value = (string)$value;
		if ($value != "")
		{
			$ret = "'" . string::replaceSql($value) . "'";
		}
		else
		{
			$ret = "NULL";
		}

		return $ret;
	}

	function sqlItemFlg($value)
	{
		$value = (string)$value;
		if ($value == "1")
		{
			$ret = "'1'";
		}
		else
		{
			$ret = "'0'";
		}

		return $ret;
	}

	function showSystemError()
	{
		exit;
	}

	function postTo($script_name, $params)
	{
		// hidden項目作成
		$hidden_str = $this->makeHiddenStr($params);

		// $script_nameは呼び出したテンプレート内で使用
		include_once("view/post_to.tpl");
		exit;
	}

	//--------------------------------------------
	// デストラクタ
	//--------------------------------------------
	function __destruct()
	{
		$this->oDb->disconnect();
	}


}// End of common_mgr
?>