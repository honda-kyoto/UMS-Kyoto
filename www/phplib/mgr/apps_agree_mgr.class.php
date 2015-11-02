<?php
/**********************************************************
* File         : apps_agree_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/apps_regist_common_mgr.class.php");
require_once("sql/apps_agree_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_agree_mgr extends apps_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function agreeAppEntry($request)
	{
		$app_id = $request['app_id'];
		$entry_no = $request['entry_no'];

		$this->oDb->begin();

		// 申請内容によって処理わけ
		$aryEnt = $this->getAppHead($app_id, $entry_no);

		$entry_kbn = $aryEnt['entry_kbn'];
		$entry_id = $aryEnt['entry_id'];
		$app_type_id = $aryEnt['app_type_id'];
		list ($wire_kbn, $ip_kbn) = $this->getAppTypeKbns($app_type_id);
		if ($wire_kbn == WIRE_KBN_FREE)
		{
			$wire_kbn = $aryEnt['wire_kbn'];
		}

		// AD連携用にデータを取っておく。
		$aryOrg = $this->getAppHead($app_id);

		$make_wireless_id = false;

		$aryEntList = array();
		$aryOrgList = array();
		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$aryEntList = $this->getAppList($app_id, $entry_no);
			$aryOrgList = $this->getAppList($app_id);
		}

		if ($entry_kbn == ENTRY_KBN_DEL)
		{
			$aryEnt = array();
			$aryEntList = array();
		}


		switch ($entry_kbn)
		{
			case ENTRY_KBN_ADD:
				$ret = $this->insertAppOrgData($request, $wire_kbn);
				break;
			case ENTRY_KBN_EDIT:
				$ret = $this->updateAppOrgData($request, $wire_kbn);
				break;
			case ENTRY_KBN_DEL:
				$ret = $this->deleteAppOrgData($request, $wire_kbn);
				break;
		}

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$make_wireless_id = false;
		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			// 新規・更新時無線IDチェック
			if ($entry_kbn != ENTRY_KBN_DEL)
			{
				$wid = $this->getUserWirelessId($aryEnt['entry_id']);

				if ($wid == "")
				{
					$this->makeWirelessId($aryEnt['entry_id']);

					if (!$ret)
					{
						$this->oDb->rollback();
						return false;
					}

					$make_wireless_id = true;
				}
			}
			else
			{
				// 削除の場合、無線利用機器がなくなれば無線IDを削除する処理を追加。あとで・・・
			}
		}

		// 申請データを承認済みに変更
		$args = $this->getSqlArgs();
		$args[0] = $app_id;
		$args[1] = $entry_no;
		$args['ENTRY_STATUS'] = $this->sqlItemChar(ENTRY_STATUS_AGREE);

		$sql = $this->getQuery('AGREE_APP_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();


		// AD連携
		$ret = $this->relationAd($wire_kbn, $aryOrg, $aryEnt, $aryOrgList, $aryEntList, $make_wireless_id);

		if (!$ret)
		{
			$this->makeAppAdErrLog($app_id);
		}

		// メール送信
		$this->sendAgreeMail($entry_id, $entry_kbn);

		return true;
	}

	function insertAppOrgData($request, $wire_kbn)
	{
		$app_id = $request['app_id'];
		$entry_no = $request['entry_no'];
		$ip_addr = $request['ip_addr'];

		$args = $this->getSqlArgs();
		$args[0] = $app_id;
		$args[1] = $entry_no;
		$args['IP_ADDR'] = $this->sqlItemChar($ip_addr);
		$args['USE_SBC'] = $this->sqlItemFlg(@$request['use_sbc']);

		$sql = $this->getQuery('INSERT_APP_ORG_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			// リストを登録
			$sql = $this->getQuery('INSERT_APP_ORG_LIST', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				return false;
			}
		}

		return true;
	}

	function updateAppOrgData($request, $wire_kbn)
	{
		$app_id = $request['app_id'];
		$entry_no = $request['entry_no'];
		$ip_addr = $request['ip_addr'];

		$args = $this->getSqlArgs();
		$args[0] = $app_id;
		$args[1] = $entry_no;
		$args['IP_ADDR'] = $this->sqlItemChar($ip_addr);

		$sql = $this->getQuery('UPDATE_APP_ORG_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$aryEntList = $this->getAppList($app_id, $entry_no);
			$aryOrgList = $this->getAppList($app_id);

			if (is_array($aryEntList))
			{
				foreach ($aryEntList AS $vlan_id => $data)
				{
					if (isset($aryOrgList[$vlan_id]))
					{
						// 登録済み
						// 内容に差異がないか確認
						$is_changed = false;
						if ($aryOrgList[$vlan_id]['busy_flg'] != $data['busy_flg'])
						{
							$is_changed = true;
						}

						// 処理するので消しておく
						unset($aryOrgList[$vlan_id]);

						// 変わってなければスキップ
						if (!$is_changed)
						{
							continue;
						}
					}

					$args = $this->getSqlArgs();
					$args['APP_ID'] = $this->sqlItemInteger($app_id);
					$args['VLAN_ID'] = $this->sqlItemInteger($vlan_id);
					$args['BUSY_FLG'] = $this->sqlItemFlg($data['busy_flg']);

					// 存在チェック
					$sql = $this->getQuery('EXISTS_APP_LIST_DATA', $args);

					$ext_id = $this->oDb->getOne($sql);

					if ($ext_id != "")
					{
						$sql_id = 'UPDATE_APP_LIST_DATA';
					}
					else
					{
						$sql_id = 'INSERT_APP_LIST_DATA';
					}

					$sql = $this->getQuery($sql_id, $args);

					$ret = $this->oDb->query($sql);

					if (!$ret)
					{
						Debug_Print($sql);
						return false;
					}
				}
			}

			// 残ってたら消す
			if (is_array($aryOrgList))
			{
				foreach ($aryOrgList AS $vlan_id => $data)
				{
					$args = $this->getSqlArgs();
					$args['APP_ID'] = $this->sqlItemInteger($app_id);
					$args['VLAN_ID'] = $this->sqlItemInteger($vlan_id);

					$sql = $this->getQuery('DELETE_APP_LIST_DATA', $args);

					$ret = $this->oDb->query($sql);

					if (!$ret)
					{
						Debug_Print($sql);
						return false;
					}
				}
			}
		}

		return true;
	}

	function deleteAppOrgData($request, $wire_kbn)
	{
		$app_id = $request['app_id'];

		$args = $this->getSqlArgs();
		$args[0] = $app_id;

		$sql = $this->getQuery('DELETE_APP_ORG_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			// リストを登録
			$sql = $this->getQuery('DELETE_APP_ORG_LIST', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				return false;
			}
		}

		return true;
	}

	function rejectAppEntry($request)
	{
		$app_id = $request['app_id'];
		$entry_no = $request['entry_no'];
		$agree_note = $request['agree_note'];

		// 申請内容によって処理わけ
		$aryEnt = $this->getAppHead($app_id, $entry_no);

		$entry_kbn = $aryEnt['entry_kbn'];
		$entry_id = $aryEnt['entry_id'];


		$args = $this->getSqlArgs();
		$args[0] = $app_id;
		$args[1] = $entry_no;
		$args['AGREE_NOTE'] = $this->sqlItemChar($agree_note);
		$args['ENTRY_STATUS'] = $this->sqlItemChar(ENTRY_STATUS_REJECT);

		$sql = $this->getQuery('REJECT_APP_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// メール送信
		$this->sendRejectMail($entry_id, $entry_kbn);

		return true;
	}

	function sendResultMail($entry_id, $entry_kbn, $file)
	{
		// 申請者名
		$aryUser = $this->getUserData($entry_id);

		if ($aryUser['mail_acc'] == "")
		{
			Debug_Trace("承認メール対象アドレスなし", "1234");
			return true;
		}

		$entry_name = $aryUser['kanjisei'] . "　" . $aryUser['kanjimei'];
		$mail_to = $aryUser['mail_acc'] . USER_MAIL_DOMAIN;

		$entry_kbn_name = "";
		switch ($entry_kbn)
		{
			case ENTRY_KBN_ADD:
				$entry_kbn_name = "登録";
				break;
			case ENTRY_KBN_EDIT:
				$entry_kbn_name = "更新";
				break;
			case ENTRY_KBN_DEL:
				$entry_kbn_name = "削除";
				break;
		}


		$mail_data = file_get_contents($file, FILE_USE_INCLUDE_PATH);

		// メールデータを取り出す
		list ($head, $body) = explode ("BODY:", $mail_data);

		// 件名
		$subject = trim(str_replace("TITLE:", "", $head));
		$subject = str_replace("{ENTRY_KBN}", $entry_kbn_name, $subject);

		// 本文
		$body = str_replace("{ENTRY_NAME}", $entry_name, $body);
		$body = str_replace("{ENTRY_KBN}", $entry_kbn_name, $body);

		$params = array();
		$params['entry_name'] = $entry_name;
		$params['mail_to'] = $mail_to;
		$params['subject'] = $subject;
		$params['body'] = $body;

		Debug_Trace($params, "1234");

		// メール送信
		$this->sendSystemMail($mail_to, $subject, $body, SYSMAIL_FROM, SYSMAIL_SENDERNAME, SYSMAIL_CC, SYSMAIL_BCC, SYSMAIL_ENVELOP, SYSMAIL_RETURN_PATH);
	}

	function sendAgreeMail($entry_id, $entry_kbn)
	{
		$file = 'mail/app_agree.tpl';

		$this->sendResultMail($entry_id, $entry_kbn, $file);
	}


	function sendRejectMail($entry_id, $entry_kbn)
	{
		$file = 'mail/app_reject.tpl';

		$this->sendResultMail($entry_id, $entry_kbn, $file);
	}

}
?>
