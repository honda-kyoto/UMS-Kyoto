<?php
/**********************************************************
* File         : users_edit_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/users_regist_common_mgr.class.php");
require_once("sql/users_edit_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class users_edit_mgr extends users_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getSubBelongData($user_id)
	{
		$sql = $this->getQuery('GET_SUB_BELONG_DATA', $user_id);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubJobData($user_id)
	{
		$sql = $this->getQuery('GET_SUB_JOB_DATA', $user_id);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubPostData($user_id)
	{
		$sql = $this->getQuery('GET_SUB_POST_DATA', $user_id);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubHisData($user_id)
	{
		$sql = $this->getQuery('GET_SUB_HIS_DATA', $user_id);

		$ret = $this->oDb->getAssoc($sql);

		if (is_array($ret))
		{
			foreach ($ret AS $key => $aryData)
			{

				if ($aryData['sub_staffcode'] != "")
				{
					$ret[$key]['sub_his_flg'] = '1';
					$ret[$key]['sub_has_his_data'] = '1';
				}

				if ($aryData['sub_password'] != "")
				{
					$ret[$key]['sub_password'] = $this->passwordDecrypt($aryData['sub_password']);
				}
			}
		}

		return $ret;
	}

	function getRoleData($user_id)
	{
		$sql = $this->getQuery('GET_ROLE_DATA', $user_id);

		$tmp = $this->oDb->getCol($sql);

		$ret = array();
		if (is_array($tmp))
		{
			foreach ($tmp AS $val)
			{
				if ($val < 10)
				{
					$ret['user_type_id'] = $val;
				}
				else
				{
					$ret['user_role_id'][$val] = '1';
				}
			}
		}

		return $ret;
	}

	function getPrintNcvcIdData($user_id)
	{
		$sql = $this->getQuery('GET_NCVC_ID_DATA', $user_id);

		$ret = $this->oDb->getRow($sql);

		if ($ret !== false)
		{
			if ($ret['login_passwd'] != "")
			{
				$ret['login_passwd'] = $this->passwordDecrypt($ret['login_passwd']);
			}
			if ($ret['login_passwd'] != "")
			{
				$ret['login_passwd_furigana'] = $this->makePasswordFurigana($ret['login_passwd']);
			}
			if ($ret['belong_dep_name'] != "")
			{
				$ret['belong_name'] = $ret['belong_dep_name'];
			}
			else if ($ret['belong_sec_name'] != "")
			{
				$ret['belong_name'] = $ret['belong_sec_name'];
			}
			else if ($ret['belong_chg_name'] != "")
			{
				$ret['belong_name'] = $ret['belong_chg_name'];
			}
			else if ($ret['belong_div_name'] != "")
			{
				$ret['belong_name'] = $ret['belong_div_name'];
			}
			else if ($ret['belong_class_name'] != "")
			{
				$ret['belong_name'] = $ret['belong_class_name'];
			}
		}

		return $ret;
	}

	function getJunHisData($user_id, $list_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $list_no;

		$sql = $this->getQuery('GET_JUN_HIS_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		if ($ret !== false)
		{
			if ($ret['wardstatus'] != "")
			{
				$ret['wardname'] = $GLOBALS['wardstatus'][$ret['wardstatus']] . "　" . $ret['wardname'];
			}
			if ($ret['password'] != "")
			{
				$ret['password'] = $this->passwordDecrypt($ret['password']);
			}
			if ($ret['password'] != "")
			{
				$ret['password_furigana'] = $this->makePasswordFurigana($ret['password']);
			}

		}

		return $ret;
	}

	function makeHistoryData($user_id)
	{
		//
		// 履歴レコードを作成
		//

		// 履歴番号取得（ロック）
		$sql = $this->getQuery('HISTORY_LOCK', $args);
		$this->oDb->query($sql);

		$sql = $this->getQuery('GET_HISTORY_NO', $user_id);

		$history_no = $this->oDb->getOne($sql);

		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		// ユーザマスタ
		$sql = $this->getQuery('MAKE_HISTORY_USER_MST', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// サブ所属
		$sql = $this->getQuery('MAKE_HISTORY_SUB_CHG', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// サブ職種
		$sql = $this->getQuery('MAKE_HISTORY_SUB_JOB', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// サブ所属
		$sql = $this->getQuery('MAKE_HISTORY_SUB_POST', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// HIS連携データ
		$sql = $this->getQuery('MAKE_HISTORY_USER_HIS', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// 権限データ
		$sql = $this->getQuery('MAKE_HISTORY_USER_ROLE', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function updateUserData($request)
	{
		// ユーザID
		$user_id = $request['user_id'];

		// チェック用に現状を取得
		$aryOld = $this->getUserData($user_id);
		$aryOldSub = $this->getSubHisData($user_id);
		$aryOldJob = $this->getSubJobData($user_id);
		$aryOldPost = $this->getSubPostData($user_id);
		$aryOldBelong = $this->getSubBelongData($user_id);

		$this->oDb->begin();

		// 一括削除用のパラメータ
		$delArgs = $this->getSqlArgs();
		$delArgs['USER_ID'] = $this->sqlItemInteger($user_id);

		// 履歴作成
		$ret = $this->makeHistoryData($user_id);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// データ加工
		$birthday = "";
		if ($request['birth_year'] != "" && $request['birth_mon'] != "" && $request['birth_day'] != "")
		{
			$birthday = sprintf("%04d-%02d-%02d", $request['birth_year'], $request['birth_mon'], $request['birth_day']);
		}

		// 不明ユーザーの場合、パスワードが入力されていれば更新対象とする
		$login_passwd = "";
		if ($request['is_unknown_user'] && $request['login_passwd'] != "")
		{
			$login_passwd = $this->passwordEncrypt($request['login_passwd']);
		}

		list ($start_y, $start_m, $start_d) = explode("/", $request['start_date']);
		$start_date = sprintf("%04d-%02d-%02d", $start_y, $start_m, $start_d);
		$end_date = "";
		if ($request['end_date'] != "")
		{
			list ($end_y, $end_m, $end_d) = explode("/", $request['end_date']);
			$end_date = sprintf("%04d-%02d-%02d", $end_y, $end_m, $end_d);
		}


		// 基本情報
		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['STAFF_ID'] = $this->sqlItemChar($request['staff_id']);
		$args['STAFFCODE'] = $this->sqlItemChar($request['staffcode']);
		$args['KANJISEI'] = $this->sqlItemChar($request['kanjisei']);
		$args['KANJIMEI'] = $this->sqlItemChar($request['kanjimei']);
		$args['KANASEI'] = $this->sqlItemChar($request['kanasei']);
		$args['KANAMEI'] = $this->sqlItemChar($request['kanamei']);
		$args['EIJISEI'] = $this->sqlItemChar($request['eijisei']);
		$args['EIJIMEI'] = $this->sqlItemChar($request['eijimei']);
		$args['KYUSEI'] = $this->sqlItemChar($request['kyusei']);
		$args['SEX'] = $this->sqlItemChar($request['sex']);
		$args['BIRTHDAY'] = $this->sqlItemChar($birthday);
		$args['LOGIN_ID'] = $this->sqlItemChar($request['login_id']);
		$args['MAIL_ACC'] = $this->sqlItemChar($request['mail_acc']);
		$args['BELONG_CHG_ID'] = $this->sqlItemInteger($request['belong_chg_id']);
		$args['JOB_ID'] = $this->sqlItemInteger($request['job_id']);
		$args['POST_ID'] = $this->sqlItemInteger($request['post_id']);
		$args['NAISEN'] = $this->sqlItemChar($request['naisen']);
		$args['PBNO'] = $this->sqlItemChar($request['pbno']);
		$args['JOUKIN_KBN'] = $this->sqlItemChar($request['joukin_kbn']);
		$args['START_DATE'] = $this->sqlItemChar($start_date);
		$args['END_DATE'] = $this->sqlItemChar($end_date);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['RETIRE_FLG'] = $this->sqlItemFlg($request['retire_flg']);
		$args['MAIL_DISUSED_FLG'] = $this->sqlItemFlg($request['mail_disused_flg']);
		$args['GAROON_DISUSED_FLG'] = $this->sqlItemFlg($request['garoon_disused_flg']);
		$args['MLIST_DISUSED_FLG'] = $this->sqlItemFlg($request['mlist_disused_flg']);
		$args['VDI_USER_FLG'] = $this->sqlItemFlg($request['vdi_user_flg']);

		// 更新するパスワードがあれば
		if ($login_passwd != "")
		{
			$sql_id = "UPDATE_USER_MST_WITH_PASSWD";
			$args['LOGIN_PASSWD'] = $this->sqlItemChar($login_passwd);
		}
		else
		{
			$sql_id = "UPDATE_USER_MST";
		}

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		//
		// 権限データ
		//
		// 一旦論理削除
		$sql = $this->getQuery('DELETE_USER_ROLE_DATA', $delArgs);

		$ret = $this->oDb->query($sql);

		// 利用者種別
		$ret = $this->updateUserRoleData($user_id, $request['user_type_id']);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 管理権限
		if (is_array($request['user_role_id']))
		{
			foreach ($request['user_role_id'] AS $role_id => $val)
			{
				if ($val != "1")
				{
					continue;
				}

				$ret = $this->updateUserRoleData($user_id, $role_id);

				if (!$ret)
				{
					$this->oDb->rollback();
					return false;
				}
			}
		}

		// サブ所属
		// 一旦論理削除
		$sql = $this->getQuery('DELETE_USER_SUB_BELONG_CHG', $delArgs);

		$ret = $this->oDb->query($sql);

		if (is_array($request['sub_belong_chg_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);

			foreach ($request['sub_belong_chg_id'] AS $no => $belong_chg_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['BELONG_CHG_ID'] = $this->sqlItemInteger($belong_chg_id);

				// 存在チェック
				$sql = $this->getQuery('EXISTS_SUB_BELONG_CHG', $args);

				$list_no = $this->oDb->getOne($sql);

				if ($list_no == "")
				{
					$sql_id = "INSERT_SUB_BELONG_CHG";
				}
				else
				{
					$sql_id = "UPDATE_SUB_BELONG_CHG";
				}

				$sql = $this->getQuery($sql_id, $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}
			}
		}

		// サブ職種
		// 一旦論理削除
		$sql = $this->getQuery('DELETE_USER_SUB_JOB', $delArgs);

		$ret = $this->oDb->query($sql);

		if (is_array($request['sub_job_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);

			foreach ($request['sub_job_id'] AS $no => $job_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['JOB_ID'] = $this->sqlItemInteger($job_id);

				// 存在チェック
				$sql = $this->getQuery('EXISTS_SUB_JOB', $args);

				$list_no = $this->oDb->getOne($sql);

				if ($list_no == "")
				{
					$sql_id = "INSERT_SUB_JOB";
				}
				else
				{
					$sql_id = "UPDATE_SUB_JOB";
				}

				$sql = $this->getQuery($sql_id, $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}
			}
		}

		// サブ役職
		// 一旦論理削除
		$sql = $this->getQuery('DELETE_USER_SUB_POST', $delArgs);

		$ret = $this->oDb->query($sql);

		if (is_array($request['sub_post_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);

			foreach ($request['sub_post_id'] AS $no => $post_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['POST_ID'] = $this->sqlItemInteger($post_id);

				// 存在チェック
				$sql = $this->getQuery('EXISTS_SUB_POST', $args);

				$list_no = $this->oDb->getOne($sql);

				if ($list_no == "")
				{
					$sql_id = "INSERT_SUB_POST";
				}
				else
				{
					$sql_id = "UPDATE_SUB_POST";
				}

				$sql = $this->getQuery($sql_id, $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}
			}
		}

		//
		// HIS連携
		//
		// 一旦論理削除
		$sql = $this->getQuery('DELETE_USER_HIS', $delArgs);

		$ret = $this->oDb->query($sql);

		if ($request['his_flg'] == "1")
		{
			$ret = $this->updateUserHisData($user_id, &$request);

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		//
		// HISサブアカウント
		//
		if (is_array($request['sub_his_flg']))
		{
			$no = 1;
			foreach ($request['sub_his_flg'] AS $key => $val)
			{
				if ($val != "1")
				{
					continue;
				}

				$ret = $this->updateUserHisData($user_id, &$request, $no, $key);

				if (!$ret)
				{
					$this->oDb->rollback();
					return false;
				}

				$no++;
			}
		}

		// 統合IDが変わった場合非表示扱いの統合ID@ncvc.go.jpがあれば削除する
		if ($aryOld['login_id'] != $request['login_id'])
		{
			// DBだけ更新すればメールサーバには自動的に新しく作られる
			$args = $this->getSqlArgs();
			$args[0] = $user_id;
			$args[1] = $aryOld['login_id'] . USER_MAIL_DOMAIN;

			// あればdel_flgが１になる
			$sql = $this->getQuery('DELETE_HIDDEN_ADDR', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				$this->oDb->rollback();
				return false;
			}
		}

		// その他アドレスに統合ID@ncvc.go.jpを追加（非表示）
		// 統合IDが既にメールアカウントとして使用されている場合は無視
		if ($request['login_id'] != "" && @$request['mail_disused_flg'] != '1' && $request['login_id'] != $request['mail_acc'])
		{
			$ret = $this->makeHiddenAddr($user_id, $request['login_id']);

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}


		// 一旦トランザクション終了
		$this->oDb->end();


		//
		// メールアカウント連携
		//


		if ($aryOld['login_id'] != $request['login_id'])
		{
			// 統合IDが変わった場合

			// 削除
			$this->delUserMailAddr($aryOld['login_id']);

			$aryTmp = $request;

			// 新たに登録するべきデータがあれば追加
			if ($request['login_id'] != "")
			{
				$this->relationUserMailAddr('add', $user_id);
			}
		}
		else if ($aryOld['mail_acc'] != $request['mail_acc'])
		{
			// メールアドレスのみ変わった場合
			if ($request['mail_acc'] == "")
			{
				// アカウントの廃止
				$this->delUserMailAddr($request['login_id']);
			}
			else if ($aryOld['mail_acc'] == "")
			{
				$this->relationUserMailAddr('add', $user_id);
			}
			else
			{
				// 更新
				$this->relationUserMailAddr('edit', $user_id);
			}
		}

		//
		// AD連携
		//
		if ($aryOld['login_id'] != $request['login_id'])
		{
			$this->relationAd($user_id, $request['login_id'], $aryOld['login_id']);
		}

		//
		// HIS連携
		//

		//
		// HIS連携
		//
		if ($request['his_flg'] == "1")
		{
			$this->relationHis($request, $aryOld);
		}
		else if ($aryOld['his_flg'] == "1")
		{
			// 削除された
			//$this->relationHisDelete($aryOld['staffcode']);
		}

		$aryNewSC = array();
		$aryOldSC = array();
		if (is_array($request['sub_his_flg']))
		{
			foreach ($request['sub_his_flg'] AS $key => $val)
			{
				if ($val != "1")
				{
					continue;
				}

				$buff = $request;
				$buff['send_date'] = $request['sub_send_date'][$key];
				$buff['staffcode'] = $request['sub_staffcode'][$key];
				$buff['kanjiname'] = $request['sub_kanjiname'][$key];
				$buff['kananame'] = $request['sub_kananame'][$key];
				$buff['password'] = $request['sub_password'][$key];
				$buff['wardcode'] = $request['sub_wardcode'][$key];
				$buff['professioncode'] = $request['sub_professioncode'][$key];
				$buff['gradecode'] = $request['sub_gradecode'][$key];
				$buff['appcode'] = $request['sub_appcode'][$key];
				$buff['deptcode'] = $request['sub_deptcode'][$key];
				$buff['deptgroupcode'] = $request['sub_deptgroupcode'][$key];
				$buff['validstartdate'] = $request['sub_validstartdate'][$key];
				$buff['validenddate'] = $request['sub_validenddate'][$key];

				$oldBuff = $aryOld;
				$oldBuff['send_date'] = $aryOldSub[$key]['sub_send_date'];
				$oldBuff['staffcode'] = $aryOldSub[$key]['sub_staffcode'];
				$oldBuff['kanjiname'] = $aryOldSub[$key]['sub_kanjiname'];
				$oldBuff['kananame'] = $aryOldSub[$key]['sub_kananame'];
				$oldBuff['password'] = $aryOldSub[$key]['sub_password'];
				$oldBuff['wardcode'] = $aryOldSub[$key]['sub_wardcode'];
				$oldBuff['professioncode'] = $aryOldSub[$key]['sub_professioncode'];
				$oldBuff['gradecode'] = $aryOldSub[$key]['sub_gradecode'];
				$oldBuff['appcode'] = $aryOldSub[$key]['sub_appcode'];
				$oldBuff['deptcode'] = $aryOldSub[$key]['sub_deptcode'];
				$oldBuff['deptgroupcode'] = $aryOldSub[$key]['sub_deptgroupcode'];
				$oldBuff['validstartdate'] = $aryOldSub[$key]['sub_validstartdate'];
				$oldBuff['validenddate'] = $aryOldSub[$key]['sub_validenddate'];

				// 職員コードが変わっている場合配列に保持する
				if ($buff['staffcode'] != $oldBuff['staffcode'] && $oldBuff['staffcode'] != "")
				{
					$aryNewSC[] = $buff['staffcode'];
					$aryOldSC[] = $oldBuff['staffcode'];
				}

				$this->relationHis($buff, $oldBuff);
			}
		}

		if (is_array($aryOldSub))
		{
			foreach ($aryOldSub AS $key => $arySubHis)
			{
				// 今回更新の職員コードに含まれていないものを保持
				if (!in_array($arySubHis['sub_staffcode'], $aryNewSC) && !in_array($arySubHis['sub_staffcode'], $aryOldSC))
				{
					$aryOldSC[] = $arySubHis['sub_staffcode'];
				}
			}
		}

		if (is_array($aryOldSC))
		{
			foreach ($aryOldSC AS $del_code)
			{
				// 別の列に移っていれば除外
				if (in_array($del_code, $aryNewSC))
				{
					continue;
				}
				//$this->relationHisDelete($del_code);
			}
		}

		//
		// メーリングリストの動的配布チェック
		//
		// 変わった職種
		$aryJobChanged = $this->getDiffAttr('job_id', 'sub_job_id', $request, $aryOld['job_id'], $aryOldJob);

		// 変わった役職
		$aryPostChanged = $this->getDiffAttr('post_id', 'sub_post_id', $request, $aryOld['post_id'], $aryOldPost);

		// 変わった所属
		$aryBelongChanged = $this->getDiffAttr('belong_chg_id', 'sub_belong_chg_id', $request, $aryOld['belong_chg_id'], $aryOldBelong);

		$isJoukinChanged = false;
		if ($aryOld['joukin_kbn'] != $request['joukin_kbn'])
		{
			$isJoukinChanged = true;
		}

		$isDisusedChanged = false;
		if ($aryOld['mlist_disused_flg'] != $request['mlist_disused_flg'])
		{
			$isDisusedChanged = true;
		}

		// チェック処理
		$this->checkMlistAutoList($aryJobChanged, $aryPostChanged, $aryBelongChanged, $isJoukinChanged, $isDisusedChanged);


		//
		// ガルーン連携
		//

		// あとで作る
		$garoon_result = "0";

		// 結果を記録　あとで作る

		return true;
	}

	function getDiffAttr($col_name, $sub_col_name, $request, $old_val, $aryOld)
	{
		$aryBefore = array();
		$aryAfter = array();
		if (@$old_val != "")
		{
			$aryBefore[] = $old_val;
		}
		if ($request[$col_name] != "")
		{
			$aryAfter[] = $request[$col_name];
		}
		if (is_array($aryOld))
		{
			foreach ($aryOld AS $data)
			{
				if ($data[$sub_col_name] == "")
				{
					continue;
				}
				$aryBefore[] = $data[$sub_col_name];
			}
		}
		if (is_array(@$request[$sub_col_name]))
		{
			foreach ($request[$sub_col_name] AS $id)
			{
				if ($id == "")
				{
					continue;
				}
				$aryAfter[] = $id;
			}
		}

		// ソート
		sort($aryBefore);
		sort($aryAfter);
		$diff1 = array_diff($aryBefore, $aryAfter);
		//$diff2 = array_diff($aryAfter, $aryBefore);
		$aryChanged = array_merge($diff1, $aryAfter);

		return $aryChanged;
	}

	function updateUserRoleData($user_id, $role_id)
	{
		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['ROLE_ID'] = $this->sqlItemInteger($role_id);

		// 存在チェック
		$sql = $this->getQuery('EXISTS_USER_ROLE', $args);

		$list_no = $this->oDb->getOne($sql);

		if ($list_no == "")
		{
			$sql_id = "INSERT_USER_ROLE";
		}
		else
		{
			$sql_id = "UPDATE_USER_ROLE";
		}

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function updateUserHisData($user_id, &$request, $no="", $key="")
	{
		if ($key =="")
		{
			$no = "0";
			$send_date = $request['send_date'];
			$staffcode = $request['staffcode'];
			$kanjiname = $request['kanjiname'];
			$kananame = $request['kananame'];
			$password = $request['password'];
			$wardcode = $request['wardcode'];
			$professioncode = $request['professioncode'];
			$gradecode = $request['gradecode'];
			$appcode = $request['appcode'];
			$deptcode = $request['deptcode'];
			$deptgroupcode = $request['deptgroupcode'];
			$validstartdate = $request['validstartdate'];
			$validenddate = $request['validenddate'];
		}
		else
		{
			$send_date = $request['sub_send_date'][$key];
			$staffcode = $request['sub_staffcode'][$key];
			$kanjiname = $request['sub_kanjiname'][$key];
			$kananame = $request['sub_kananame'][$key];
			$password = $request['sub_password'][$key];
			$wardcode = $request['sub_wardcode'][$key];
			$professioncode = $request['sub_professioncode'][$key];
			$gradecode = $request['sub_gradecode'][$key];
			$appcode = $request['sub_appcode'][$key];
			$deptcode = $request['sub_deptcode'][$key];
			$deptgroupcode = $request['sub_deptgroupcode'][$key];
			$validstartdate = $request['sub_validstartdate'][$key];
			$validenddate = $request['sub_validenddate'][$key];
		}

		$password = $this->passwordEncrypt($password);
		list ($start_y, $start_m, $start_d) = explode("/", $validstartdate);
		$validstartdate = sprintf("%04d-%02d-%02d", $start_y, $start_m, $start_d);
		list ($end_y, $end_m, $end_d) = explode("/", $validenddate);
		$validenddate = sprintf("%04d-%02d-%02d", $end_y, $end_m, $end_d);
		list ($send_y, $send_m, $send_d) = explode("/", $send_date);
		$send_date = sprintf("%04d-%02d-%02d", $send_y, $send_m, $send_d);

		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['LIST_NO'] = $this->sqlItemInteger($no);
		$args['STAFFCODE'] = $this->sqlItemChar($staffcode);
		$args['WARDCODE'] = $this->sqlItemChar($wardcode);
		$args['PROFESSIONCODE'] = $this->sqlItemChar($professioncode);
		$args['GRADECODE'] = $this->sqlItemChar($gradecode);
		$args['KANANAME'] = $this->sqlItemChar($kananame);
		$args['KANJINAME'] = $this->sqlItemChar($kanjiname);
		$args['PASSWORD'] = $this->sqlItemChar($password);
		$args['VALIDSTARTDATE'] = $this->sqlItemChar($validstartdate);
		$args['VALIDENDDATE'] = $this->sqlItemChar($validenddate);
		$args['DEPTCODE'] = $this->sqlItemChar($deptcode);
		$args['APPCODE'] = $this->sqlItemChar($appcode);
		$args['DEPTGROUPCODE'] = $this->sqlItemChar($deptgroupcode);
		$args['SEND_DATE'] = $this->sqlItemChar($send_date);

		// 存在チェック
		$sql = $this->getQuery('EXISTS_USER_HIS', $args);

		$list_no = $this->oDb->getOne($sql);

		if ($list_no == "")
		{
			$sql_id = "INSERT_USER_HIS";
		}
		else
		{
			$sql_id = "UPDATE_USER_HIS";
		}

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);
		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function reissuePassword($col_name, $list_no, $user_id, $passwd="")
	{
		$this->oDb->begin();

		// 履歴作成
		$ret = $this->makeHistoryData($user_id);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$args = $this->getSqlArgs();
		$args[0] = $user_id;

		$is_his_changed = false;
		if ($col_name == 'login_passwd')
		{
			// パスワード生成
			$src_pwd = $this->createPassword();
			$sql_id = 'UPDATE_LOGIN_PASSWD';
		}
		else
		{
			$args[2] = $list_no;
			$src_pwd = $passwd;
			$sql_id = 'UPDATE_HIS_PASSWORD';
			$is_his_changed = true;
		}
		$pwd = $this->passwordEncrypt($src_pwd);

		$args[1] = $pwd;


		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return "0";
		}

		$this->oDb->end();

		// 登録データ取得
		$aryData = $this->getUserData($user_id);

		if ($is_his_changed)
		{
			//
			// HIS連携
			//

			// コミットしてるので現在の登録データをそのままセット
			if ($list_no != "0")
			{
				// サブ
				$aryTmp = $this->getSubHisData($user_id);
				$aryData['send_date'] = $aryTmp[$list_no]['sub_send_date'];
				$aryData['staffcode'] = $aryTmp[$list_no]['sub_staffcode'];
				$aryData['kanjiname'] = $aryTmp[$list_no]['sub_kanjiname'];
				$aryData['kananame'] = $aryTmp[$list_no]['sub_kananame'];
				$aryData['password'] = $aryTmp[$list_no]['sub_password'];
				$aryData['wardcode'] = $aryTmp[$list_no]['sub_wardcode'];
				$aryData['professioncode'] = $aryTmp[$list_no]['sub_professioncode'];
				$aryData['gradecode'] = $aryTmp[$list_no]['sub_gradecode'];
				$aryData['appcode'] = $aryTmp[$list_no]['sub_appcode'];
				$aryData['deptcode'] = $aryTmp[$list_no]['sub_deptcode'];
				$aryData['deptgroupcode'] = $aryTmp[$list_no]['sub_deptgroupcode'];
				$aryData['validstartdate'] = $aryTmp[$list_no]['sub_validstartdate'];
				$aryData['validenddate'] = $aryTmp[$list_no]['sub_validenddate'];
			}

			//$aryData['password'] = $src_pwd;

			$this->relationHis($aryData);
		}
		else
		{
			//
			// AD連携
			//
			$this->setPasswordAd($user_id);

			// メール連携
			$this->relationUserMailAddr('edit', $user_id);
		}

		$_SESSION['password_user_id'] = $user_id;
		$_SESSION['password_col_name'] = $col_name;
		$_SESSION['password_list_no'] = $list_no;

		return "1";
	}
}
?>
