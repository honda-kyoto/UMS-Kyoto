<?php
/**********************************************************
* File         : users_detail_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/users_regist_common_mgr.class.php");
require_once("sql/users_detail_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class users_detail_mgr extends users_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getInitPageData()
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$sql = $this->getQuery('GET_USER_DEFAULT_CTRL', $user_id);

		$aryRet = $this->oDb->getRow($sql);

		return array($aryRet['mode_name'], $aryRet['disp_type']);
	}

	function getUserCtrlList()
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$sql = $this->getQuery('GET_USER_CTRL_LIST', $user_id);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}


	function makeUsersTabMenu($cur_mode_name)
	{
		$aryCtrl = $this->getUserCtrlList();

		$strTag = "";
		if (is_array($aryCtrl))
		{
			foreach ($aryCtrl AS $ctrl_id => $aryVal)
			{
				$ctrl_name = $aryVal['ctrl_name'];
				$mode_name = $aryVal['mode_name'];
				$mode = "view";
				if ($aryVal['disp_type'] == '1')
				{
					$mode = "input";
				}

				$class = "";
				$onclick = "shiftTab('" . $mode . "','" . $mode_name . "');";
				if ($aryVal['mode_name'] == $cur_mode_name)
				{
					$class = 'class="curtab"';
					$onclick = "return;";
				}


				$strTag .= <<< HTML
<li><a href="javascript:;" onclick="$onclick"><span $class>$ctrl_name</span></a></li>

HTML;
			}
		}

		return $strTag;
	}

	function getHisListNo($user_id)
	{
		$sql = $this->getQuery('GET_HIS_LIST_NO', $user_id);

		$aryNo = $this->oDb->getCol($sql);

		return $aryNo;
	}

	function makeHisTabMenu($user_id, $list_no, $mode)
	{
		$aryNo = $this->getHisListNo($user_id);

		if ($list_no == "")
		{
			$list_no = "0";
		}

		$sub_no = 0;
		if (is_array($aryNo))
		{
			foreach ($aryNo AS $no)
			{
				$li_class = "";
				$color = "gray";
				$onclick = "shiftHisTab(" . $no . ");";

				if ($no == $list_no)
				{
					$li_class = 'class="none"';
					$color = "blue";
					$onclick = "return;";
				}

				$name = "サブ" . $sub_no;
				if ($sub_no == "0")
				{
					$name = "メイン";
				}

				$strTag .= <<< HTML
<li $li_class><a href="javascript:;" class="$color" onclick="$onclick">$name</a></li>

HTML;

				$sub_no++;
			}
		}

		if ($mode == "view")
		{
			return $strTag;
		}

		$li_class = "";
		// 追加が選択されている場合
		if ($list_no == "new" || $sub_no == 0)
		{
			$li_class = 'class="none"';
		}

		$strTag .= <<< HTML
<li $li_class><a href="javascript:;" class="yellow" onclick="shiftHisTab('new');">（追加）</a></li>

HTML;

		return $strTag;
	}


	function getKyotoCardListNo($user_id)
	{
		$sql = $this->getQuery('GET_KYOTO_CARD_LIST_NO', $user_id);

		$aryNo = $this->oDb->getCol($sql);

		return $aryNo;
	}

	function makeCardTabMenu($user_id, $list_no, $mode)
	{
		$aryNo = $this->getKyotoCardListNo($user_id);

		if ($list_no == "")
		{
			$list_no = "0";
		}

		$sub_no = 0;
		if (is_array($aryNo))
		{
			foreach ($aryNo AS $no)
			{
				$li_class = "";
				$color = "gray";
				$onclick = "shiftCardTab(" . $no . ");";

				if ($no == $list_no)
				{
					$li_class = 'class="none"';
					$color = "blue";
					$onclick = "return;";
				}

				$name = "サブ" . $sub_no;
				if ($sub_no == "0")
				{
					$name = "メイン";
				}

				$strTag .= <<< HTML
<li $li_class><a href="javascript:;" class="$color" onclick="$onclick">$name</a></li>

HTML;

				$sub_no++;
			}
		}

		if ($mode == "view")
		{
			return $strTag;
		}

		$li_class = "";
		// 追加が選択されている場合
		if ($list_no == "new")
		{
			$li_class = 'class="none"';
		}
		else
		{
			$li_class = "";
		}

		$strTag .= <<< HTML
<li $li_class><a href="javascript:;" class="yellow" onclick="shiftCardTab('new');">（追加）</a></li>

HTML;
		$li_class = "";
		// 予約が選択されている場合
		if ($list_no == "reserve")
		{
			$li_class = 'class="none"';
		}
		$strTag .= <<< HTML
<li $li_class><a href="javascript:;" class="red" onclick="shiftCardTab('reserve');">（予約）</a></li>

HTML;
		return $strTag;
	}

	function getUserBaseData($user_id, $reserve_flg=false)
	{
		$args = array();
		$args[0] = $user_id;
		$args['TBL'] = "mst";
		$args['REFLECT_DATE_COL'] = "";
		$args['REFLECT_DATE_CND'] = "";
		$args['MAIL_ACC'] = "UM.mail_acc";
		if ($reserve_flg)
		{
			$args['TBL'] = "base_reserve";
			$args['REFLECT_DATE_COL'] = ",TO_CHAR(UM.reflect_date, 'YYYY/MM/DD') AS reflect_date";
			$args['REFLECT_DATE_CND'] = "AND complete_flg = '0'";
			$args['MAIL_ACC'] = "(SELECT mail_acc FROM user_mst WHERE user_id = " . $user_id . ") AS mail_acc";
		}

		$sql = $this->getQuery('GET_USER_BASE_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		if ($ret['birthday'] != "")
		{
			list($by, $bm, $bd) = explode("-", $ret['birthday']);
			$ret['birth_year'] = $by;
			$ret['birth_mon'] = (int)$bm;
			$ret['birth_day'] = (int)$bd;
		}

		return $ret;
	}

	function getUserNcvcData($user_id, $reserve_flg=false)
	{
		$args = array();
		$args[0] = $user_id;
		$args['TBL'] = "mst";
		$args['REFLECT_DATE_COL'] = "";
		$args['REFLECT_DATE_CND'] = "";
		if ($reserve_flg)
		{
			$args['TBL'] = "ncvc_reserve";
			$args['REFLECT_DATE_COL'] = ",TO_CHAR(UM.reflect_date, 'YYYY/MM/DD') AS reflect_date";
			$args['REFLECT_DATE_CND'] = "AND complete_flg = '0'";
		}

		$sql = $this->getQuery('GET_USER_NCVC_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		if ($ret['login_passwd'] != "")
		{
			$ret['login_passwd'] = $this->passwordDecrypt($ret['login_passwd']);
		}

		return $ret;

	}

	function getUserMailReissueFlg($user_id)
	{
		$sql = $this->getQuery('GET_MAIL_REISSUE_FLG', $user_id);

		$ret = $this->oDb->getOne($sql);

		return $ret;
	}


	function getUserHisData($user_id, $list_no, $reserve_flg=false)
	{
		if ($list_no == "")
		{
			$list_no = "0";
		}

		$args = array();
		$args[0] = $user_id;
		$args[1] = $list_no;
		$args['TBL'] = "tbl";
		$args['HISTORY_COLUMNS'] = "";
		if ($reserve_flg)
		{
			$args['TBL'] = "reserve";
			$args['HISTORY_COLUMNS'] = ",UHT.history_note,UHT.his_history_kbn";
		}

		$sql = $this->getQuery('GET_USER_HIS_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		if ($ret['password'] != "")
		{
			$ret['password'] = $this->passwordDecrypt($ret['password']);
		}

		return $ret;
	}

	function getHisHistoryList($user_id, $list_no)
	{
		if ($list_no == "")
		{
			$list_no = "0";
		}

		$args = array();
		$args[0] = $user_id;
		$args[1] = $list_no;

		$sql = $this->getQuery('GET_HIS_HISTORY_LIST', $args);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}

	function getUserHisHistoryData($user_id, $list_no, $history_no)
	{
		if ($list_no == "")
		{
			$list_no = "0";
		}

		$args = array();
		$args[0] = $user_id;
		$args[1] = $list_no;
		$args[2] = $history_no;

		$sql = $this->getQuery('GET_USER_HIS_HISTORY_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function existsSalaryExists($user_id)
	{
		$sql = $this->getQuery('EXISTS_SALARY_PASSWD', $user_id);

		$history_no = $this->oDb->getOne($sql);

		if ($history_no != "")
		{
			return true;
		}

		return false;

	}


	function getUserCardData($user_id)
	{
		$sql = $this->getQuery('GET_USER_CARD_DATA', $user_id);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubBelongData($user_id, $reserve_flg=false)
	{
		$args = array();
		$args[0] = $user_id;
		$args['TBL'] = "tbl";
		if ($reserve_flg)
		{
			$args['TBL'] = "reserve";
		}
		$sql = $this->getQuery('GET_SUB_BELONG_DATA', $args);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubJobData($user_id, $reserve_flg=false)
	{
		$args = array();
		$args[0] = $user_id;
		$args['TBL'] = "tbl";
		if ($reserve_flg)
		{
			$args['TBL'] = "reserve";
		}

		$sql = $this->getQuery('GET_SUB_JOB_DATA', $args);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubPostData($user_id, $reserve_flg=false)
	{
		$args = array();
		$args[0] = $user_id;
		$args['TBL'] = "tbl";
		if ($reserve_flg)
		{
			$args['TBL'] = "reserve";
		}

		$sql = $this->getQuery('GET_SUB_POST_DATA', $args);

		$ret = $this->oDb->getAssoc($sql);

		return $ret;
	}

	function getSubStaffIdData($user_id, $reserve_flg=false)
	{
		$args = array();
		$args[0] = $user_id;
		$args['TBL'] = "tbl";
		if ($reserve_flg)
		{
			$args['TBL'] = "reserve";
		}

		$sql = $this->getQuery('GET_SUB_STAFF_ID_DATA', $args);

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

	function getRoleData($user_id, $reserve_flg=false)
	{
		$args = array();
		$args[0] = $user_id;
		$args['TBL'] = "tbl";
		if ($reserve_flg)
		{
			$args['TBL'] = "reserve";
		}

		$sql = $this->getQuery('GET_ROLE_DATA', $args);

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

		}

		return $ret;
	}

	function makeHistoryData($user_id, $mode="")
	{
		//
		// 履歴レコードを作成
		//

		// 履歴番号取得（ロック）
		$sql = $this->getQuery('HISTORY_LOCK', $user_id);
		$this->oDb->query($sql);

		$sql = $this->getQuery('GET_HISTORY_NO', $user_id);

		$history_no = $this->oDb->getOne($sql);

		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;
		$args[2] = $mode;

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

		// サブ職員番号
		$sql = $this->getQuery('MAKE_HISTORY_SUB_STAFF_ID', $args);

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

	function updateUserBaseData($request)
	{
		// ファイルサーバ使用登録用配列
		$aryNewChgId = array();
		$aryDelChgId = array();
		$aryNewChgId[] = $request['belong_chg_id'];


		// ユーザID
		$user_id = $request['user_id'];

		// チェック用に現状を取得
		$aryOld = $this->getUserBaseData($user_id);
		$aryOldJob = $this->getSubJobData($user_id);
		$aryOldPost = $this->getSubPostData($user_id);
		$aryOldBelong = $this->getSubBelongData($user_id);

		$this->oDb->begin();

		// 一括削除用のパラメータ
		$delArgs = $this->getSqlArgs();
		$delArgs['USER_ID'] = $this->sqlItemInteger($user_id);

		$delArgs['TAB'] = "tbl";

		if ($request['reserve_flg'] == '1')
		{
			$delArgs['TAB'] = "reserve";
		}
		else
		{
			// 履歴作成
			$ret = $this->makeHistoryData($user_id, 'base');

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		// データ加工
		$birthday = "";
		if ($request['birth_year'] != "" && $request['birth_mon'] != "" && $request['birth_day'] != "")
		{
			$birthday = sprintf("%04d-%02d-%02d", $request['birth_year'], $request['birth_mon'], $request['birth_day']);
		}

		if (@$request['retire_date'] != "")
		{
			list ($retire_y, $retire_m, $retire_d) = explode("/", $request['retire_date']);
			$retire_date = sprintf("%04d-%02d-%02d", $retire_y, $retire_m, $retire_d);
		}


		// 基本情報
		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['STAFF_ID'] = $this->sqlItemChar($request['staff_id']);
		$args['STAFF_ID_FLG'] = $this->sqlItemFlg($request['staff_id_flg']);
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
		$args['BELONG_CHG_ID'] = $this->sqlItemInteger($request['belong_chg_id']);
		$args['JOB_ID'] = $this->sqlItemInteger($request['job_id']);
		$args['POST_ID'] = $this->sqlItemInteger($request['post_id']);
		$args['NAISEN'] = $this->sqlItemChar($request['naisen']);
		$args['PBNO'] = $this->sqlItemChar($request['pbno']);
		$args['JOUKIN_KBN'] = $this->sqlItemChar($request['joukin_kbn']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['RETIRE_DATE'] = $this->sqlItemChar(@$retire_date);

		$args['TAB'] = "mst";
		$args['REFLECT_DATE'] = "";
		$args['REFLECT_DATE_COL'] = "";
		$sql_id = 'UPDATE_USER_BASE_DATA';

		if ($request['reserve_flg'] == '1')
		{
			$args['TAB'] = "base_reserve";
			list ($reflect_y, $reflect_m, $reflect_d) = explode("/", $request['reflect_date']);
			$reflect_date = sprintf("%04d-%02d-%02d", $reflect_y, $reflect_m, $reflect_d);
			$args['REFLECT_DATE'] = $this->sqlItemChar($reflect_date);
			$args['REFLECT_DATE_COL'] = ",reflect_date = " . $args['REFLECT_DATE'] . ",complete_flg = '0'";

			$sql = $this->getQuery('EXISTS_USER_BASE_RESERVE', $user_id);

			$ext_id = $this->oDb->getOne($sql);

			if ($ext_id == "")
			{
				$sql_id = 'INSERT_USER_BASE_RESERVE';
			}
		}

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// サブ所属
		// 一旦論理削除
		$sql = $this->getQuery('DELETE_USER_SUB_BELONG_CHG', $delArgs);

		$ret = $this->oDb->query($sql);

		if (is_array($request['sub_belong_chg_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);
			$args['TAB'] = "tbl";

			if ($request['reserve_flg'] == '1')
			{
				$args['TAB'] = "reserve";
			}

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

				// 配列に追加
				if (!in_array($belong_chg_id, $aryNewChgId))
				{
					$aryNewChgId[] = $belong_chg_id;
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
			$args['TAB'] = "tbl";

			if ($request['reserve_flg'] == '1')
			{
				$args['TAB'] = "reserve";
			}

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
			$args['TAB'] = "tbl";

			if ($request['reserve_flg'] == '1')
			{
				$args['TAB'] = "reserve";
			}

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

		// サブ職員番号
		// 一旦論理削除
		$sql = $this->getQuery('DELETE_USER_SUB_STAFF_ID', $delArgs);

		$ret = $this->oDb->query($sql);

		if (is_array($request['sub_staff_id']))
		{
			$args = $this->getSqlArgs();
			$args['USER_ID'] = $this->sqlItemInteger($user_id);
			$args['TAB'] = "tbl";

			if ($request['reserve_flg'] == '1')
			{
				$args['TAB'] = "reserve";
			}

			foreach ($request['sub_staff_id'] AS $no => $staff_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['STAFF_ID'] = $this->sqlItemChar($staff_id);

				// 存在チェック
				$sql = $this->getQuery('EXISTS_SUB_STAFF_ID', $args);

				$list_no = $this->oDb->getOne($sql);

				if ($list_no == "")
				{
					$sql_id = "INSERT_SUB_STAFF_ID";
				}
				else
				{
					$sql_id = "UPDATE_SUB_STAFF_ID";
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


		// 更新ログ作成
		if ($request['ctrl_mode_name'] != "")
		{
			// モードがない場合は予約反映なのでログを残さない
			$ret = $this->makeEditLog($user_id, LOG_KBN_EDIT, $request['ctrl_mode_name'], $request['reserve_flg'], $request['reflect_date']);

			if (!$ret)
			{
				Debug_Print("更新ログ作成失敗");
				$this->oDb->rollback();
				return false;
			}
		}

		// 一旦トランザクション終了
		$this->oDb->end();

		// 予約ならここで終わり
		if ($request['reserve_flg'] == '1')
		{
			return true;
		}

		//
		// 所属に変更があるかチェック
		//
		$key = array_search($aryOld['belong_chg_id'], $aryNewChgId);
		if ($key !== false)
		{
			unset($aryNewChgId[$key]);
		}
		else
		{
			$aryDelChgId[] = $aryOld['belong_chg_id'];
		}
		if (is_array($aryOldBelong))
		{
			foreach ($aryOldBelong AS $b_list_no => $data)
			{
				$old_belong_chg_id = $data['sub_belong_chg_id'];

				if ($old_belong_chg_id == "")
				{
					continue;
				}

				$key = array_search($old_belong_chg_id, $aryNewChgId);

				if ($key !== false)
				{
					unset($aryNewChgId[$key]);
				}
				else
				{
					// 配列に追加
					if (!in_array($old_belong_chg_id, $aryDelChgId))
					{
						$aryDelChgId[] = $old_belong_chg_id;
					}
				}
			}
		}

		//
		// AD連携
		//
		if ($aryOld['kanjisei'] != $request['kanjisei'] || $aryOld['kanjimei'] != $request['kanjimei'] || $aryOld['job_id'] != $request['job_id'] || $aryOld['post_id'] != $request['post_id']
				|| $aryOld['naisen'] != $request['naisen'] || $aryOld['pbno'] != $request['pbno'] || $aryOld['belong_chg_id'] != $request['belong_chg_id']
				|| count($aryNewChgId) > 0 || count($aryDelChgId) > 0)
		{
			// 比較対象にNCVCデータを渡す
			$aryNCVC = $this->getUserNcvcData($user_id);
			$aryNCVC['del_belong_chg_id'] = $aryDelChgId;

			$ret = $this->relationAd($user_id, $aryNewChgId, $aryNCVC);

			if (!$ret)
			{
				// エラーログ
				$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'AD');
			}
		}

		//
		// HIS連携（該当項目が変わった場合のみ：関数側でチェック）
		//
		$sql = $this->getQuery('GET_HIS_VALID_LIST_NO', $user_id);
		$aryHisIds = $this->oDb->getCol($sql);

		if (is_array($aryHisIds))
		{
			foreach ($aryHisIds AS $v_list_no)
			{
				$aryHis = $this->getUserHisData($user_id, $v_list_no);

				if ($aryHis['staffcode'] != "")
				{
					$aryNewHis = $aryHis;
					$aryNewHis['user_id'] = $user_id;

					$aryHis['sex'] = $aryOld['sex'];
					$aryHis['birth_year'] = $aryOld['birth_year'];
					$aryHis['birth_mon'] = $aryOld['birth_mon'];
					$aryHis['birth_day'] = $aryOld['birth_day'];
					$aryHis['pbno'] = $aryOld['pbno'];

					//$this->relationHis($aryNewHis, $aryHis);
				}
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


		return true;
	}

	function deleteBaseReserve($user_id)
	{
		$args = $this->getSqlArgs();
		$args[0] = $user_id;

		$sql = $this->getQuery('DELETE_USER_BASE_RESERVE', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function deleteNcvcReserve($user_id)
	{
		$args = $this->getSqlArgs();
		$args[0] = $user_id;

		$sql = $this->getQuery('DELETE_USER_NCVC_RESERVE', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function updateUserNcvcData($request)
	{
		// ユーザID
		$user_id = $request['user_id'];

		// チェック用に現状を取得
		$aryOld = $this->getUserNcvcData($user_id);

		$this->oDb->begin();

		// 一括削除用のパラメータ
		$delArgs = $this->getSqlArgs();
		$delArgs['USER_ID'] = $this->sqlItemInteger($user_id);

		$delArgs['TAB'] = "tbl";

		if ($request['reserve_flg'] == '1')
		{
			$delArgs['TAB'] = "reserve";
		}
		else
		{
			// 履歴作成
			$ret = $this->makeHistoryData($user_id, 'ncvc');

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		list ($start_y, $start_m, $start_d) = explode("/", $request['start_date']);
		$start_date = sprintf("%04d-%02d-%02d", $start_y, $start_m, $start_d);
		$end_date = "";
		if ($request['end_date'] != "")
		{
			list ($end_y, $end_m, $end_d) = explode("/", $request['end_date']);
			$end_date = sprintf("%04d-%02d-%02d", $end_y, $end_m, $end_d);
		}

		$pwd = "login_passwd";
		$login_passwd_changed = false;
		if ($request['login_passwd'] != "")
		{
			if ($request['login_passwd'] != $aryOld['login_passwd'])
			{
				$login_passwd = $this->passwordEncrypt($request['login_passwd']);
				$pwd = $this->sqlItemChar($login_passwd);

				$login_passwd_changed = true;
			}
		}
		else if ($request['reserve_flg'] == '1')
		{
			$login_passwd = $this->passwordEncrypt($aryOld['login_passwd']);
			$pwd = $this->sqlItemChar($login_passwd);
		}

		if ($request['ftrans_user_flg'] != "1")
		{
			$request['ftrans_user_kbn'] = "0";
		}

		// 基本情報
		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['EIJISEI'] = $this->sqlItemChar($request['eijisei']);
		$args['EIJIMEI'] = $this->sqlItemChar($request['eijimei']);
		$args['LOGIN_ID'] = $this->sqlItemChar($request['login_id']);
		$args['LOGIN_PASSWD'] = $pwd;
		$args['MAIL_ACC'] = $this->sqlItemChar($request['mail_acc']);
		$args['START_DATE'] = $this->sqlItemChar($start_date);
		$args['END_DATE'] = $this->sqlItemChar($end_date);
		$args['MAIL_DISUSED_FLG'] = $this->sqlItemFlg($request['mail_disused_flg']);
		$args['GAROON_DISUSED_FLG'] = $this->sqlItemFlg($request['garoon_disused_flg']);
		$args['MLIST_DISUSED_FLG'] = $this->sqlItemFlg($request['mlist_disused_flg']);
		$args['VDI_USER_FLG'] = $this->sqlItemFlg($request['vdi_user_flg']);
		$args['FTRANS_USER_FLG'] = $this->sqlItemFlg($request['ftrans_user_flg']);
		$args['FTRANS_USER_KBN'] = $this->sqlItemChar($request['ftrans_user_kbn']);

		$args['TAB'] = "mst";
		$args['REFLECT_DATE'] = "";
		$args['REFLECT_DATE_COL'] = "";

		$sql_id = 'UPDATE_USER_NCVC_DATA';

		if ($request['reserve_flg'] == '1')
		{
			$args['TAB'] = "ncvc_reserve";
			list ($reflect_y, $reflect_m, $reflect_d) = explode("/", $request['reflect_date']);
			$reflect_date = sprintf("%04d-%02d-%02d", $reflect_y, $reflect_m, $reflect_d);
			$args['REFLECT_DATE'] = $this->sqlItemChar($reflect_date);
			$args['REFLECT_DATE_COL'] = ",reflect_date = " . $args['REFLECT_DATE'] . ",complete_flg = '0'";

			$sql = $this->getQuery('EXISTS_USER_NCVC_RESERVE', $user_id);

			$ext_id = $this->oDb->getOne($sql);

			if ($ext_id == "")
			{
				$sql_id = 'INSERT_USER_NCVC_RESERVE';
			}
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
		$ret = $this->updateUserRoleData($user_id, $request['user_type_id'], $request['reserve_flg']);

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

				$ret = $this->updateUserRoleData($user_id, $role_id, $request['reserve_flg']);

				if (!$ret)
				{
					$this->oDb->rollback();
					return false;
				}
			}
		}

		// 更新ログ作成
		if ($request['ctrl_mode_name'] != "")
		{
			// モードがない場合は予約反映なのでログを残さない
			$ret = $this->makeEditLog($user_id, LOG_KBN_EDIT, $request['ctrl_mode_name'], $request['reserve_flg'], $request['reflect_date']);

			if (!$ret)
			{
				Debug_Print("更新ログ作成失敗");
				$this->oDb->rollback();
				return false;
			}
		}

		// 予約の場合ここで終わり
		if ($request['reserve_flg'] == '1')
		{
			$this->oDb->end();
			return true;
		}

		// エントリーテーブルを更新
		if ($request['vdi_user_flg'] == "")
		{
			$request['vdi_user_flg'] = "0";
		}
		$ret = $this->updateUserEntryFlg($user_id, 'vdi_user_flg', $request['vdi_user_flg']);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
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

		$has_mail_err = false;
		if ($aryOld['login_id'] != $request['login_id'])
		{
			// 統合IDが変わった場合

			// 削除
			$ret = $this->delUserMailAddr($aryOld['login_id']);
			if (!$ret)
			{
				$has_mail_err = true;
			}

			$aryTmp = $request;

			// 新たに登録するべきデータがあれば追加
			if ($request['login_id'] != "")
			{
				$ret = $this->relationUserMailAddr('add', $user_id);
				if (!$ret)
				{
					$has_mail_err = true;
				}
			}
		}
		else if ($aryOld['mail_acc'] != $request['mail_acc'])
		{
			// メールアドレスのみ変わった場合
			if ($request['mail_acc'] == "")
			{
				// アカウントの廃止
				$ret = $this->delUserMailAddr($request['login_id']);
				if (!$ret)
				{
					$has_mail_err = true;
				}
			}
			else if ($aryOld['mail_acc'] == "")
			{
				$ret = $this->relationUserMailAddr('add', $user_id);
				if (!$ret)
				{
					$has_mail_err = true;
				}
			}
			else
			{
				// 更新
				$ret = $this->relationUserMailAddr('edit', $user_id);
				if (!$ret)
				{
					$has_mail_err = true;
				}
			}
		}
		else if ($login_passwd_changed)
		{
			// パスワードのみ変わった場合
			// 更新
			$ret = $this->relationUserMailAddr('edit', $user_id);
			if (!$ret)
			{
				$has_mail_err = true;
			}
		}

		if ($has_mail_err)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
		}

		//
		// AD連携
		//
		if ($request['ftrans_user_flg'] == "")
		{
			$request['ftrans_user_flg'] = "0";
		}
		if ($request['ftrans_user_kbn'] == "")
		{
			$request['ftrans_user_kbn'] = "0";
		}

		if ($aryOld['login_id'] != $request['login_id'] || $aryOld['mail_acc'] != $request['mail_acc'] || $aryOld['vdi_user_flg'] != $request['vdi_user_flg'] || $login_passwd_changed
				 || $aryOld['ftrans_user_flg'] != $request['ftrans_user_flg'] || $aryOld['ftrans_user_kbn'] != $request['ftrans_user_kbn'])
		{
			$aryNewChgId = array();
			$aryDelChgId = array();
			if ($aryOld['login_id'] != $request['login_id'])
			{
				// 統合IDが変わった場合のみ
				$aryBase = $this->getUserBaseData($user_id);
				$arySubBelong = $this->getSubBelongData($user_id);

				$aryNewChgId[] = $aryBase['belong_chg_id'];
				if (is_array($arySubBelong))
				{
					foreach ($arySubBelong AS $data)
					{
						// 配列に追加
						if (!in_array($data['sub_belong_chg_id'], $aryNewChgId))
						{
							$aryNewChgId[] = $data['sub_belong_chg_id'];
						}
					}
				}
			}

			$ret = $this->relationAd($user_id, $aryNewChgId, $aryOld);

			if (!$ret)
			{
				// エラーログ
				$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'AD');
			}
		}

		return true;
	}

	function updateSendonType($user_id, $sendon_type)
	{
		if ($sendon_type == "")
		{
			$sendon_type = "0";
		}
		return $this->setSendonType($user_id, $sendon_type);
	}

	function existsSendonAddr($sendon_addr, $user_id)
	{
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

	function existsCardNo($card_no)
	{
		$args = array();
		$args[0] = $card_no;

		$sql = $this->getQuery('EXISTS_CARD_NO', $args);

		$no = $this->oDb->getOne($sql);

		if ($no != "")
		{
			return true;
		}

		return false;

	}

	function setSendonType($user_id, $sendon_type="")
	{
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
			return false;
		}

		$ret = $this->relationUserMailAddr('edit', $user_id);
		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
		}

		return true;
	}

	function insertSendonAddr($user_id, $sendon_addr)
	{
		$this->oDb->begin();

		// ヘッダがなければ作る
		$ret = $this->setSendonType($user_id);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args['SENDON_ADDR'] = $this->sqlItemChar($sendon_addr);

		$sql = $this->getQuery('INSERT_SENDON_ADDR', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();


		$ret = $this->relationUserMailAddr('edit', $user_id);
		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
		}

		return true;
	}

	function deleteSendonAddr($user_id, $list_no)
	{
		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $list_no;

		$sql = $this->getQuery('DELETE_SENDON_ADDR', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		$ret = $this->relationUserMailAddr('edit', $user_id);
		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
		}

		return true;
	}

	function existsOldmailAddr($oldmail_addr, $user_id)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $oldmail_addr;

		$sql = $this->getQuery('EXISTS_OLDMAIL_ADDR', $args);

		$no = $this->oDb->getOne($sql);

		if ($no != "")
		{
			return true;
		}

		return false;

	}

	function insertOldmailAddr($user_id, $oldmail_addr)
	{
		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args['OLDMAIL_ADDR'] = $this->sqlItemChar($oldmail_addr);

		$sql = $this->getQuery('INSERT_OLDMAIL_ADDR', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$ret = $this->relationUserMailAddr('edit', $user_id);
		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
		}

		return true;
	}

	function deleteOldmailAddr($user_id, $list_no)
	{
		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $list_no;

		$sql = $this->getQuery('DELETE_OLDMAIL_ADDR', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		$ret = $this->relationUserMailAddr('edit', $user_id);
		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
		}

		return true;
	}

	function updateUserMailReissueFlg($request)
	{
		$user_id = $request['user_id'];
		$mail_reissue_flg = $request['mail_reissue_flg'];

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args['MAIL_REISSUE_FLG'] = $this->sqlItemFlg($mail_reissue_flg);

		$sql = $this->getQuery('UPDATE_MAIL_REISSUE_FLG', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function getInvalidAccData($user_id, $is_all=false)
	{
		$args = array();
		$args[0] = $user_id;
		$args['COND'] = "";
		if (!$is_all)
		{
			$args['COND'] = " AND del_flg = '0'";
		}

		$sql = $this->getQuery('GET_INVALID_ACC_DATA', $args);

		$aryRet = $this->oDb->getRow($sql);

		return $aryRet;
	}

	function setMailAccValid($user_id)
	{
		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$sql = $this->getQuery('DELETE_INVALID_ACC', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		// アカウントを作り直す
		$ret = $this->relationUserMailAddr('add', $user_id);
		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
		}

		//
		// ADのRDNを移動
		//
		$ret = $this->ldapRename($user_id, false);

		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'AD');
		}

		return true;

	}

	function setMailAccInvalid($user_id)
	{
		// 存在チェック
		$aryTmp = $this->getInvalidAccData($user_id, true);

		if ($aryTmp['invalid_flg'] != "")
		{
			$sql_id = 'UPDATE_INVALID_ACC_FLG';
		}
		else
		{
			$sql_id = 'INSERT_INVALID_ACC_FLG';
		}

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		// アカウントを削除
		$aryUser = $this->getUserData($user_id);

		// 削除
		$ret = $this->delUserMailAddr($aryUser['login_id']);

		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'MAIL');
		}

		//
		// ADのRDNを移動
		//
		$ret = $this->ldapRename($user_id, true);

		if (!$ret)
		{
			// エラーログ
			$this->makeRelationErrLog($user_id, __CLASS__, __FUNCTION__, 'AD');
		}

		return true;

	}

	function setMailAccExcCmt($user_id, $exception_note)
	{
		// 存在チェック
		$aryTmp = $this->getInvalidAccData($user_id, true);

		if ($aryTmp['invalid_flg'] != "")
		{
			$sql_id = 'UPDATE_INVALID_ACC_EXC';
		}
		else
		{
			$sql_id = 'INSERT_INVALID_ACC_EXC';
		}

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $exception_note;
		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			return false;
		}

		return true;

	}

	function ldapRename($user_id, $to_invalid=true)
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

		//
		// ldap_renameを使うためにバージョンを指定
		//
		if (ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3))
		{
		}
		else
		{
			Debug_Trace("プロトコルバージョンを 3 に設定できませんでした", 1);
		}

		$ldap_bind  = ldap_bind($ldap_conn, LDAP_DN, LDAP_PASS);

		if (!$ldap_bind)
		{
			Debug_Trace("バインド失敗", 1);
			return false;
		}

		// 統合IDを取得
		$aryData = $this->getUserData($user_id);
		$login_id = $aryData['login_id'];
		$rdn = "CN=".$login_id;

		if ($to_invalid)
		{
			// 無効にする場合
			$oldParent = LOGINID_DN;
			$newParent = LOGINID_DN_DISABLE;
			$msg = "無効化";
		}
		else
		{
			// 有効にする場合
			$oldParent = LOGINID_DN_DISABLE;
			$newParent = LOGINID_DN;
			$msg = "有効化";
		}

		$old_dn = $rdn.",".$oldParent;

		if (ldap_rename ( $ldap_conn, $old_dn, $rdn, $newParent, true ))
		{
			Debug_Trace("ADへの".$msg."は成功しました", 129);
			return true;
		}
		else
		{
			Debug_Trace("ADへの".$msg."は失敗しました", 129);
			return false;
		}
	}

	function changeHisMainData($user_id, $list_no)
	{
		$aryTabs = array(
				'user_his_history',
				'user_his_history_list',
				'user_his_reserve',
				'user_his_tbl',
				);

		$this->oDb->begin();

		$args = array();
		$args[0] = $user_id;

		foreach ($aryTabs AS $tab_name)
		{
			// 該当のデータのlist_noをマイナス値に
			$temp_list_no = $list_no * -1;

			$args[1] = $list_no;
			$args[2] = $temp_list_no;
			$args['TABLE'] = $tab_name;

			$sql = $this->getQuery('UPDATE_HIS_LIST_NO', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				$this->oDb->rollback();
				return false;
			}

			// 該当データまでのlist_noを＋１
			for ($i = $list_no - 1 ; $i >= 0 ; $i--)
			{
				$args[1] = $i;
				$args[2] = $i + 1;
				$sql = $this->getQuery('UPDATE_HIS_LIST_NO', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}

			}

			// マイナス値にしたデータのlist_noを0に
			$args[1] = $temp_list_no;
			$args[2] = 0;

			$sql = $this->getQuery('UPDATE_HIS_LIST_NO', $args);

			$ret = $this->oDb->query($sql);
			if (!$ret)
			{
				Debug_Print($sql);
				$this->oDb->rollback();
				return false;
			}
		}

		$this->oDb->end();

		return true;
	}

	function updateUserHisHistoryData($request)
	{
		// ユーザID
		$user_id = $request['user_id'];
		$list_no = $request['list_no'];
		$history_no = $request['history_no'];

		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['LIST_NO'] = $this->sqlItemInteger($list_no);
		$args['HISTORY_NO'] = $this->sqlItemInteger($history_no);
		$args['HISTORY_NOTE'] = $this->sqlItemChar($request['history_note']);
		$args['HIS_HISTORY_KBN'] = $this->sqlItemChar($request['his_history_kbn']);

		$sql = $this->getQuery('UPDATE_USER_HIS_HISTORY_DATA', $args);

		$ret = $this->oDb->query($sql);
		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		return true;
	}

	function retireHisData($request)
	{
		// ユーザID
		$user_id = $request['user_id'];
		$list_no = $request['list_no'];

		$args = $this->getSqlArgs();
		$args[0] = $this->sqlItemInteger($request['user_id']);
		$args[1] = $this->sqlItemInteger($request['list_no']);
		$args['VALIDENDDATE'] = $this->sqlItemChar($request['retire_date']);

		$sql = $this->getQuery('SET_HIS_RETIRE_DATE', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;

	}

	function updateUserHisData(&$request)
	{
		// ユーザID
		$user_id = $request['user_id'];
		$list_no = $request['list_no'];

		$this->oDb->begin();

		$is_new_data = false;

		// 新規の場合
		if ($list_no == "new")
		{
			// 履歴番号取得（ロック）
			$sql = $this->getQuery('USER_HIS_LOCK', $user_id);
			$this->oDb->query($sql);

			$sql = $this->getQuery('GET_USER_HIS_LIST_NO', $user_id);

			$list_no = $this->oDb->getOne($sql);

			$is_new_data = true;
		}
		else
		{
			// 比較のため古いデータを取っておく
			$aryOld = $this->getUserHisData($user_id, $list_no);
			$aryTemp = $this->getUserData($user_id);
			$aryOld['sex'] = $aryTemp['sex'];
			$aryOld['birth_year'] = $aryTemp['birth_year'];
			$aryOld['birth_mon'] = $aryTemp['birth_mon'];
			$aryOld['birth_day'] = $aryTemp['birth_day'];
			$aryOld['pbno'] = $aryTemp['pbno'];
		}

		if ($request['copy_main_passwd'] == "1" && $list_no > 0)
		{
			$aryMain = $this->getUserHisData($user_id, '0');
			$request['password'] = $aryMain['password'];
		}

		$password = $this->passwordEncrypt($request['password']);
		list ($start_y, $start_m, $start_d) = explode("/", $request['validstartdate']);
		$validstartdate = sprintf("%04d-%02d-%02d", $start_y, $start_m, $start_d);
		list ($end_y, $end_m, $end_d) = explode("/", $request['validenddate']);
		$validenddate = sprintf("%04d-%02d-%02d", $end_y, $end_m, $end_d);
		if ($request['immediate_flg'] == "1")
		{
			$request['send_date'] = date("Y/m/d");
		}
		list ($send_y, $send_m, $send_d) = explode("/", $request['send_date']);
		$send_date = sprintf("%04d-%02d-%02d", $send_y, $send_m, $send_d);

		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['LIST_NO'] = $this->sqlItemInteger($list_no);
		$args['STAFFCODE'] = $this->sqlItemChar($request['staffcode']);
		$args['WARDCODE'] = $this->sqlItemChar($request['wardcode']);
		$args['PROFESSIONCODE'] = $this->sqlItemChar($request['professioncode']);
		$args['GRADECODE'] = $this->sqlItemChar($request['gradecode']);
		$args['KANANAME'] = $this->sqlItemChar($request['kananame']);
		$args['KANJINAME'] = $this->sqlItemChar($request['kanjiname']);
		$args['PASSWORD'] = $this->sqlItemChar($password);
		$args['VALIDSTARTDATE'] = $this->sqlItemChar($validstartdate);
		$args['VALIDENDDATE'] = $this->sqlItemChar($validenddate);
		$args['DEPTCODE'] = $this->sqlItemChar($request['deptcode']);
		$args['APPCODE'] = $this->sqlItemChar($request['appcode']);
		$args['DEPTGROUPCODE'] = $this->sqlItemChar($request['deptgroupcode']);
		$args['SEND_DATE'] = $this->sqlItemChar($send_date);
		$args['HISTORY_NOTE'] = $this->sqlItemChar($request['history_note']);
		$args['HIS_HISTORY_KBN'] = $this->sqlItemChar($request['his_history_kbn']);

		$args['TBL'] = "tbl";

		// 明日以降なら予約テーブルへ
		$is_reserve_data = false;
		if (strtotime(date('Y-m-d')) < strtotime($send_date))
		{
			$args['TBL'] = "reserve";
			$is_reserve_data = true;
		}

		// 予約データ反映の場合
		if ($request['make_id'] != "")
		{
			$args['MAKE_ID'] = $this->sqlItemInteger($request['make_id']);
			$args['UPDATE_ID'] = $this->sqlItemInteger($request['update_id']);
		}

		// 存在チェック
		$sql = $this->getQuery('EXISTS_USER_HIS', $args);

		$no = $this->oDb->getOne($sql);

		if ($no == "")
		{
			$sql_id = "INSERT_USER_HIS_" . strtoupper($args['TBL']);
		}
		else
		{
			$sql_id = "UPDATE_USER_HIS_" . strtoupper($args['TBL']);
		}


		// 予約でない場合は履歴を作成
		if (!$is_reserve_data && !$is_new_data)
		{
			$ret = $this->makeHisHistoryData($request);

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}


		// 更新クエリ
		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);
		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// 履歴作成メニューの場合
		if ($request['edit_mode'] != "" && $request['edit_mode'] != "reserve")
		{
			// 元データがあるかチェック
			$args[0] = $user_id;
			$args[1] = $list_no;
			$sql = $this->getQuery('EXISTS_HIS_HISTORY_BASE', $args);

			$orgno = $this->oDb->getOne($sql);

		}

		// 履歴作成
		if ($request['immediate_flg'] == "1")
		{
			$ret = $this->makeHistoryData($user_id, 'his');

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		// 一旦トランザクション終了
		$this->oDb->end();

		if ($request['immediate_flg'] == "1")
		{
			$this->relationHis($request, $aryOld);
		}

		$request['list_no'] = $list_no;
		return true;
	}

	function makeHisHistoryData($request)
	{
		$aryOld = $this->getUserHisData($request['user_id'], $request['list_no']);

		// 指定項目が変わっていれば有効期限を差し替える
		$validenddate = 'validenddate';
		if ($aryOld['wardcode'] != $request['wardcode']
				|| $aryOld['professioncode'] != $request['professioncode']
				|| $aryOld['deptcode'] != $request['deptcode']
				|| $aryOld['deptgroupcode'] != $request['deptgroupcode']
				|| $aryOld['validstartdate'] != $request['validstartdate'])
		{
			$validenddate = $this->sqlItemChar(date("Y-m-d"));
		}


		$args = $this->getSqlArgs();
		$args[0] = $this->sqlItemInteger($request['user_id']);
		$args[1] = $this->sqlItemInteger($request['list_no']);
		$args['VALIDENDDATE'] = $validenddate;
		$args['HIS_HISTORY_KBN'] = $this->sqlItemChar($request['his_history_kbn']);
		$args['HISTORY_NOTE'] = $this->sqlItemChar($request['history_note']);

		$sql = $this->getQuery('INSERT_HIS_HISTORY_LIST', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;

	}

	function deleteHisReserve($user_id, $list_no)
	{
		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $list_no;

		$sql = $this->getQuery('DELETE_USER_HIS_RESERVE', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function updateSalaryPasswd($request)
	{
		$salary_passwd = $this->passwordEncrypt($request['salary_passwd']);

		$args = $this->getSqlArgs();
		$args[0] = $request['user_id'];
		$args[1] = $salary_passwd;

		$sql = $this->getQuery("INSERT_USER_SALARY_PASSWD", $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

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

	function updateUserRoleData($user_id, $role_id, $reserve_flg="")
	{
		$args = $this->getSqlArgs();
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		$args['ROLE_ID'] = $this->sqlItemInteger($role_id);
		$args['TAB'] = "tbl";

		if ($reserve_flg == '1')
		{
			$args['TAB'] = "reserve";
		}

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


	function reissuePassword($list_no, $user_id, $passwd="")
	{
/*
		// 比較のため古いデータを取っておく
		$aryOld = $this->getUserHisData($user_id);
		$aryTemp = $this->getUserData($user_id);
		$aryOld['sex'] = $aryTemp['sex'];
		$aryOld['birth_year'] = $aryTemp['birth_year'];
		$aryOld['birth_mon'] = $aryTemp['birth_mon'];
		$aryOld['birth_day'] = $aryTemp['birth_day'];
		$aryOld['pbno'] = $aryTemp['pbno'];
*/
		$this->oDb->begin();

		// 履歴作成
		$ret = $this->makeHistoryData($user_id, 'password');

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		if ($passwd == "" && $list_no > 0)
		{
			$aryMain = $this->getUserHisData($user_id, '0');
			$passwd = $aryMain['password'];
		}

		$pwd = $this->passwordEncrypt($passwd);

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $pwd;
		$args[2] = $list_no;

		$sql = $this->getQuery('UPDATE_HIS_PASSWORD', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return "0";
		}

		$this->oDb->end();

		// 登録データ取得
		$aryData = $this->getUserHisData($user_id, $list_no);

		$aryData['user_id'] = $user_id;

		//$this->relationHis($aryData, $aryOld);
		$this->relationHis($aryData);

		$_SESSION['password_user_id'] = $user_id;
		$_SESSION['password_col_name'] = 'password';
		$_SESSION['password_list_no'] = $list_no;

		return "1";
	}

	function addUserCardData($request)
	{
		$this->oDb->begin();

		$args = $this->getSqlArgs();
		$args[0] = $request['user_id'];
		$args[1] = $request['list_no'];
		$args[2] = $request['ident_code'];

		$sql = $this->getQuery('INSERT_USER_CARD_HEAD', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 初回発行の履歴作成
		$sql = $this->getQuery('INSERT_USER_CARD_LIST_FIRST', $args);

		$ret = $this->oDb->query($sql);


		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;
	}

	function reissueUserCardData($request)
	{
		$this->oDb->begin();

		$args = $this->getSqlArgs();
		$args[0] = $request['user_id'];
		$args[1] = $request['list_no'];

		$sql = $this->getQuery('UPDATE_USER_CARD_ISSUE_CNT', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$args[2] = $request['reissue_kbn'][$request['list_no']];
		$sql = $this->getQuery('INSERT_USER_CARD_LIST_REISSUE', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;

	}

	function stopUserCardData($request)
	{
		$args = $this->getSqlArgs();
		$args[0] = $request['user_id'];
		$args[1] = $request['list_no'];
		$args[2] = $request['disuse_kbn'][$request['list_no']];

		$sql = $this->getQuery('UPDATE_USER_CARD_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		return true;

	}


	function getKyotoCardData($user_id, $list_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $list_no;
		$sql = $this->getQuery("GET_KYOTO_CARD_DATA", $args);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function getKyotoCardHistory($user_id, $history_no)
	{
		$args = array();
		$args[0] = $user_id;
		$args[1] = $history_no;

		$sql = $this->getQuery("GET_KYOTO_CARD_HISTORY", $args);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function updateKyotoCard(&$request)
	{
		$user_id = $request['user_id'];
		$list_no = $request['list_no'];

		$this->oDb->begin();

		if ($list_no == "new")
		{
			//ログ出力
			file_put_contents("/var/www/phplib/card/log/nyutaishitsu.log",date("Y/m/d H:i:s")."  ID：".$_SESSION['LOGIN_USER_ID']."  新規\n", FILE_APPEND);	

			// 新規
			$sql_id = "INSERT_KYOTO_CARD_DATA";

			// 履歴番号取得（ロック）
			$sql = $this->getQuery('KYOTO_CARD_LOCK', $user_id);
			$this->oDb->query($sql);

			$sql = $this->getQuery('GET_KYOTO_CARD_NEW_LIST_NO', $user_id);

			$list_no = $this->oDb->getOne($sql);
		}
		else
		{
			//ログ出力
			file_put_contents("/var/www/phplib/card/log/nyutaishitsu.log",date("Y/m/d H:i:s")."  ID：".$_SESSION['LOGIN_USER_ID']."  更新\n", FILE_APPEND);	

			// 更新
			$sql_id = "UPDATE_KYOTO_CARD_DATA";
		}

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = $list_no;

		$args['CARD_TYPE'] = $this->sqlItemChar($request['card_type']);

		// 扉の権限１～40
		for ($i = 1 ; $i <= 40 ; $i++)
		{
			$col = "permission_" . $i;
			$key = strtoupper($col);

			$args[$key] = $this->sqlItemFlg(@$request[$col]);
		}

		$args['REISSUE_FLG'] = $this->sqlItemFlg(@$request['reissue_flg']);

		// 所属情報１～４
		for ($i = 1 ; $i <= 4 ; $i++)
		{
			$col = "belong_info_" . $i;
			$key = strtoupper($col);

			$args[$key] = $this->sqlItemChar($request[$col]);
		}

		$args['KEY_NUMBER'] = $this->sqlItemChar($request['key_number']);
		$args['UID'] = $this->sqlItemChar($request['uid']);

		list ($start_y, $start_m, $start_d) = explode("/", $request['start_date']);
		$start_date = sprintf("%04d-%02d-%02d", $start_y, $start_m, $start_d);
		list ($end_y, $end_m, $end_d) = explode("/", $request['end_date']);
		$end_date = sprintf("%04d-%02d-%02d", $end_y, $end_m, $end_d);

		$args['START_DATE'] = $this->sqlItemChar($start_date);
		$args['END_DATE'] = $this->sqlItemChar($end_date);
		$args['SUSPEND_FLG'] = $this->sqlItemFlg(@$request['suspend_flg']);
		$args['DEL_FLG'] = $this->sqlItemFlg(@$request['del_flg']);

		// メインデータ更新
		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		//ログ出力
		file_put_contents("/var/www/phplib/card/log/nyutaishitsu.log",date("Y/m/d H:i:s")."  ID：".$_SESSION['LOGIN_USER_ID']."  sql：".$sql."\n", FILE_APPEND);	

		if (!$ret)
		{
			//ログ出力
			file_put_contents("/var/www/phplib/card/log/nyutaishitsu.log",date("Y/m/d H:i:s")."  ID：".$_SESSION['LOGIN_USER_ID']."  更新エラー（DB）\n", FILE_APPEND);	

			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		//ログ出力
		file_put_contents("/var/www/phplib/card/log/nyutaishitsu.log",date("Y/m/d H:i:s")."  ID：".$_SESSION['LOGIN_USER_ID']."  DB更新終了\n", FILE_APPEND);	

		$request['list_no'] = $list_no;

		return true;
	}

	function updateKyotoCardReserve(&$request)
	{
		$user_id = $request['user_id'];
		$list_no = 0;

		$this->oDb->begin();

		$sql = $this->getQuery('GET_KYOTO_CARD_RESERVE', $user_id);

		$aryRsv = $this->oDb->getAll($sql);

		if (count($aryRsv) > 0)
		{
			// 更新
			$sql_id = "UPDATE_KYOTO_CARD_DATA_RESERVE";
		}
		else 
		{
			// 新規
			$sql_id = "INSERT_KYOTO_CARD_DATA_RESERVE";

		}

		$args = $this->getSqlArgs();
		$args[0] = $user_id;
		$args[1] = 0;

		$args['CARD_TYPE'] = $this->sqlItemChar($request['card_type']);

		// 扉の権限１～40
		for ($i = 1 ; $i <= 40 ; $i++)
		{
			$col = "permission_" . $i;
			$key = strtoupper($col);

			$args[$key] = $this->sqlItemFlg(@$request[$col]);
		}

		$args['REISSUE_FLG'] = $this->sqlItemFlg(@$request['reissue_flg']);

		// 所属情報１～４
		for ($i = 1 ; $i <= 4 ; $i++)
		{
			$col = "belong_info_" . $i;
			$key = strtoupper($col);

			$args[$key] = $this->sqlItemChar($request[$col]);
		}

		$args['KEY_NUMBER'] = $this->sqlItemChar($request['key_number']);
		$args['UID'] = $this->sqlItemChar($request['uid']);

		list ($start_y, $start_m, $start_d) = explode("/", $request['start_date']);
		$start_date = sprintf("%04d-%02d-%02d", $start_y, $start_m, $start_d);
		list ($end_y, $end_m, $end_d) = explode("/", $request['end_date']);
		$end_date = sprintf("%04d-%02d-%02d", $end_y, $end_m, $end_d);

		$args['START_DATE'] = $this->sqlItemChar($start_date);
		$args['END_DATE'] = $this->sqlItemChar($end_date);
		$args['SUSPEND_FLG'] = $this->sqlItemFlg(@$request['suspend_flg']);
		$args['DEL_FLG'] = $this->sqlItemFlg(@$request['del_flg']);

		// 予約反映日
		list ($send_y, $send_m, $send_d) = explode("/", $request['send_date']);
		$send_date = sprintf("%04d-%02d-%02d", $send_y, $send_m, $send_d);
		$args['SEND_DATE'] = $this->sqlItemChar($send_date);


		// メインデータ更新
		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;
	}
	
	function changeKyotoCardMainData($user_id, $list_no)
	{
		$this->oDb->begin();

		$args = array();
		$args[0] = $user_id;

		// 該当のデータのlist_noをマイナス値に
		$temp_list_no = $list_no * -1;

		$args[1] = $list_no;
		$args[2] = $temp_list_no;

		$sql = $this->getQuery('UPDATE_KYOTO_CARD_LIST_NO', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// 該当データまでのlist_noを＋１
		for ($i = $list_no - 1 ; $i >= 0 ; $i--)
		{
			$args[1] = $i;
			$args[2] = $i + 1;
			$sql = $this->getQuery('UPDATE_KYOTO_CARD_LIST_NO', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				$this->oDb->rollback();
				return false;
			}

		}

		// マイナス値にしたデータのlist_noを0に
		$args[1] = $temp_list_no;
		$args[2] = 0;

		$sql = $this->getQuery('UPDATE_KYOTO_CARD_LIST_NO', $args);

		$ret = $this->oDb->query($sql);
		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;
	}

	function relationKyotoCardData(&$request)
	{
		$user_id = $request['user_id'];
		$line_no = $request['list_no'];

		// 共有ディレクトリのKOJINEND.txtを確認する。
		$lock_file = CARD_KOJIN_DIR . "/load/KOJINEND.txt";
		if (file_exists($lock_file))
		{
			$this->pushError("インターロックファイルによりロック中のため処理できません。");
			return false;
		}

		$work_time = date("YmdHis");

		// 共有ディレクトリのKOJINSTS.txtを確認する。
		$status_file = CARD_KOJIN_DIR . "/load/KOJINSTS.txt";
		if (file_exists($status_file))
		{
			// あれば中身を確認
			if ($data = file_get_contents($status_file))
			{
				// 文字コード変換
				$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

				$aryData = explode(",", $data);
				$stauts = $aryData[0];	// 1項目目がステータス

				$stauts = trim($stauts);
				$stauts = trim($stauts,"\x22");

				//if ($status == "8" || $stauts == "9")
				if ($stauts != "0")
				{
					$this->pushError("前回の処理にエラーがあるため処理できません。");
					return false;
				}

				// エラーが無い場合は、KOJINSTS_yyyymmddhhMMss.txtにリネイムして移動する。
				$new_file = "KOJINSTS_" . $work_time . ".txt";

				//$ret = rename($status_file, CARD_KOJIN_DIR . "/load/" . $new_file);
//				$ret = rename($status_file, CARD_KOJIN_DIR . "/status/" . $new_file);
//
//				if (!$ret)
//				{
//					$this->pushError("ステータスファイルのリネームに失敗しました。");
//					return false;
//				}
				
				$ret = copy($status_file, CARD_KOJIN_DIR . "/status/" . $new_file);

				if (!$ret)
				{
					$this->pushError("ステータスファイルのコピーに失敗しました。");
					return false;
				}
				
				$ret = unlink($status_file);

				if (!$ret)
				{
					$this->pushError("ステータスファイルの削除に失敗しました。");
					return false;
				}
			}
			else
			{
				$this->pushError("ステータスファイルの読み込みに失敗しました。");
				return false;
			}
		}

		//
		// CSV作成
		//

		$aryCsvBase = array();
		//
		$aryCsvBase[] = array('header' => '0', 'val' => '{command}',			'title' => 'コマンド');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{key_number}',		'title' => '個人番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '属性');
		$aryCsvBase[] = array('header' => '1', 'val' => '{kanjiname}',		'title' => '氏名');
		$aryCsvBase[] = array('header' => '1', 'val' => '{kananame}',			'title' => 'ﾌﾘｶﾞﾅ');
		$aryCsvBase[] = array('header' => '1', 'val' => '{sexplus}',			'title' => '性別');
		$aryCsvBase[] = array('header' => '1', 'val' => '{birthday}',			'title' => '生年月日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => 'TEL番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '端末操作権限');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '通行連動番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '分類番号');
		$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_1}','title' => '所属１所属番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属１有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属１有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_2}','title' => '所属２所属番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属２有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属２有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_3}','title' => '所属３所属番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属３有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属３有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_4}','title' => '所属４所属番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属４有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属４有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{card_type}',		'title' => '認証端末１認証端末種別');
		$aryCsvBase[] = array('header' => '1', 'val' => '{uid}',					'title' => '認証端末１認証ＩＤ番号');
		$aryCsvBase[] = array('header' => '1', 'val' => '{start_date}',		'title' => '認証端末１有効期限開始日');
		$aryCsvBase[] = array('header' => '1', 'val' => '{end_date}',			'title' => '認証端末１有効期限終了日');
		$aryCsvBase[] = array('header' => '1', 'val' => '{suspend_flg}',	'title' => '認証端末１失効フラグ');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末１発行回数');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末１暗証番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２認証端末種別');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２認証ＩＤ番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２失効フラグ');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２発行回数');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２暗証番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{sec_name}',			'title' => '文字情報01');
		$aryCsvBase[] = array('header' => '1', 'val' => '{chg_name}',			'title' => '文字情報02');
		$aryCsvBase[] = array('header' => '1', 'val' => '{post_name}',		'title' => '文字情報03');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報04');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報05');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報06');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報07');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報08');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報09');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報10');
		$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '写真ファイル名');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => '予備１');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => '予備２');
		$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => '予備３');

		$title = "";
		$header = "";
		$line = "";
		$sep = "";
		foreach ($aryCsvBase AS $aryBase)
		{
			$title .= $sep . '"' . $aryBase['title'] . '"';
			$header .= $sep . '"' . $aryBase['header'] . '"';
			$line .= $sep . '"' . $aryBase['val'] . '"';
			$sep = ",";
		}

		// 削除の場合
		if ($request['del_flg'] == "1")
		{
			$command = "D";	// 削除

			// 現在の登録内容を取得
			if ($line_no != "new")
			{
				$aryOld = $this->getKyotoCardData($user_id, $line_no);

				if ($request['key_number'] != $aryOld['key_number'])
				{
					// 変わっていたら戻しておく
					$request['key_number'] = $aryOld['key_number'];
				}
			}

			$kanjiname = "";
			$kananame = "";
			$sexplus = "";
			$birthday = "";
			$belong_info_1 = "";
			$belong_info_2 = "";
			$belong_info_3 = "";
			$belong_info_4 = "";
			$card_type = "";
			$uid = "";
			$start_date = "";
			$end_date = "";
			$suspend_flg = "";
			$sec_name = "";
			$chg_name = "";
			$post_name = "";
		}
		else
		{
			// 更新の場合
			$command = "A";	// 削除

			// 利用者マスタから基本情報を取得
			$aryUser = $this->getUserBaseData($user_id);

			$kanjiname = $aryUser['kanjisei'] . "　" . $aryUser['kanjimei'];
			$kananame = $aryUser['kanasei'] . "　" . $aryUser['kanamei'];
			$sexplus = $aryUser['sex'] + 1;
			$birthday = "";
			if ($aryUser['birth_year'] != "")
			{
				$ret['birth_year'] = $by;
				$ret['birth_mon'] = (int)$bm;
				$ret['birth_day'] = (int)$bd;
				$birthday = sprintf("%04d/%02d/%02d", $aryUser['birth_year'], $aryUser['birth_mon'], $aryUser['birth_day']);
			}
			$belong_info_1 = $request['belong_info_1'];
			$belong_info_2 = $request['belong_info_2'];
			$belong_info_3 = $request['belong_info_3'];
			$belong_info_4 = $request['belong_info_4'];
			// ハンズフリータグ：70、カード：50
			$card_type = ($request['card_type'] == '1' ? "50" : "70");
			$uid = $request['uid'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$suspend_flg = ($request['suspend_flg'] == '1' ? '1' : '0');

			$arySec = $this->getBelongSecAry($aryUser['belong_dep_id']);
			$aryChg = $this->getBelongChgAry($aryUser['belong_sec_id']);
			$aryPost = $this->getPostAry();
			$sec_name = $arySec[$aryUser['belong_sec_id']];
			$chg_name = $aryChg[$aryUser['belong_chg_id']];
			$post_name = $aryPost[$aryUser['post_id']];
		}

		$request['key_number'] = str_pad($request['key_number'], 16, "0", STR_PAD_LEFT);

		// キー番号は０埋め16桁
		$key_number = $request['key_number'];

		// 置換
		$line = str_replace("{command}", $command, $line);
		$line = str_replace("{key_number}", $key_number, $line);
		$line = str_replace("{kanjiname}", substr($kanjiname,0,40), $line);
		$line = str_replace("{kananame}", substr($kananame,0,40), $line);
		$line = str_replace("{sexplus}", $sexplus, $line);
		$line = str_replace("{birthday}", $birthday, $line);
		$line = str_replace("{belong_info_1}", $belong_info_1, $line);
		$line = str_replace("{belong_info_2}", $belong_info_2, $line);
		$line = str_replace("{belong_info_3}", $belong_info_3, $line);
		$line = str_replace("{belong_info_4}", $belong_info_4, $line);
		$line = str_replace("{card_type}", $card_type, $line);
		$line = str_replace("{uid}", $uid, $line);
		$line = str_replace("{start_date}", $start_date, $line);
		$line = str_replace("{end_date}", $end_date, $line);
		$line = str_replace("{suspend_flg}", $suspend_flg, $line);
		$line = str_replace("{sec_name}", substr($sec_name,0,40), $line);
		$line = str_replace("{chg_name}", substr($chg_name,0,40), $line);
		$line = str_replace("{post_name}", substr($post_name,0,40), $line);

		$strCsv = $title . "\n" . $header . "\n" . $line . "\n";

		$strCsv = mb_convert_encoding($strCsv, "sjis-win", "UTF-8");

		$ret = file_put_contents(CARD_KOJIN_DIR . "/load/LDKOJIN.csv", $strCsv);

		if (!$ret)
		{
			$this->pushError("個人情報ファイルの書き込みに失敗しました。");
			return false;

		}

		file_put_contents(CARD_KOJIN_DIR . "/status/LDKOJIN_" . $work_time . ".csv", $strCsv);


		$work_time = substr($work_time, 0, 12);
		//$statFileData = '"0","'.$work_time.'","0","0","0"';
		$statFileData = $work_time;

		file_put_contents($lock_file, $statFileData);

		sleep(1);
		// 共有ディレクトリのKOJINSTS.txtを確認する。
		$status_file = CARD_KOJIN_DIR . "/load/KOJINSTS.txt";
		if (!file_exists($status_file))
		{
			sleep(2);
			if (!file_exists($status_file))
			{
				sleep(2);
				if (!file_exists($status_file))
				{
					sleep(10);
					if (!file_exists($status_file))
					{
						sleep(30);
						if (!file_exists($status_file))
						{
							$this->pushError("ステータスファイルが作成されないため処理できません。");
							return false;
						}
					}
				}
			}
		}

		
		$work_time = date("YmdHis");

		if (file_exists($status_file))
		{
			// あれば中身を確認
			if ($data = file_get_contents($status_file))
			{
				// 文字コード変換
				$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

				$aryData = explode(",", $data);
				$stauts = $aryData[0];	// 1項目目がステータス

				$stauts = trim($stauts);
				$stauts = trim($stauts,"\x22");

				//if ($status == "8" || $stauts == "9")
				if ($stauts != "0")
				{
					$this->pushError("エラーがあるため処理できません。");
					return false;
				}

				// エラーが無い場合は、KOJINSTS_yyyymmddhhMMss.txtにリネイムして移動する。
				$new_file = "KOJINSTS_" . $work_time . ".txt";

				//$ret = rename($status_file, CARD_KOJIN_DIR . "/load/" . $new_file);
//				$ret = rename($status_file, CARD_KOJIN_DIR . "/status/" . $new_file);
//
//				if (!$ret)
//				{
//					$this->pushError("ステータスファイルのリネームに失敗しました。");
//					return false;
//				}
				
				$ret = copy($status_file, CARD_KOJIN_DIR . "/status/" . $new_file);

				if (!$ret)
				{
					$this->pushError("ステータスファイルのコピーに失敗しました。");
					return false;
				}
				
				$ret = unlink($status_file);

				if (!$ret)
				{
					$this->pushError("ステータスファイルの削除に失敗しました。");
					return false;
				}
			}
			else
			{
				$this->pushError("ステータスファイルの読み込みに失敗しました。");
				return false;
			}
		}
		
		return true;
	}

}

?>
