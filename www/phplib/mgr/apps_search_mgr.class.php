<?php
/**********************************************************
* File         : apps_search_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/apps_regist_common_mgr.class.php");
require_once("sql/apps_search_sql.inc.php");

define ("DEFAULT_ORDER", "update_time");
define ("DEFAULT_DESC", "DESC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_search_mgr extends apps_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function saveSearchData($request)
	{
		$_SESSION['AP_SCH']['app_type_id'] = @$request['app_type_id'];
		$_SESSION['AP_SCH']['vlan_ridge_id'] = @$request['vlan_ridge_id'];
		$_SESSION['AP_SCH']['vlan_floor_id'] = @$request['vlan_floor_id'];
		$_SESSION['AP_SCH']['vlan_room_id'] = @$request['vlan_room_id'];
		$_SESSION['AP_SCH']['vlan_id'] = @$request['vlan_id'];
		$_SESSION['AP_SCH']['mac_addr'] = @$request['mac_addr'];
		$_SESSION['AP_SCH']['ip_addr'] = @$request['ip_addr'];
		$_SESSION['AP_SCH']['app_name'] = @$request['app_name'];
		$_SESSION['AP_SCH']['entry_kbn_status'] = @$request['entry_kbn_status'];
		$_SESSION['AP_SCH']['app_user_name'] = @$request['app_user_name'];

		$_SESSION['AP_SCH']['list_max'] = @$request['list_max'];
		$_SESSION['AP_SCH']['order']    = "";
		$_SESSION['AP_SCH']['desc']     = "";
	}

	function saveOrderData($request)
	{
		$desc = "";
		if (@$request['order'] == @$_SESSION['AP_SCH']['order'])
		{
			if (@$_SESSION['AP_SCH']['desc'] == "")
			{
				$desc = "DESC";
			}
		}
		$_SESSION['AP_SCH']['order'] = $request['order'];
		$_SESSION['AP_SCH']['desc']  = $desc;
	}

	function loadOrderKey()
	{
		return @$_SESSION['AP_SCH']['order'];
	}

	function loadOrderDesc()
	{
		return $_SESSION['AP_SCH']['desc'];
	}

	function savePage($page)
	{
		$_SESSION['AP_SCH']['page'] = $page;
	}

	function saveListMax($max)
	{
		$_SESSION['AP_SCH']['list_max'] = $max;
	}

	function loadSearchData(&$request)
	{
		$request['app_type_id'] = @$_SESSION['AP_SCH']['app_type_id'];
		$request['vlan_ridge_id'] = @$_SESSION['AP_SCH']['vlan_ridge_id'];
		$request['vlan_floor_id'] = @$_SESSION['AP_SCH']['vlan_floor_id'];
		$request['vlan_room_id'] = @$_SESSION['AP_SCH']['vlan_room_id'];
		$request['vlan_id'] = @$_SESSION['AP_SCH']['vlan_id'];
		$request['mac_addr'] = @$_SESSION['AP_SCH']['mac_addr'];
		$request['ip_addr'] = @$_SESSION['AP_SCH']['ip_addr'];
		$request['app_name'] = @$_SESSION['AP_SCH']['app_name'];
		$request['entry_kbn_status'] = @$_SESSION['AP_SCH']['entry_kbn_status'];
		$request['app_user_name'] = @$_SESSION['AP_SCH']['app_user_name'];

		$request['list_max'] = @$_SESSION['AP_SCH']['list_max']   ;
		$request['order'] = @$_SESSION['AP_SCH']['order']   ;
		$request['desc'] = @$_SESSION['AP_SCH']['desc']    ;
	}

	function loadPage(&$page)
	{
		$page = @$_SESSION['AP_SCH']['page'];
	}

	function getSearchArgs($request)
	{
		$args = $this->getSqlArgs();
		$args['COND'] = "";
		$args['ENTRY_STATUS_ENTRY'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);
		$args['ENTRY_STATUS_REJECT'] = $this->sqlItemChar(ENTRY_STATUS_REJECT);

		// ソート処理のために以下の定数をSQLパラメータに追加
		$args['IP_KBN_DHCP'] = $this->sqlItemChar(IP_KBN_DHCP);
		$args['IP_KBN_FREE'] = $this->sqlItemChar(IP_KBN_FREE);
		$args['DUMY_IP_ADDR_DHCP'] = $this->sqlItemChar(DUMY_IP_ADDR_DHCP);
		$args['DUMY_IP_ADDR_NONE'] = $this->sqlItemChar(DUMY_IP_ADDR_NONE);
		$args['WIRE_KBN_WLESS'] = $this->sqlItemChar(WIRE_KBN_WLESS);
		$args['WIRE_KBN_FREE'] = $this->sqlItemChar(WIRE_KBN_FREE);

		$aryCond = array();

		// 機器種別
		if (@$request['app_type_id'] != "")
		{
			$aryCond[] = "APP.app_type_id = " . string::replaceSql($request['app_type_id']);
		}

		// 設置場所
		if (@$request['vlan_id'] != "")
		{
			$aryCond[] = "APP.vlan_id = " . string::replaceSql($request['vlan_id']);
		}
		else if (@$request['vlan_room_id'] != "")
		{
			$aryCond[] = "APP.vlan_room_id = " . string::replaceSql($request['vlan_room_id']);
		}
		else if (@$request['vlan_floor_id'] != "")
		{
			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst WHERE APP.vlan_room_id = vlan_room_id AND del_flg = '0' AND vlan_floor_id = " . string::replaceSql($request['vlan_floor_id']) . ")";
		}
		else if (@$request['vlan_ridge_id'] != "")
		{

			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst AS VRM,vlan_floor_mst AS VFM WHERE APP.vlan_room_id = VRM.vlan_room_id AND VRM.vlan_floor_id = VFM.vlan_floor_id AND VRM.del_flg = '0' AND VFM.del_flg = '0' AND VFM.vlan_ridge_id = " . string::replaceSql($request['vlan_ridge_id']) . ")";
		}

		// 申請者名（漢字・カナ）
		if (@$request['app_user_name'] != "")
		{
			$kananame = string::han2zen($request['app_user_name']);
			$kananame = str_replace("　", " ", $kananame);
			$kananame = str_replace(" ", "", $kananame);
			$strBuff  = "EXISTS (SELECT * FROM user_mst AS UM ";
			$strBuff .= "WHERE (";
			$strBuff .= "APP.app_user_id = UM.user_id ";
			$strBuff .= " OR ";
			$strBuff .= "APP.entry_id = UM.user_id ";
			$strBuff .= ") AND (";
			$strBuff .= "UM.kanasei || UM.kanamei LIKE '%" . string::replaceSql($kananame) . "%'";
			$strBuff .= " OR ";
			$strBuff .= "UM.kanjisei || UM.kanjimei LIKE '%" . string::replaceSql($kananame) . "%'";
			$strBuff .= ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}

		// MACアドレス
		if (@$request['mac_addr'] != "")
		{
			$mac_addr = strtolower($request['mac_addr']);
			$mac_addr = str_replace(":", "", $mac_addr);
			$mac_addr = str_replace("-", "", $mac_addr);
			$aryCond[] = "APP.mac_addr LIKE '%" . string::replaceSql($mac_addr) . "%'";
		}

		// IPアドレス
		if (@$request['ip_addr'] != "")
		{
			$aryCond[] = "APP.ip_addr LIKE '%" . string::replaceSql($request['ip_addr']) . "%'";
		}

		// 名称
		if (@$request['app_name'] != "")
		{
			$aryCond[] = "APP.app_name LIKE '%" . string::replaceSql($request['app_name']) . "%'";
		}


		$cnt = 0;
		if (is_array($request['entry_kbn_status']))
		{
			foreach ($request['entry_kbn_status'] AS $key => $val)
			{
				if ($val != "1")
				{
					continue;
				}

				if ($key == 'agreed')
				{
					$aryKbnStat[] = "(APP.entry_kbn IS NULL AND APP.entry_status IS NULL)";
				}

				list ($entry_kbn, $entry_status) = explode("_", $key);

				$aryKbnStat[] = "(APP.entry_kbn = '" . string::replaceSql($entry_kbn) . "' AND APP.entry_status = '" . string::replaceSql($entry_status) . "')";

				$cnt++;
			}
		}
		if ($cnt > 0)
		{
			$aryCond[] = "(" . implode(" OR ", $aryKbnStat) . ")";
		}


		// ログインユーザの権限によって処理分け

		// 管理権限があるか？
		if (!$this->hasAdminActType('apps_search.php'))
		{
			// 一般ユーザの場合自分の申請したもののみ表示
			$user_id = $this->getSessionData('LOGIN_USER_ID');
			$uid = string::replaceSql($user_id);

			$usersCond = <<<  SQL
(
EXISTS (SELECT * FROM app_head_entry WHERE APP.app_id = app_id AND entry_id = $uid)
OR
APP.app_user_id = $uid

SQL;

			if ($this->isVlanAdminUser())
			{
				// VLAN管理者の場合

				$usersCond .= <<<  SQL
OR
EXISTS (SELECT * FROM vlan_admin_list WHERE APP.vlan_id = vlan_id AND del_flg = '0' AND user_id = $uid)
OR
EXISTS (SELECT * FROM vlan_admin_list AS VA, app_list_tbl AS AL WHERE VA.vlan_id = AL.vlan_id AND AL.app_id = APP.app_id AND VA.del_flg = '0' AND AL.del_flg = '0' AND VA.user_id = $uid)
OR
EXISTS (SELECT * FROM vlan_admin_list AS VA, app_list_entry AS AL WHERE VA.vlan_id = AL.vlan_id AND AL.app_id = APP.app_id AND AL.entry_no = APP.entry_no AND VA.del_flg = '0' AND AL.del_flg = '0' AND VA.user_id = $uid)

SQL;

			}


			$aryCond[] = $usersCond . ")";

		}


		if (count($aryCond) > 0)
		{
			$args['COND'] = " WHERE " . join(" AND ", $aryCond);
		}

		return $args;
	}

	function getCount($request)
	{
		$args = $this->getSearchArgs($request);

		$sql = $this->getQuery('GETCOUNT', $args);

		$count = $this->oDb->getOne($sql);

		return $count;
	}

	/*
	 * getList
	*/
	function getList($request, $limit)
	{
		$args = $this->getSearchArgs($request);

		if ($request['order'] != "")
		{
			switch($request['order'])
			{
				case "ip_addr":
					$args['ORDER'] = "LPAD(split_part(ip_addr_sort, '.', 1), 3,'0') " .
									"|| LPAD(split_part(ip_addr_sort, '.', 2), 3,'0') " .
									"|| LPAD(split_part(ip_addr_sort, '.', 3), 3,'0') " .
									"|| LPAD(split_part(ip_addr_sort, '.', 4), 3,'0') ";
					break;
				case "entry_status":
					$args['ORDER'] = "entry_kbn || '_' || entry_status";
					break;
				default:
					$args['ORDER'] = $request['order'];
					break;
			}

			$args['DESC'] = $request['desc'];
		}
		else
		{
			$args['ORDER'] = DEFAULT_ORDER;
			$args['DESC'] = DEFAULT_DESC;
		}

		if ($limit == "")
		{
			$limit = DEFAULT_LIST_MAX;
		}

		$offset = ($request['page'] - 1) * $limit;

		$args['LIMIT'] = $limit;
		$args['OFFSET'] = $offset;

		$sql = $this->getQuery('GETLIST', $args);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}

	function delAppEntry($app_id, &$entry_no)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$request = $this->getAppHead($app_id);

		$this->oDb->begin();

		//$app_id = $request['app_id'];
		$entry_kbn = ENTRY_KBN_DEL;

		// 申請番号取得（ロック）
		$sql = $this->getQuery('ENTRY_LOCK', $args);
		$this->oDb->query($sql);

		$sql = $this->getQuery('GET_ENTRY_NO', $app_id);

		$entry_no = $this->oDb->getOne($sql);

		$args = $this->getSqlArgs();
		$args['APP_ID'] = $this->sqlItemInteger($app_id);
		$args['ENTRY_NO'] = $this->sqlItemInteger($entry_no);
		$args['ENTRY_STATUS'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);
		$args['ENTRY_KBN'] = $this->sqlItemChar($entry_kbn);
		$args['APP_TYPE_ID'] = $this->sqlItemInteger($request['app_type_id']);
		$args['APP_NAME'] = $this->sqlItemChar($request['app_name']);
		$args['VLAN_ROOM_ID'] = $this->sqlItemInteger($request['vlan_room_id']);
		$args['VLAN_ID'] = $this->sqlItemInteger(@$request['vlan_id']);
		$args['MAC_ADDR'] = $this->sqlItemChar(@$request['mac_addr']);
		$args['IP_ADDR'] = $this->sqlItemChar(@$request['ip_addr']);
		$args['WIRE_KBN'] = $this->sqlItemChar(@$request['wire_kbn']);
		$args['IP_KBN'] = $this->sqlItemChar(@$request['ip_kbn']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['USE_SBC'] = $this->sqlItemFlg($request['use_sbc']);
		$args['ENTRY_ID'] = $this->sqlItemInteger($user_id);

		$sql = $this->getQuery('INSERT_APP_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$aryTargetVlanId = array();
		if (@$request['vlan_id'] != "")
		{
			$aryTargetVlanId[] = $request['vlan_id'];
		}

		// 区分別にチェック
		list ($wire_kbn, $ip_kbn) = $this->getAppTypeKbns($request['app_type_id']);

		if ($wire_kbn == WIRE_KBN_FREE)
		{
			$wire_kbn = $request['wire_kbn'];
		}

		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$aryVlan = $this->getAppList($app_id);

			if (is_array($aryVlan))
			{
				$args = $this->getSqlArgs();
				$args['APP_ID'] = $this->sqlItemInteger($app_id);
				$args['ENTRY_NO'] = $this->sqlItemInteger($entry_no);
				foreach ($aryVlan AS $vlan_id => $data)
				{
					$args['VLAN_ID'] = $this->sqlItemInteger($vlan_id);
					$args['ENTRY_ID'] = $this->sqlItemInteger($user_id);
					$args['BUSY_FLG'] = $this->sqlItemFlg($data['busy_flg']);

					$sql = $this->getQuery('INSERT_APP_LIST_ENTRY', $args);

					$ret = $this->oDb->query($sql);

					if (!$ret)
					{
						Debug_Print($sql);
						$this->oDb->rollback();
						return false;
					}

					$aryTargetVlanId[] = $vlan_id;
				}
			}
		}

		$this->oDb->end();

		// ここにメール送信処理を入れる
		$this->sendDelEntryMail($aryTargetVlanId);

		return true;
	}

	function deleleAppData($app_id)
	{
		$aryOrg = $this->getAppHead($app_id);

		// 区分別にチェック
		list ($wire_kbn, $ip_kbn) = $this->getAppTypeKbns($aryOrg['app_type_id']);

		if ($wire_kbn == WIRE_KBN_FREE)
		{
			$wire_kbn = $request['wire_kbn'];
		}

		$aryOrgList = array();
		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$aryOrgList = $this->getAppList($app_id);
		}

		// 削除
		$this->oDb->begin();

		$args = $this->getSqlArgs();
		$args[0] = $app_id;
		$sql = $this->getQuery('DELETE_APP_HEAD_TBL', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$sql = $this->getQuery('DELETE_APP_LIST_TBL', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// AD連携
		$ret = $this->relationAd($wire_kbn, $aryOrg, array(), $aryOrgList, array());

		if (!$ret)
		{
			$this->makeAppAdErrLog($app_id);
		}

		return true;
	}

	function sendDelEntryMail($aryTargetVlanId)
	{
		// 申請者名
		$entry_name = $this->getUserName();

		$mail_data = file_get_contents('mail/app_delreq.tpl', FILE_USE_INCLUDE_PATH);

		// メールデータを取り出す
		list ($head, $bodySrc) = explode ("BODY:", $mail_data);

		// 件名
		$subject = trim(str_replace("TITLE:", "", $head));

		// 本文
		$bodySrc = str_replace("{ENTRY_NAME}", $entry_name, $bodySrc);

		$aryIds = array();

		if (is_array($aryTargetVlanId))
		{
			foreach ($aryTargetVlanId AS $vlan_id)
			{
				$aryTmp = $this->getVlanAdminIds($vlan_id);

				if (is_array($aryTmp))
				{
					foreach ($aryTmp AS $adm_id)
					{
						if (!in_array($adm_id, $aryIds))
						{
							$aryIds[] = $adm_id;
						}
					}
				}
			}

		}

		if (is_array($aryIds))
		{
			foreach ($aryIds AS $admin_id)
			{
				$aryAdm = $this->getUserData($admin_id);

				if ($aryAdm['mail_acc'] == "")
				{
					continue;
				}

				// 管理者名
				$admin_name = $aryAdm['kanjisei'] . "　" . $aryAdm['kanjimei'];
				$body = str_replace("{ADMIN_NAME}", $admin_name, $bodySrc);
				$mail_to = $aryAdm['mail_acc'] . USER_MAIL_DOMAIN;

				$params = array();
				$params['admin_name'] = $admin_name;
				$params['entry_name'] = $entry_name;
				$params['mail_to'] = $mail_to;
				$params['subject'] = $subject;
				$params['body'] = $body;

				Debug_Trace($params, "1234");

				// メール送信
				$this->sendSystemMail($mail_to, $subject, $body, SYSMAIL_FROM, SYSMAIL_SENDERNAME, SYSMAIL_CC, SYSMAIL_BCC, SYSMAIL_ENVELOP, SYSMAIL_RETURN_PATH);

			}
		}
	}

}
?>
