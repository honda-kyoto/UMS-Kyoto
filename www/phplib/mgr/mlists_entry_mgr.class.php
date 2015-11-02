<?php
/**********************************************************
* File         : mlists_entry_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/mlists_regist_common_mgr.class.php");
require_once("sql/mlists_entry_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mlists_entry_mgr extends mlists_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function insertMlistData(&$request)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$this->oDb->begin();

		$id_addr = "";
		if (@$request['mlist_id'] != "")
		{
			$mlist_id = $request['mlist_id'];
			$entry_kbn = ENTRY_KBN_EDIT;

			$aryOld = $this->getMlistData($mlist_id);
			$mlist_kbn = $aryOld['mlist_kbn'];
		}
		else
		{
			$mlist_id = $this->oDb->getSequence("mlist_id_seq");
			$entry_kbn = ENTRY_KBN_ADD;
			$mlist_kbn = $request['mlist_kbn'];
		}

		// 申請番号取得（ロック）
		$sql = $this->getQuery('ENTRY_LOCK', $args);
		$this->oDb->query($sql);

		$sql = $this->getQuery('GET_ENTRY_NO', $mlist_id);

		$entry_no = $this->oDb->getOne($sql);

		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);
		$args['ENTRY_NO'] = $this->sqlItemInteger($entry_no);
		$args['ENTRY_STATUS'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);
		$args['ENTRY_KBN'] = $this->sqlItemChar($entry_kbn);
		$args['MLIST_NAME'] = $this->sqlItemChar($request['mlist_name']);
		$args['MLIST_ACC'] = $this->sqlItemChar($request['mlist_acc']);
		$args['SENDER_KBN'] = $this->sqlItemChar($request['sender_kbn']);
		$args['MLIST_KBN'] = $this->sqlItemChar($mlist_kbn);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['USAGE'] = $this->sqlItemChar($request['usage']);
		$args['ENTRY_ID'] = $this->sqlItemInteger($user_id);

		$sql = $this->getQuery('INSERT_MLIST_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}


		//
		// 管理者データ
		//

		if (is_array($request['admin_id']))
		{
			$args = $this->getSqlArgs();
			$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);

			foreach ($request['admin_id'] AS $no => $user_id)
			{
				$args['ENTRY_NO'] = $this->sqlItemInteger($entry_no);
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['USER_ID'] = $this->sqlItemInteger($user_id);
				$args['ENTRY_ID'] = $this->sqlItemInteger($user_id);

				$sql = $this->getQuery('INSERT_MLIST_ADMIN_ENTRY', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}
			}
		}

		// エントリーテーブルを更新
		$ret = $this->updateUserEntryFlg($user_id, 'mlist_entry_flg');

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// ここにメール送信処理を入れる
		$this->sendEntryMail();

		$request['mlist_id'] = $mlist_id;
		$request['entry_no'] = $entry_no;

		return true;
	}


	function sendEntryMail()
	{
		// 申請者名
		$entry_name = $this->getUserName();

		$mail_data = file_get_contents('mail/mlist_entry.tpl', FILE_USE_INCLUDE_PATH);

		// メールデータを取り出す
		list ($head, $bodySrc) = explode ("BODY:", $mail_data);

		// 件名
		$subject = trim(str_replace("TITLE:", "", $head));

		// 本文
		$bodySrc = str_replace("{ENTRY_NAME}", $entry_name, $bodySrc);

		$admin_id = MLIST_ADMIN_USER_ID;

		$aryAdm = $this->getUserData($admin_id);

		if ($aryAdm['mail_acc'] == "")
		{
			return;
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
?>
