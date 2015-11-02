<?php
/**********************************************************
* File         : users_regist_common_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");

define (LOG_KBN_REGIST, "0");
define (LOG_KBN_EDIT, "1");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class users_regist_common_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function insertUserData(&$request)
	{
		// ファイルサーバ使用登録用配列
		$aryNewChgId = array();
		$aryNewChgId[] = $request['belong_chg_id'];

		$this->oDb->begin();

		// ユーザID取得
		$user_id = $this->oDb->getSequence('user_id_seq');

		// データ加工
		$birthday = "";
		if ($request['birth_year'] != "" && $request['birth_mon'] != "" && $request['birth_day'] != "")
		{
			$birthday = sprintf("%04d-%02d-%02d", $request['birth_year'], $request['birth_mon'], $request['birth_day']);
		}

		$login_passwd = $this->passwordEncrypt($request['login_passwd']);

		list ($start_y, $start_m, $start_d) = explode("/", $request['start_date']);
		$start_date = sprintf("%04d-%02d-%02d", $start_y, $start_m, $start_d);
		$end_date = "";
		if ($request['end_date'] != "")
		{
			list ($end_y, $end_m, $end_d) = explode("/", $request['end_date']);
			$end_date = sprintf("%04d-%02d-%02d", $end_y, $end_m, $end_d);
		}

		if ($request['ftrans_user_flg'] != "1")
		{
			$request['ftrans_user_kbn'] = "0";
		}

		// 基本情報
		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['STAFF_ID'] = $this->sqlItemChar($request['staff_id']);
		$args['KANJISEI'] = $this->sqlItemChar($request['kanjisei']);
		$args['KANJIMEI'] = $this->sqlItemChar($request['kanjimei']);
		$args['KANASEI'] = $this->sqlItemChar($request['kanasei']);
		$args['KANAMEI'] = $this->sqlItemChar($request['kanamei']);
		$args['EIJISEI'] = $this->sqlItemChar($request['eijisei']);
		$args['EIJIMEI'] = $this->sqlItemChar($request['eijimei']);
		$args['KANJISEI_REAL'] = $this->sqlItemChar($request['kanjisei_real']);
		$args['KANJIMEI_REAL'] = $this->sqlItemChar($request['kanjimei_real']);
		$args['KANASEI_REAL'] = $this->sqlItemChar($request['kanasei_real']);
		$args['KANAMEI_REAL'] = $this->sqlItemChar($request['kanamei_real']);
		$args['KYUSEI'] = $this->sqlItemChar($request['kyusei']);
		$args['SEX'] = $this->sqlItemChar($request['sex']);
		$args['BIRTHDAY'] = $this->sqlItemChar($birthday);
		$args['LOGIN_ID'] = $this->sqlItemChar($request['login_id']);
		$args['LOGIN_PASSWD'] = $this->sqlItemChar($login_passwd);
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
		$args['MAIL_DISUSED_FLG'] = $this->sqlItemFlg($request['mail_disused_flg']);
		$args['GAROON_DISUSED_FLG'] = $this->sqlItemFlg($request['garoon_disused_flg']);
		$args['MLIST_DISUSED_FLG'] = $this->sqlItemFlg($request['mlist_disused_flg']);
		$args['VDI_USER_FLG'] = $this->sqlItemFlg($request['vdi_user_flg']);
		$args['FTRANS_USER_FLG'] = $this->sqlItemFlg($request['ftrans_user_flg']);
		$args['FTRANS_USER_KBN'] = $this->sqlItemChar($request['ftrans_user_kbn']);
		$args['STAFF_ID_FLG'] = $this->sqlItemFlg($request['staff_id_flg']);

		$sql = $this->getQuery('URC_INSERT_USER_MST', $args);

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

		// 利用者種別
		$ret = $this->insertUserRoleData($user_id, $request['user_type_id']);

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

				$ret = $this->insertUserRoleData($user_id, $role_id);

				if (!$ret)
				{
					$this->oDb->rollback();
					return false;
				}
			}
		}

		// サブ所属
		if (is_array($request['sub_belong_chg_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);

			foreach ($request['sub_belong_chg_id'] AS $no => $belong_chg_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['BELONG_CHG_ID'] = $this->sqlItemInteger($belong_chg_id);

				$sql = $this->getQuery('URC_INSERT_SUB_BELONG_CHG', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}

				// 配列に追加
				if (!in_array($belong_chg_id, $aryNewChgId))
				{
					$aryNewChgId[] = $belong_chg_id;
				}
			}
		}

		// サブ職種
		if (is_array($request['sub_job_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);

			foreach ($request['sub_job_id'] AS $no => $job_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['JOB_ID'] = $this->sqlItemInteger($job_id);

				$sql = $this->getQuery('URC_INSERT_SUB_JOB', $args);

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
		if (is_array($request['sub_post_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);

			foreach ($request['sub_post_id'] AS $no => $post_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['POST_ID'] = $this->sqlItemInteger($post_id);

				$sql = $this->getQuery('URC_INSERT_SUB_POST', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}
			}
		}

		// サブ職員番号
		if (is_array($request['sub_staff_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);

			foreach ($request['sub_staff_id'] AS $no => $staff_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['STAFF_ID'] = $this->sqlItemChar($staff_id);

				$sql = $this->getQuery('URC_INSERT_SUB_STAFF_ID', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}
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


		//
		// メニュー生成用のレコード作成
		//
		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['VDI_USER_FLG'] = $this->sqlItemFlg($request['vdi_user_flg']);

		$sql = $this->getQuery('URC_INSERT_USER_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}


		// 更新ログ作成
		$ret = $this->makeEditLog($user_id, LOG_KBN_REGIST);

		if (!$ret)
		{
			Debug_Print("更新ログ作成失敗");
			$this->oDb->rollback();
			return false;
		}


		// 一旦トランザクション終了
		$this->oDb->end();

		if ($request['login_id'] != "" && $request['login_passwd'] != "")
		{
			//
			// AD連携
			//
			$ret = $this->relationAd($user_id, $aryNewChgId);

			if (!$ret)
			{
				// エラーログ
				$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'AD');
			}

			//
			// メールアカウント連携
			//
			$ret = $this->relationUserMailAddr('add', $user_id);

			if (!$ret)
			{
				// エラーログ
				$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
			}
		}

		//
		// メーリングリストの動的配布チェック
		//
		// 変わった職種
		$aryJobChanged = $this->getNewAttr('job_id', 'sub_job_id', $request);

		// 変わった役職
		$aryPostChanged = $this->getNewAttr('post_id', 'sub_post_id', $request);

		// 変わった所属
		$aryBelongChanged = $this->getNewAttr('belong_chg_id', 'sub_belong_chg_id', $request);

		// チェック処理
		$this->checkMlistAutoList($aryJobChanged, $aryPostChanged, $aryBelongChanged, true, true);


		// 結果を記録　あとで作る

		$request['user_id'] = $user_id;

		return true;
	}

	function getNewAttr($col_name, $sub_col_name, $request)
	{
		$aryNew = array();
		if (@$request[$col_name] != "")
		{
			$aryNew[] = $request[$col_name];
		}
		if (is_array(@$request[$sub_col_name]))
		{
			foreach ($request[$sub_col_name] AS $id)
			{
				if ($id == "")
				{
					continue;
				}
				$aryNew[] = $id;
			}
		}

		return $aryNew;
	}

	function insertUserRoleData($user_id, $role_id)
	{
		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['ROLE_ID'] = $this->sqlItemInteger($role_id);

		$sql = $this->getQuery('URC_INSERT_USER_ROLE', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function existsStaffId($staff_id, $user_id="")
	{
		$args = array();
		$args[0] = $staff_id;
		$args['COND'] = "";
		if ($user_id != "")
		{
			$args['COND'] = " AND user_id != " . string::replaceSql($user_id);
		}

		$sql = $this->getQuery('EXISTS_STAFF_ID', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		return false;
	}

	function existsStaffcode($staffcode, $user_id="")
	{
		$args = array();
		$args[0] = $staffcode;
		$args['COND'] = "";
		if ($user_id != "")
		{
			$args['COND'] = " AND user_id != " . string::replaceSql($user_id);
		}

		$sql = $this->getQuery('EXISTS_STAFFCODE', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		return false;
	}

	function checkMlistAutoList($aryJobChanged, $aryPostChanged, $aryBelongChanged, $isJoukinChanged, $isDisusedChanged)
	{
		// 条件に該当するもののIDを取得
		$args = array();
		$jobIds = implode(",", $aryJobChanged);
		$postIds = implode(",", $aryPostChanged);
		$chgIds = implode(",", $aryBelongChanged);

		// 所属上位を取得
		$aryClass = array();
		$aryDiv = array();
		$aryDep = array();
		$arySec = array();
		if (is_array($aryBelongChanged))
		{
			foreach ($aryBelongChanged AS $belong_chg_id)
			{
				$params = array();
				$params['belong_chg_id'] = $belong_chg_id;

				$this->getBelongName(&$params);

				$aryClass[] = $params['belong_class_id'];
				$aryDiv[] = $params['belong_div_id'];
				$aryDep[] = $params['belong_dep_id'];
				$arySec[] = $params['belong_sec_id'];
			}
		}
		$classIds = implode(",", $aryClass);
		$divIds = implode(",", $aryDiv);
		$depIds = implode(",", $aryDep);
		$secIds = implode(",", $arySec);

		$aryCond = array();

		if ($jobIds != "")
		{
			$aryCond[] = "job_id IN (" . $jobIds . ")";
		}

		if ($postIds != "")
		{
			$aryCond[] = "post_id IN (" . $postIds . ")";
		}

		if ($classIds != "")
		{
			$aryCond[] = "belong_class_id IN (" . $classIds . ")";
		}

		if ($divIds != "")
		{
			$aryCond[] = "belong_div_id IN (" . $divIds . ")";
		}

		if ($depIds != "")
		{
			$aryCond[] = "belong_dep_id IN (" . $depIds . ")";
		}

		if ($secIds != "")
		{
			$aryCond[] = "belong_sec_id IN (" . $secIds . ")";
		}

		if ($chgIds != "")
		{
			$aryCond[] = "belong_chg_id IN (" . $chgIds . ")";
		}

		if ($isJoukinChanged)
		{
			$aryCond[] = "joukin_kbn IS NOT NULL";
		}

		if (!$isDisusedFlg && count($aryCond) == 0)
		{
			return;
		}

		$args['COND'] = "";
		if (count($aryCond) > 0)
		{
			$args['COND'] = "AND (" . implode(" OR ", $aryCond) . ")";
		}

		$sql = $this->getQuery('GET_COND_CHANGED_MLIST_ID', $args);

		Debug_Trace($sql, 333);

		$aryMlistId = $this->oDb->getCol($sql);

		if (is_array($aryMlistId))
		{
			foreach ($aryMlistId AS $mlist_id)
			{
				$this->relationAutoMembers($mlist_id);
			}
		}
	}

	function makeHiddenAddr($user_id, $login_id)
	{
		$oldmail_addr = $login_id . USER_MAIL_DOMAIN;
		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $oldmail_addr;

		// 古いアドレスにないかチェック
		$sql = $this->getQuery('URC_EXISTS_OLDMAIL_ADDR', $args);

		$addr = $this->oDb->getOne($sql);

		if ($addr != "")
		{
			// あれば何もしない
			return true;
		}


		$sql = $this->getQuery('INSERT_HIDDEN_ADDR', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function getCtrlId($mode_name)
	{
		$sql = $this->getQuery('GET_CTRL_ID', $mode_name);

		$id = $this->oDb->getOne($sql);

		return $id;
	}

	function makeEditLog($user_id, $log_kbn, $mode_name="", $reserve_flg="0", $reflect_date="")
	{
		//
		return true;

		$log_user_id = $this->getSessionData('LOGIN_USER_ID');

		$ctrl_id = "";
		if ($mode_name != "")
		{
			$ctrl_id = $this->getCtrlId($mode_name);
		}

		$args = array();

		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['LOG_KBN'] = $this->sqlItemChar($log_kbn);
		$args['CTRL_ID'] = $this->sqlItemInteger($ctrl_id);
		$args['LOG_USER_ID'] = $this->sqlItemInteger($log_user_id);
		$args['RESERVE_FLG'] = $this->sqlItemFlg($reserve_flg);
		$args['REFLECT_DATE'] = $this->sqlItemChar($reflect_date);

		$sql = $this->getQuery("INSERT_EDIT_LOG", $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function getBelongSgName($belong_chg_id)
	{
		$sql = $this->getQuery('GET_SG_NAME', $belong_chg_id);

		$ret = $this->oDb->getOne($sql);

		return $ret;
	}

	function relationAd($user_id, $aryNewChgId, $aryOld=array())
	{
		if (!defined("LDAP_HOST_1"))
		{
			return true;
		}

		//接続開始
		$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
		$is_sec = false;
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
			$is_sec = true;
		}

		if (!$ldap_conn)
		{
			Debug_Trace("接続失敗", 1);
			$this->makeUserAdErrLog($user_id);
			return false;
		}

		$ldap_bind  = ldap_bind($ldap_conn, LDAP_DN, LDAP_PASS);

		if (!$ldap_bind)
		{
			Debug_Trace("バインド失敗", 1);
			$this->makeUserAdErrLog($user_id);
			return false;
		}

		$aryData = $this->getUserData($user_id);
		$login_id = $aryData['login_id'];

		// 変更時前のデータを削除 ※変わっている場合のみ
		if (@$aryOld['login_id'] != "" && $aryOld['login_id'] != $login_id)
		{
			// 存在チェック
			$target = "CN=".$aryOld['login_id'];
			$filter = array("cn", "sAMAccountName");

			$result = ldap_search($ldap_conn, LOGINID_DN, $target, $filter);
			$data = ldap_get_entries($ldap_conn, $result);

			// いたら消す
			if ($data['count'] == 1)
			{
				// 削除
				$deleteDn = $target.",".LOGINID_DN;

				if (ldap_delete($ldap_conn,$deleteDn))
				{
					Debug_Trace("削除は成功しました", 5);
				}
				else
				{
					Debug_Trace("削除は失敗しました", 5);
					$this->makeUserAdErrLog($user_id);
					return false;
				}

			}
		}

		// 新しいIDが空ならこれで終わり
		if ($login_id == "")
		{
			return true;
		}

		$login_passwd = $aryData['login_passwd'];
		$vdi_user_flg = $aryData['vdi_user_flg'];
		$ftrans_user_flg = $aryData['ftrans_user_flg'];
		$ftrans_user_kbn = $aryData['ftrans_user_kbn'];

		//
		// 統合ID
		//

		// 存在チェック
		$target = "CN=".$login_id;
		$filter = array("cn", "sAMAccountName");

		$result = ldap_search($ldap_conn, LOGINID_DN, $target, $filter);
		$data = ldap_get_entries($ldap_conn, $result);

		$users_dn = "CN=".$login_id.",".LOGINID_DN;
		$group_info["member"] = $users_dn;

		// 更新
		if ($data['count'] == 1)
		{
			$mod = array();
			$this->getAdRelUserData($user_id, &$mod);

			if (ldap_modify($ldap_conn,$users_dn,$mod))
			{
				Debug_Trace("更新は成功しました", 1);
			}
			else
			{
				Debug_Trace("更新は失敗しました", 1);
				$this->makeUserAdErrLog($user_id);
				return false;
			}
		}
		else
		{
			// 登録

			// 追加時のパラメータ
			$add["cn"] = $login_id;
			$add["objectClass"] = "user";
			$add["sAMAccountName"] = $login_id;
			$add["userPrincipalName"] = $login_id . AD_LOCAL_DOMAIN;
			$add["UserAccountControl"] = "66048";
			$this->getAdRelUserData($user_id, &$add);


			if (ldap_add($ldap_conn,$users_dn,$add))
			{
				Debug_Trace("追加は成功しました", 1);
			}
			else
			{
				Debug_Trace("追加は失敗しました", 1);
				$this->makeUserAdErrLog($user_id);
				return false;
			}

			// VLANグループの追加
			if (ldap_mod_add($ldap_conn,VLAN400_DN,$group_info))
			{
				Debug_Trace("VLAN参加は成功しました", 1);
			}
			else
			{
				Debug_Trace("VLAN参加は失敗しました", 1);
				$this->makeUserAdErrLog($user_id);
				return false;
			}
		}

		$has_error = false;
		// VDIグループの追加
		if ($vdi_user_flg == "1")
		{
			if ($aryOld['vdi_user_flg'] != "1")
			{
				if (ldap_mod_add($ldap_conn,VDI_DN,$group_info))
				{
					Debug_Trace("VDI参加は成功しました", 1);
				}
				else
				{
					Debug_Trace("VDI参加は失敗しました", 1);
					Debug_Trace($group_info, 1);
					$has_error = true;
				}
			}
		}
		else
		{
			if ($aryOld['vdi_user_flg'] == "1")
			{
				if (ldap_mod_del($ldap_conn,VDI_DN,$group_info))
				{
					Debug_Trace("VDI脱退は成功しました", 1);
				}
				else
				{
					Debug_Trace("VDI脱退は失敗しました", 1);
					Debug_Trace($group_info, 1);
					$has_error = true;
				}
			}
		}

		// ファイル転送グループの追加
		if ($ftrans_user_flg == "1")
		{
			if ($ftrans_user_kbn == $aryOld['ftrans_user_kbn'])
			{
				// 変わってない
				$ftrans_add_kbn = "";
				$ftrans_del_kbn = "";
			}
			else if ($aryOld['ftrans_user_kbn'] == "0")
			{
				// 追加のみ
				$ftrans_add_kbn = $ftrans_user_kbn;
				$ftrans_del_kbn = "";
			}
			else
			{
				// 抜けて追加
				$ftrans_add_kbn = $ftrans_user_kbn;
				$ftrans_del_kbn = $aryOld['ftrans_user_kbn'];
			}

			// 追加
			$ftrans_add_dn = "";
			if ($ftrans_add_kbn == FTRANS_USER_KBN_USR)
			{
				$ftrans_add_dn = FTRANS_DN_USR;
			}
			else if ($ftrans_add_kbn == FTRANS_USER_KBN_ADM)
			{
				$ftrans_add_dn = FTRANS_DN_ADM;
			}

			// 脱退
			$ftrans_del_dn = "";
			if ($ftrans_del_kbn == FTRANS_USER_KBN_USR)
			{
				$ftrans_del_dn = FTRANS_DN_USR;
			}
			else if ($ftrans_del_kbn == FTRANS_USER_KBN_ADM)
			{
				$ftrans_del_dn = FTRANS_DN_ADM;
			}

			if ($ftrans_add_dn != "")
			{
				if (ldap_mod_add($ldap_conn,$ftrans_add_dn,$group_info))
				{
					Debug_Trace("ファイル転送グループ参加は成功しました", 1);
				}
				else
				{
					Debug_Trace("ファイル転送グループ参加は失敗しました", 1);
					Debug_Trace($group_info, 1);
					$has_error = true;
				}
			}

			if ($ftrans_del_dn != "")
			{
				if (ldap_mod_del($ldap_conn,$ftrans_del_dn,$group_info))
				{
					Debug_Trace("ファイル転送グループ脱退は成功しました", 1);
				}
				else
				{
					Debug_Trace("ファイル転送グループ脱退は失敗しました", 1);
					Debug_Trace($group_info, 1);
					$has_error = true;
				}
			}
		}
		else
		{
			// 脱退
			$ftrans_del_dn = "";
			if ($aryOld['ftrans_user_kbn'] == FTRANS_USER_KBN_USR)
			{
				$ftrans_del_dn = FTRANS_DN_USR;
			}
			else if ($aryOld['ftrans_user_kbn'] == FTRANS_USER_KBN_ADM)
			{
				$ftrans_del_dn = FTRANS_DN_ADM;
			}

			if ($ftrans_del_dn != "")
			{
				if (ldap_mod_del($ldap_conn,$ftrans_del_dn,$group_info))
				{
					Debug_Trace("ファイル転送グループ脱退は成功しました", 1);
				}
				else
				{
					Debug_Trace("ファイル転送グループ脱退は失敗しました", 1);
					Debug_Trace($group_info, 1);
					$has_error = true;
				}
			}
		}

		// ファイルサーバグループの追加
		if (is_array($aryNewChgId))
		{
			foreach ($aryNewChgId AS $chg_id)
			{
				$sg_name = $this->getBelongSgName($chg_id);

				if ($sg_name == "")
				{
					continue;
				}

				$sg_name = mb_convert_encoding($sg_name, "sjis-win", "auto");

				$file_server_dn = "CN=".$sg_name.",".FILE_SERVER_DN;

				if (ldap_mod_add($ldap_conn,$file_server_dn,$group_info))
				{
					Debug_Trace("ファイルサーバグループ参加は成功しました", 1);
				}
				else
				{
					Debug_Trace("ファイルサーバグループ参加は失敗しました", 1);
					Debug_Trace($group_info, 1);
					$has_error = true;
				}
			}
		}

		// 抜けるファイルサーバグループがある場合
		if (is_array(@$aryOld['del_belong_chg_id']))
		{
			foreach ($aryOld['del_belong_chg_id'] AS $chg_id)
			{
				$sg_name = $this->getBelongSgName($chg_id);

				if ($sg_name == "")
				{
					continue;
				}

				$sg_name = mb_convert_encoding($sg_name, "sjis-win", "auto");

				$file_server_dn = "CN=".$sg_name.",".FILE_SERVER_DN;

				if (ldap_mod_del($ldap_conn,$file_server_dn,$group_info))
				{
					Debug_Trace("ファイルサーバグループ脱退は成功しました", 1);
				}
				else
				{
					Debug_Trace("ファイルサーバグループ脱退は失敗しました", 1);
					Debug_Trace($group_info, 1);
					$has_error = true;
				}

			}
		}

		// 無線IDの登録があるか確認
		// パスワードが変わったか旧データがない場合
		if ($aryOld['login_passwd'] == "" || ($aryOld['login_passwd'] != $login_passwd))
		{
			$ret = $this->checkAdWirelessId(&$ldap_conn, $user_id, $login_passwd);
			if (!$ret)
			{
				$has_error = true;
			}
		}

		ldap_close($ldap_conn);

		if ($has_error)
		{
			return false;
		}

		return true;
	}

	function isChangeHisData($request, $aryOld)
	{
		// 変わっている項目があるかチェック

		// 職員コード
		if ($request['send_date'] != $aryOld['send_date'])
		{
			//Debug_Trace("send_date", 1);
			//return true;
		}

		// 職員コード
		if ($request['staffcode'] != $aryOld['staffcode'])
		{
			//Debug_Trace("staffcode", 1);
			return true;
		}

		// 所属部署コード
		if ($request['wardcode'] != $aryOld['wardcode'])
		{
			//Debug_Trace("wardcode", 1);
			return true;
		}

		// 職種コード
		if ($request['professioncode'] != $aryOld['professioncode'])
		{
			//Debug_Trace("professioncode", 1);
			return true;
		}

		// 役職コード
		if ($request['gradecode'] != $aryOld['gradecode'])
		{
			//Debug_Trace("gradecode", 1);
			return true;
		}

		// 職員カナ名称
		if ($request['kananame'] != $aryOld['kananame'])
		{
			//Debug_Trace("kananame", 1);
			return true;
		}

		// 職員漢字名称
		if ($request['kanjiname'] != $aryOld['kanjiname'])
		{
			//Debug_Trace("kanjiname", 1);
			return true;
		}

		// パスワード←個別対応のためチェックしない


		// 有効開始日
		if ($request['validstartdate'] != $aryOld['validstartdate'])
		{
			//Debug_Trace("validstartdate", 1);
			return true;
		}

		// 有効終了日
		if ($request['validenddate'] != $aryOld['validenddate'])
		{
			//Debug_Trace("validenddate", 1);
			return true;
		}

		// 性別
		if ($request['sex'] != $aryOld['sex'])
		{
			//Debug_Trace("sex", 1);
			return true;
		}

		// 生年月日（和暦に変換）
		if ($request['birth_year'] != $aryOld['birth_year'])
		{
			//Debug_Trace("birth_year", 1);
			return true;
		}
		if ($request['birth_mon'] != $aryOld['birth_mon'])
		{
			//Debug_Trace("birth_mon", 1);
			return true;
		}
		if ($request['birth_day'] != $aryOld['birth_day'])
		{
			//Debug_Trace("birth_day", 1);
			return true;
		}

		// PHS番号
		if ($request['pbno'] != $aryOld['pbno'])
		{
			//Debug_Trace("pbno", 1);
			return true;
		}

		// 所属科コード
		if ($request['deptcode'] != $aryOld['deptcode'])
		{
			//Debug_Trace("deptcode", 1);
			return true;
		}

		// 予約項目コード
		if ($request['appcode'] != $aryOld['appcode'])
		{
			//Debug_Trace("appcode", 1);
			return true;
		}

		// 診療グループコード
		if ($request['deptgroupcode'] != $aryOld['deptgroupcode'])
		{
			//Debug_Trace("deptgroupcode", 1);
			return true;
		}

		return false;
	}

	function relationHis($request, $aryOld="")
	{
		if (!defined("HIS_CSV_DIR"))
		{
			return;
			//define("HIS_CSV_DIR","/var/www/phplib/hiscsv/");
		}

		$user_id = $request['user_id'];

		$aryData = $this->getUserData($user_id);
		// 性別
		$request['sex'] = $aryData['sex'];
		$request['birth_year'] = $aryData['birth_year'];
		$request['birth_mon'] = $aryData['birth_mon'];
		$request['birth_day'] = $aryData['birth_day'];
		$request['pbno'] = $aryData['pbno'];

		if (@$aryOld['staffcode'] != "")
		{
			//if (!$this->isChangeHisData($request, $aryOld))
			//{
				// 変わってなければ処理しない
				//return ;
			//}

			// パスワードは変更画面から変更しないため空白をセット
			$request['password'] = "";
		}

		// 更新日時
		$update_time = date("YmdHis");

		//
		// 職員データを固定長１行データにする
		//

		$strUser = "";

		// 送信日加工
		list($sy, $sm, $sd) = explode("/", $request['send_date']);
		$send_date = sprintf("%04d%02d%02d", $sy, $sm, $sd);
		$strUser .= $send_date;

		// 病院コード01固定
		$strUser .= "01";

		// 職員コード
		$strUser .= str_pad($request['staffcode'], 10);

		// 所属部署コード
		$strUser .= str_pad($request['wardcode'], 5);

		// 職種コード
		$strUser .= str_pad($request['professioncode'], 2);

		// 役職コード
		$strUser .= str_pad($request['gradecode'], 2);

		// 職員カナ名称
		$kananame = str_replace("　", " ", $request['kananame']);
		$kananame = string::zen2han($kananame);
		//$strUser .= str_pad($kananame, 20);
		$strUser .= string::mb_str_pad($kananame, 20);

		// 職員漢字名称
		//$strUser .= str_pad($request['kanjiname'], 20);
		//$kanjiname = str_replace(" ", "　", $request['kanjiname']);
		//$kanjiname = string::han2zen($kanjiname);
		//$strUser .= string::mb_str_pad($kanjiname, 10);
		$strUser .= string::mb_str_pad($request['kanjiname'], 20);

		// パスワード
		$strUser .= str_pad($request['password'], 10);

		// 有効開始日
		list($vsy, $vsm, $vsd) = explode("/", $request['validstartdate']);
		$validstartdate = sprintf("%04d%02d%02d", $vsy, $vsm, $vsd);
		$strUser .= $validstartdate;

		// 有効終了日
		list($vey, $vem, $ved) = explode("/", $request['validenddate']);
		$validenddate = sprintf("%04d%02d%02d", $vey, $vem, $ved);
		$strUser .= $validenddate;

		// 性別
		$strUser .= str_pad($request['sex'], 1);

		// 生年月日（和暦に変換）
		$gengou = "";
		$wadate = "";
		$this->toWareki($request['birth_year'], $request['birth_mon'], $request['birth_day'], &$gengou, &$wadate);

		$strUser .= str_pad($gengou, 1);
		$strUser .= str_pad($wadate, 6);

		// 更新日
		$strUser .= $update_time;

		// 更新端末
		//$strUser .= str_pad("利用者管理", 10);
		$strUser .= string::mb_str_pad("利用者管理", 10);

		// 更新者コード
		$login_staffcode = $this->getSessionData('LOGIN_STAFFCODE');
		$strUser .= str_pad($login_staffcode, 10);

		// PHS番号
		$strUser .= str_pad($request['pbno'], 4);

		// 所属科コード
		$strUser .= str_pad($request['deptcode'], 2);

		// 予約項目コード
		$strUser .= str_pad($request['appcode'], 5);

		// 診療グループコード
		$strUser .= str_pad($request['deptgroupcode'], 2);


		// ファイル名
		$file = "STAFF_" . $request['staffcode'] . "_" . $update_time . ".txt";
		$file_path = HIS_CSV_DIR . $file;

		file_put_contents($file_path, $strUser);
		//exit;



		return;
	}

	function relationHisDelete($staffcode)
	{
		if (!defined("HIS_CSV_DIR"))
		{
			return;
		}
			// ファイル名
		$file = "STAFFDEL_" . $staffcode . ".txt";
		$file_path = HIS_CSV_DIR . $file;

		file_put_contents($file_path, "");
		//exit;



		return;
	}
}

?>
