<?php
/**********************************************************
* File         : users_search_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/users_search_sql.inc.php");

define ("DEFAULT_ORDER", "UM.update_time");
define ("DEFAULT_DESC", "DESC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class users_search_mgr extends common_mgr
{
	var $session_key = 'US_SCH';

	function __construct()
	{
		parent::__construct();
	}
	function saveSearchData($request)
	{
		$_SESSION[$this->session_key]['login_id'] = @$request['login_id'];
		$_SESSION[$this->session_key]['staffcode'] = @$request['staffcode'];
		$_SESSION[$this->session_key]['kananame'] = @$request['kananame'];
		$_SESSION[$this->session_key]['job_id'] = @$request['job_id'];
		$_SESSION[$this->session_key]['post_id'] = @$request['post_id'];
		$_SESSION[$this->session_key]['joukin_kbn'] = @$request['joukin_kbn'];
		$_SESSION[$this->session_key]['belong_class_id'] = @$request['belong_class_id'];
		$_SESSION[$this->session_key]['belong_div_id'] = @$request['belong_div_id'];
		$_SESSION[$this->session_key]['belong_dep_id'] = @$request['belong_dep_id'];
		$_SESSION[$this->session_key]['belong_sec_id'] = @$request['belong_sec_id'];
		$_SESSION[$this->session_key]['belong_chg_id'] = @$request['belong_chg_id'];
		$_SESSION[$this->session_key]['user_type_id'] = @$request['user_type_id'];
		$_SESSION[$this->session_key]['search_option'] = @$request['search_option'];

		$_SESSION[$this->session_key]['list_max'] = @$request['list_max'];
		$_SESSION[$this->session_key]['order']    = "";
		$_SESSION[$this->session_key]['desc']     = "";
	}

	function saveOrderData($request)
	{
		$desc = "";
		if (@$request['order'] == @$_SESSION[$this->session_key]['order'])
		{
			if (@$_SESSION[$this->session_key]['desc'] == "")
			{
				$desc = "DESC";
			}
		}
		$_SESSION[$this->session_key]['order'] = $request['order'];
		$_SESSION[$this->session_key]['desc']  = $desc;
	}

	function loadOrderKey()
	{
		return @$_SESSION[$this->session_key]['order'];
	}

	function loadOrderDesc()
	{
		return $_SESSION[$this->session_key]['desc'];
	}

	function savePage($page)
	{
		$_SESSION[$this->session_key]['page'] = $page;
	}

	function saveListMax($max)
	{
		$_SESSION[$this->session_key]['list_max'] = $max;
	}

	function loadSearchData(&$request)
	{
		$request['login_id'] = @$_SESSION[$this->session_key]['login_id'];
		$request['staffcode'] = @$_SESSION[$this->session_key]['staffcode'];
		$request['kananame'] = @$_SESSION[$this->session_key]['kananame'];
		$request['job_id'] = @$_SESSION[$this->session_key]['job_id'];
		$request['post_id'] = @$_SESSION[$this->session_key]['post_id'];
		$request['joukin_kbn'] = @$_SESSION[$this->session_key]['joukin_kbn'];
		$request['belong_class_id'] = @$_SESSION[$this->session_key]['belong_class_id'];
		$request['belong_div_id'] = @$_SESSION[$this->session_key]['belong_div_id'];
		$request['belong_dep_id'] = @$_SESSION[$this->session_key]['belong_dep_id'];
		$request['belong_sec_id'] = @$_SESSION[$this->session_key]['belong_sec_id'];
		$request['belong_chg_id'] = @$_SESSION[$this->session_key]['belong_chg_id'];
		$request['user_type_id'] = @$_SESSION[$this->session_key]['user_type_id'];
		$request['search_option'] = @$_SESSION[$this->session_key]['search_option'];

		$request['list_max'] = @$_SESSION[$this->session_key]['list_max']   ;
		$request['order'] = @$_SESSION[$this->session_key]['order']   ;
		$request['desc'] = @$_SESSION[$this->session_key]['desc']    ;
	}

	function loadPage(&$page)
	{
		$page = @$_SESSION[$this->session_key]['page'];
	}

	function getSearchArgs($request)
	{
		$args = $this->getSqlArgs();
		$args['COND'] = "";

		$aryCond = array();

		// 統合ID
		if (@$request['login_id'] != "")
		{
			$aryCond[] = "UM.login_id LIKE '%" . string::replaceSql($request['login_id']) . "%'";
		}

		// カードNo.
		if (@$request['staffcode'] != "")
		{
			$aryCond[] = "EXISTS (SELECT * FROM kyoto_user_card_tbl WHERE UM.user_id = user_id AND del_flg = '0' AND key_number LIKE '%" . string::replaceSql($request['staffcode']) . "%')";
		}

		// カナ氏名
		if (@$request['kananame'] != "")
		{
			$kananame = string::han2zen($request['kananame']);
			$kananame = str_replace("　", " ", $kananame);
			$kananame = str_replace(" ", "", $kananame);
			$strBuff = "(";
			$strBuff .= "COALESCE(UM.kanasei, ' ') || COALESCE(UM.kanamei, ' ') LIKE '%" . string::replaceSql($request['kananame']) . "%'";
			$strBuff .= " OR ";
			$strBuff .= "COALESCE(UM.kanjisei, ' ') || COALESCE(UM.kanjimei, ' ') LIKE '%" . string::replaceSql($request['kananame']) . "%'";
			$strBuff .= " OR ";
			$strBuff .= "UM.kanasei || UM.kanamei LIKE '%" . string::replaceSql($kananame) . "%'";
			$strBuff .= " OR ";
			$strBuff .= "UM.kanjisei || UM.kanjimei LIKE '%" . string::replaceSql($kananame) . "%'";
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_his_tbl WHERE UM.user_id = user_id AND del_flg = '0' AND replace(replace(kananame, '　', ' '), ' ', '') LIKE '%" . string::replaceSql($kananame) . "%')";
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_his_tbl WHERE UM.user_id = user_id AND del_flg = '0' AND replace(replace(kanjiname, '　', ' '), ' ', '') LIKE '%" . string::replaceSql($kananame) . "%')";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}


		// 職種
		if (@$request['job_id'] != "")
		{
			$strBuff = "(";
			$strBuff .= "UM.job_id = " . string::replaceSql($request['job_id']);
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_sub_job_tbl WHERE UM.user_id = user_id AND del_flg = '0' AND job_id = " . string::replaceSql($request['job_id']) . ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}

		// 役職
		if (@$request['post_id'] != "")
		{
			$strBuff = "(";
			$strBuff .= "UM.post_id = " . string::replaceSql($request['post_id']);
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_sub_post_tbl WHERE UM.user_id = user_id AND del_flg = '0' AND post_id = " . string::replaceSql($request['post_id']) . ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}

		// 常勤/非常勤
		if (@$request['joukin_kbn'] != "")
		{
			$aryCond[] = "UM.joukin_kbn = '" . string::replaceSql($request['joukin_kbn']) . "'";
		}

		// 所属
		if (@$request['belong_chg_id'] != "")
		{
			$strBuff = "(";
			$strBuff .= "UM.belong_chg_id = " . string::replaceSql($request['belong_chg_id']);
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_sub_chg_tbl WHERE UM.user_id = user_id AND del_flg = '0' AND belong_chg_id = " . string::replaceSql($request['belong_chg_id']) . ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}
		else if (@$request['belong_sec_id'] != "")
		{
			$strBuff = "(";
			$strBuff .= "EXISTS (SELECT * FROM belong_chg_mst WHERE UM.belong_chg_id = belong_chg_id AND del_flg = '0' AND belong_sec_id = " . string::replaceSql($request['belong_sec_id']) . ")";
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_sub_chg_tbl AS USC,belong_chg_mst AS BCM WHERE UM.user_id = USC.user_id AND USC.belong_chg_id = BCM.belong_chg_id AND BCM.del_flg = '0' AND BCM.belong_sec_id = " . string::replaceSql($request['belong_sec_id']) . ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}
		else if (@$request['belong_dep_id'] != "")
		{
			$strBuff = "(";
			$strBuff .= "EXISTS (SELECT * FROM belong_chg_mst AS BCM,belong_sec_mst AS BSM WHERE UM.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BSM.belong_dep_id = " . string::replaceSql($request['belong_dep_id']) . ")";
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_sub_chg_tbl AS USC,belong_chg_mst AS BCM,belong_sec_mst AS BSM WHERE UM.user_id = USC.user_id AND USC.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BSM.belong_dep_id = " . string::replaceSql($request['belong_dep_id']) . ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}
		else if (@$request['belong_div_id'] != "")
		{
			$strBuff = "(";
			$strBuff .= "EXISTS (SELECT * FROM belong_chg_mst AS BCM,belong_sec_mst AS BSM,belong_dep_mst AS BDM WHERE UM.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BSM.belong_dep_id = BDM.belong_dep_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BDM.del_flg = '0' AND BDM.belong_div_id = " . string::replaceSql($request['belong_div_id']) . ")";
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_sub_chg_tbl AS USC,belong_chg_mst AS BCM,belong_sec_mst AS BSM,belong_dep_mst AS BDM WHERE UM.user_id = USC.user_id AND USC.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BSM.belong_dep_id = BDM.belong_dep_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BDM.del_flg = '0' AND BDM.belong_div_id = " . string::replaceSql($request['belong_div_id']) . ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}
		else if (@$request['belong_class_id'] != "")
		{
			$strBuff = "(";
			$strBuff .= "EXISTS (SELECT * FROM belong_chg_mst AS BCM,belong_sec_mst AS BSM,belong_dep_mst AS BDM,belong_div_mst AS BVM WHERE UM.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BSM.belong_dep_id = BDM.belong_dep_id AND BDM.belong_div_id = BVM.belong_div_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BDM.del_flg = '0' AND BVM.del_flg = '0' AND BVM.belong_class_id = " . string::replaceSql($request['belong_class_id']) . ")";
			$strBuff .= " OR ";
			$strBuff .= "EXISTS (SELECT * FROM user_sub_chg_tbl AS USC,belong_chg_mst AS BCM,belong_sec_mst AS BSM,belong_dep_mst AS BDM,belong_div_mst AS BVM WHERE UM.user_id = USC.user_id AND USC.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BSM.belong_dep_id = BDM.belong_dep_id AND BDM.belong_div_id = BVM.belong_div_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BDM.del_flg = '0' AND BVM.del_flg = '0' AND BVM.belong_class_id = " . string::replaceSql($request['belong_class_id']) . ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}

		// 利用者種別
		if (is_array(@$request['user_type_id']))
		{
			$strUserTypeIds = implode(",", array_keys($request['user_type_id']));
			$aryCond[] = "EXISTS (SELECT * FROM user_role_tbl WHERE UM.user_id = user_id AND del_flg = '0' AND role_id IN (" . $strUserTypeIds . "))";
		}


		if (@$request['search_option'] == "3")
		{
			// 期限切れのみ
			$aryCond[] = "UM.end_date < (now() + '-6 months')::date";
		}
		else if (@$request['search_option'] == "2")
		{
			// 利用期間外は表示しない
			$aryCond[] = "UM.start_date <= now()::date";
			$aryCond[] = "COALESCE(UM.end_date, now()::date) >= now()::date";
		}
		else if (@$request['search_option'] == "1")
		{
			// 退職者は表示しない
			$aryCond[] = "(UM.retire_flg = '0' OR (UM.retire_flg = '1' AND COALESCE(UM.end_date, now()::date) >= now()::date))";
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
				case "kanji_name":
					$args['ORDER'] = "UM.kanjisei || UM.kanjimei";
					break;
				case "kana_name":
					$args['ORDER'] = "UM.kanasei || UM.kanamei";
					break;
				case "belong_name":
					$args['ORDER'] = "SRT.sort_belong_name";
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


}
?>
