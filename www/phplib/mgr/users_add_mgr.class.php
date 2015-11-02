<?php
/**********************************************************
* File         : users_add_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/users_regist_common_mgr.class.php");
require_once("sql/users_add_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class users_add_mgr extends users_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function insertUserData(&$request)
	{
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
		$args['RETIRE_FLG'] = $this->sqlItemFlg($request['retire_flg']);
		$args['MAIL_DISUSED_FLG'] = $this->sqlItemFlg($request['mail_disused_flg']);
		$args['GAROON_DISUSED_FLG'] = $this->sqlItemFlg($request['garoon_disused_flg']);
		$args['MLIST_DISUSED_FLG'] = $this->sqlItemFlg($request['mlist_disused_flg']);
		$args['VDI_USER_FLG'] = $this->sqlItemFlg($request['vdi_user_flg']);

		$sql = $this->getQuery('INSERT_USER_MST', $args);
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

				$sql = $this->getQuery('INSERT_SUB_BELONG_CHG', $args);

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
		if (is_array($request['sub_job_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);

			foreach ($request['sub_job_id'] AS $no => $job_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['JOB_ID'] = $this->sqlItemInteger($job_id);

				$sql = $this->getQuery('INSERT_SUB_JOB', $args);

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

				$sql = $this->getQuery('INSERT_SUB_POST', $args);

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
		if ($request['his_flg'] == "1")
		{
			$ret = $this->insertUserHisData($user_id, &$request);

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

				$ret = $this->insertUserHisData($user_id, &$request, $no, $key);

				if (!$ret)
				{
					$this->oDb->rollback();
					return false;
				}

				$no++;
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

		$sql = $this->getQuery('INSERT_USER_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
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
			$this->relationAd($user_id, $request['login_id']);

			//
			// メールアカウント連携
			//
			//if ($request['mail_acc'] != "")
			//{
				$this->relationUserMailAddr('add', $user_id);
			//}
		}

		//
		// HIS連携
		//
		if ($request['his_flg'] == "1")
		{
			$this->relationHis($request);
		}
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

				$this->relationHis($buff);
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

		//
		// ガルーン連携
		//

		// あとで作る
		$garoon_result = "0";

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

		$sql = $this->getQuery('INSERT_USER_ROLE', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function insertUserHisData($user_id, &$request, $no="", $key="")
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

		$sql = $this->getQuery('INSERT_USER_HIS', $args);

		$ret = $this->oDb->query($sql);
		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}
}
?>
