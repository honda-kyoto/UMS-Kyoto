<?php
/**********************************************************
* File         : apps_entry_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/apps_regist_common_mgr.class.php");
require_once("sql/apps_entry_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_entry_mgr extends apps_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function insertAppData(&$request)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$mac_addr = $this->getFlatMacAddr($request['mac_addr']);

		$this->oDb->begin();

		if (isset($request['unknown_data']))
		{
			$request['app_id'] = $request['unknown_data']['app_id'];
		}

		$id_addr = "";
		$wireless_id = "";
		if (@$request['app_id'] != "")
		{
			$app_id = $request['app_id'];
			$entry_kbn = ENTRY_KBN_EDIT;

			$aryOrg = $this->getAppHead($app_id);
			if ($aryOrg['app_id'] == "")
			{
				// 登録却下の再申請の場合
				$entry_kbn = ENTRY_KBN_ADD;
			}
			else
			{
				$ip_addr = $aryOrg['ip_addr'];
				$wireless_id = $aryOrg['wireless_id'];
			}
		}
		else
		{
			$app_id = $this->oDb->getSequence("app_id_seq");
			$entry_kbn = ENTRY_KBN_ADD;
		}


		// 区分別にチェック
		list ($wire_kbn, $ip_kbn) = $this->getAppTypeKbns($request['app_type_id']);

		if ($wire_kbn == WIRE_KBN_FREE)
		{
			$wire_kbn = $request['wire_kbn'];
		}

		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$wireless_id = @$aryOrg['wireless_id'];

			if ($wireless_id == "")
			{
				$aryUser = $this->getUserData($user_id);
				$login_id = $aryUser['login_id'];
				$wireless_id = $this->createWirelessId($login_id);
			}
		}



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
		$args['MAC_ADDR'] = $this->sqlItemChar($mac_addr);
		$args['IP_ADDR'] = $this->sqlItemChar($ip_addr);
		$args['WIRE_KBN'] = $this->sqlItemChar(@$request['wire_kbn']);
		$args['IP_KBN'] = $this->sqlItemChar(@$request['ip_kbn']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['ENTRY_ID'] = $this->sqlItemInteger($user_id);
		$args['WIRELESS_ID'] = $this->sqlItemChar($wireless_id);
		$args['USE_SBC'] = $this->sqlItemFlg(@$aryOrg['use_sbc']);

		$sql = $this->getQuery('INSERT_APP_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// 申請対象VLANを保持
		$target_vlan_id = $request['vlan_id'];

		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			// 承認済みのデータをコピー
			$aryVlan = $this->getTempVlanList();

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

					$sql = $this->getQuery('INSERT_APP_LIST_ENTRY_SAVE', $args);

					$ret = $this->oDb->query($sql);

					if (!$ret)
					{
						Debug_Print($sql);
						$this->oDb->rollback();
						return false;
					}
				}
			}

			// VLANの追加があれば登録
			if ($request['wl_vlan_id'] != "")
			{
				$args = $this->getSqlArgs();
				$args['APP_ID'] = $this->sqlItemInteger($app_id);
				$args['ENTRY_NO'] = $this->sqlItemInteger($entry_no);
				$args['VLAN_ID'] = $this->sqlItemInteger($request['wl_vlan_id']);
				$args['ENTRY_ID'] = $this->sqlItemInteger($user_id);
				$busy_flg = '0';
				if (count($aryVlan) == 0)
				{
					$busy_flg = '1';
				}
				$args['BUSY_FLG'] = $this->sqlItemFlg($busy_flg);

				$sql = $this->getQuery('INSERT_APP_LIST_ENTRY', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}

				// 申請対象VLAN
				$target_vlan_id = $request['wl_vlan_id'];
			}
		}

		// エントリーテーブルを更新
		$ret = $this->updateUserEntryFlg($user_id, 'app_entry_flg');

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		$this->clearTempVlanList();

		// ここにメール送信処理を入れる
		$this->sendEntryMail($target_vlan_id);


		$request['app_id'] = $app_id;
		$request['entry_no'] = $entry_no;

		return true;
	}

	function sendEntryMail($vlan_id)
	{
		// 申請者名
		$entry_name = $this->getUserName();

		$mail_data = file_get_contents('mail/app_entry.tpl', FILE_USE_INCLUDE_PATH);

		// メールデータを取り出す
		list ($head, $bodySrc) = explode ("BODY:", $mail_data);

		// 件名
		$subject = trim(str_replace("TITLE:", "", $head));

		// 本文
		$bodySrc = str_replace("{ENTRY_NAME}", $entry_name, $bodySrc);

		$aryIds = $this->getVlanAdminIds($vlan_id);

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
