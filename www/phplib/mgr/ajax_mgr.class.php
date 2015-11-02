<?php
/**********************************************************
* File         : ajax_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.29
* Last Update  : 2013.01.29
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/ajax_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class ajax_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function makeVlanRidgeChangeJs($id, $prefix="", $has_vlan_id=true)
	{
		$str = "";
		// 取得
		$aryTmp = $this->getVlanFloorAry($id);

		if (!is_array($aryTmp))
		{
			return $str;
		}

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";

		//
		// オプションリスト生成JS
		//

		$elem_id = $prefix . "vlan_floor_id";
		$opt_cnt = count($aryTmp) + 1;

		$disabled = "false";
		if ($id == "")
		{
			$disabled = "true";
		}

		$str .= 'document.getElementById("' . $elem_id . '").disabled = '.$disabled.';';
		$str .= "\n";
		$str .= 'document.getElementById("' . $prefix . 'vlan_room_id").selectedIndex = 0;';
		$str .= "\n";
		$str .= 'document.getElementById("' . $prefix . 'vlan_room_id").disabled = true;';
		$str .= "\n";
		if ($has_vlan_id)
		{
			$str .= 'document.getElementById("' . $prefix . 'vlan_id").selectedIndex = 0;';
			$str .= "\n";
			$str .= 'document.getElementById("' . $prefix . 'vlan_id").disabled = true;';
			$str .= "\n";
		}
		$str .= 'document.getElementById("' . $elem_id . '").length = ';
		$str .= $opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		foreach ($aryTmp AS $key => $value)
		{
			$str .= 'document.getElementById("' . $elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";
		}

		$str .= '</SCRIPT>';

		return $str;
	}

	function makeVlanFloorChangeJs($id, $prefix="", $has_vlan_id=true)
	{
		$str = "";
		// 取得
		$aryTmp = $this->getVlanRoomAry($id);

		if (!is_array($aryTmp))
		{
			return $str;
		}

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";

		//
		// オプションリスト生成JS
		//

		$elem_id = $prefix . "vlan_room_id";
		$opt_cnt = count($aryTmp) + 1;

		$disabled = "false";
		if ($id == "")
		{
			$disabled = "true";
		}

		$str .= 'document.getElementById("' . $elem_id . '").disabled = '.$disabled.';';
		$str .= "\n";
		if ($has_vlan_id)
		{
			$str .= 'document.getElementById("' . $prefix . 'vlan_id").selectedIndex = 0;';
			$str .= "\n";
			$str .= 'document.getElementById("' . $prefix . 'vlan_id").disabled = true;';
			$str .= "\n";
		}
		$str .= 'document.getElementById("' . $elem_id . '").length = ';
		$str .= $opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		foreach ($aryTmp AS $key => $value)
		{
			$str .= 'document.getElementById("' . $elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";
		}

		$str .= '</SCRIPT>';

		return $str;
	}

	function makeVlanRoomChangeJs($id, $prefix="")
	{
		$str = "";
		// 取得
		$aryTmp = $this->getVlanAry($id);

		if (!is_array($aryTmp))
		{
			return $str;
		}

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";

		//
		// オプションリスト生成JS
		//

		$elem_id = $prefix . "vlan_id";
		$opt_cnt = count($aryTmp) + 1;

		$disabled = "false";
		if ($id == "")
		{
			$disabled = "true";
		}

		$str .= 'document.getElementById("' . $elem_id . '").disabled = '.$disabled.';';
		$str .= "\n";
		$str .= 'document.getElementById("' . $elem_id . '").length = ';
		$str .= $opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		foreach ($aryTmp AS $key => $value)
		{
			$str .= 'document.getElementById("' . $elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";
		}

		$str .= '</SCRIPT>';

		return $str;
	}

	function getChgOptList($prefix, $aryChg)
	{
		$chg_elem_id = $prefix . "belong_chg_id";
		$chg_opt_cnt = count($aryChg) + 1;

		$str .= 'document.getElementById("' . $chg_elem_id . '").length = ';
		$str .= $chg_opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		foreach ($aryChg AS $key => $value)
		{
			$str .= 'document.getElementById("' . $chg_elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";
		}

		return $str;
	}

	function getSecOptList($prefix, $arySec)
	{
		$sec_elem_id = $prefix . "belong_sec_id";
		$sec_opt_cnt = count($arySec) + 1;

		$str .= 'document.getElementById("' . $sec_elem_id . '").length = ';
		$str .= $sec_opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		$aryChg = array();
		foreach ($arySec AS $key => $value)
		{
			$str .= 'document.getElementById("' . $sec_elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";

			// 係を取得
			$aryTmp = $this->getBelongChgAry($key);
			if (is_array($aryTmp))
			{
				$aryChg += $aryTmp;
			}
		}

		if (is_array($aryChg))
		{
			//
			// 係のオプションリスト生成JS
			//
			$str .= $this->getChgOptList($prefix, $aryChg);
		}

		return $str;
	}

	function getDepOptList($prefix, $aryDep)
	{
		$dep_elem_id = $prefix . "belong_dep_id";
		$dep_opt_cnt = count($aryDep) + 1;

		$str .= 'document.getElementById("' . $dep_elem_id . '").length = ';
		$str .= $dep_opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		$arySec = array();
		foreach ($aryDep AS $key => $value)
		{
			$str .= 'document.getElementById("' . $dep_elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";

			// 課を取得
			$aryTmp = $this->getBelongSecAry($key);
			if (is_array($aryTmp))
			{
				$arySec += $aryTmp;
			}
		}

		if (!is_array($arySec))
		{
			return $str;
		}

		//
		// 課、係のオプションリスト生成JS
		//
		$str .= $this->getSecOptList($prefix, $arySec);

		return $str;
	}

	function makeBelongClassChangeJs($id, $prefix="")
	{
		$str = "";
		// 部門を取得
		$aryDiv = $this->getBelongDivAry($id);

		if (!is_array($aryDiv))
		{
			return $str;
		}

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";

		//
		// 部門のオプションリスト生成JS
		//

		$div_elem_id = $prefix . "belong_div_id";
		$div_opt_cnt = count($aryDiv) + 1;

		$str .= 'document.getElementById("' . $div_elem_id . '").length = ';
		$str .= $div_opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		$aryDep = array();
		foreach ($aryDiv AS $key => $value)
		{
			$str .= 'document.getElementById("' . $div_elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";

			// 部を取得
			$aryBuff = $this->getBelongDepAry($key);
			if (is_array($aryBuff))
			{
				$aryDep += $aryBuff;
			}
		}

		if (is_array($aryDep))
		{
			//
			// 部、課、係のオプションリスト生成JS
			//
			$str .= $this->getDepOptList($prefix, $aryDep);
		}

		$str .= '</SCRIPT>';

		return $str;
	}

	function makeBelongDivChangeJs($id, $prefix="")
	{
		$aryTmp = $this->getBelongDepAry($id);

		$str = "";
		if (is_array($aryTmp))
		{
			$opt_cnt = count($aryTmp) + 1;

			$str .= '<SCRIPT type="text/javascript">';
			$str .= "\n";
			$str .= $this->getDepOptList($prefix, $aryTmp);
			$str .= '</SCRIPT>';
		}

		return $str;
	}

	function makeBelongDepChangeJs($id, $prefix="")
	{
		$aryTmp = $this->getBelongSecAry($id);

		$str = "";
		if (is_array($aryTmp))
		{
			$str .= '<SCRIPT type="text/javascript">';
			$str .= "\n";
			$str .= $this->getSecOptList($prefix, $aryTmp);
			$str .= '</SCRIPT>';
		}

		return $str;
	}

	function makeBelongSecChangeJs($id, $prefix="")
	{
		$aryTmp = $this->getBelongChgAry($id);

		$str = "";
		if (is_array($aryTmp))
		{
			$str .= '<SCRIPT type="text/javascript">';
			$str .= "\n";
			$str .= $this->getChgOptList($prefix, $aryTmp);
			$str .= '</SCRIPT>';
		}

		return $str;
	}

	function makeAddBelongListJs($key, $tbody_id, $pattern)
	{
		$classOpt = $this->makeSelectList('sub_belong_class_id', "");
		$divOpt = $this->makeSelectList('sub_belong_div_id', "");
		$depOpt = $this->makeSelectList('sub_belong_dep_id', "");
		$secOpt = $this->makeSelectList('sub_belong_sec_id', "");
		$chgOpt = $this->makeSelectList('sub_belong_chg_id', "");
		$jobOpt = $this->makeSelectList('sub_job_id', "");
		$postOpt = $this->makeSelectList('sub_post_id', "");

		/*
		 * 所属の上２階層を非表示およびサブ職員番号追加対応
		 */
		if ($pattern == "")
		{
			$upper_belogn_style = "width:100px;";
			$sub_staff_id_style = "width:0px;visibility:hidden;";
			$staff_id_len = 0;
			$staff_id_label = "";
		}
		else if ($pattern == 2)
		{
			$upper_belogn_style = "width:0px;visibility:hidden;";
			$sub_staff_id_style = "width:80px;";
			$staff_id_len = STAFF_ID_LEN;
			$staff_id_label = '<label for="sub_'.$key . '_staff_id" id="sub_' . $key . '_staff_id_label" style="' . $sub_staff_id_style . '">職員番号：</label>';
		}



		/*
		 * 注：<select　～ </select>は必ず1行で書く事
		*/

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";
		$str .= <<< JSCRIPT

var tr_tag = document.createElement("tr");
var td_tag1 = document.createElement("td");
var td_tag2 = document.createElement("td");

document.getElementById("${tbody_id}").appendChild(tr_tag);
tr_tag.appendChild(td_tag1);
tr_tag.appendChild(td_tag2);
td_tag1.align = "center";
td_tag1.innerHTML = "(${key})&nbsp;";
td_tag2.innerHTML = '<select name="sub_belong_class_id[${key}]" id="sub_${key}_belong_class_id" onchange="belongClassChange(this.value, \'sub_${key}_\');" style="$upper_belogn_style"><option value="">--大分類--</option>${classOpt}</select>';
td_tag2.innerHTML += '<div class="floatLeft" id="sub_${key}_belongClassJs"></div>\\n';
td_tag2.innerHTML += '<select name="sub_belong_div_id[${key}]" id="sub_${key}_belong_div_id" onchange="belongDivChange(this.value, \'sub_${key}_\');" style="$upper_belogn_style"><option value="">--部門--</option>${divOpt}</select>';
td_tag2.innerHTML += '<div class="floatLeft" id="sub_${key}_belongDivJs"></div>\\n';
td_tag2.innerHTML += '<select name="sub_belong_dep_id[${key}]" id="sub_${key}_belong_dep_id" onchange="belongDepChange(this.value, \'sub_${key}_\');" style="width:100px;"><option value="">--部--</option>${depOpt}</select>';
td_tag2.innerHTML += '<div class="floatLeft" id="sub_${key}_belongDepJs"></div>\\n';
td_tag2.innerHTML += '<select name="sub_belong_sec_id[${key}]" id="sub_${key}_belong_sec_id" onchange="belongSecChange(this.value, \'sub_${key}_\');" style="width:100px;"><option value="">--課・科--</option>${secOpt}</select>';
td_tag2.innerHTML += '<div class="floatLeft" id="sub_${key}_belongSecJs"></div>\\n';
td_tag2.innerHTML += '<select name="sub_belong_chg_id[${key}]" id="sub_${key}_belong_chg_id" style="width:100px;"><option value="">--係・室・他--</option>${chgOpt}</select>\\n';
td_tag2.innerHTML += '<select name="sub_job_id[${key}]" id="sub_${key}_job_id" style="width:100px;"><option value="">--職種--</option>${jobOpt}</select>\\n';
td_tag2.innerHTML += '<select name="sub_post_id[${key}]" id="sub_${key}_post_id" style="width:100px;"><option value="">--役職--</option>${postOpt}</select>\\n';
td_tag2.innerHTML += '$staff_id_label<input name="sub_staff_id[${key}]" type="text" id="sub_${key}_staff_id" maxlength="$staff_id_len" value="" style="$sub_staff_id_style" />\\n';
td_tag2.innerHTML += '[<a href="javascript:;" onclick="deleteBelongList(${key});">×</a>]';

jQuery(function (){
	jQuery('select#sub_${key}_belong_class_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_belong_div_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_belong_dep_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_belong_sec_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_belong_chg_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});
});


JSCRIPT;


		$str .= '</SCRIPT>';

		return $str;

	}

	function makeAddJobListJs($key, $tbody_id)
	{
		$jobOpt = $this->makeSelectList('sub_job_id', "");

		/*
		 * 注：<select　～ </select>は必ず1行で書く事
		*/

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";
		$str .= <<< JSCRIPT

var tr_tag = document.createElement("tr");
var td_tag1 = document.createElement("td");
var td_tag2 = document.createElement("td");

document.getElementById("${tbody_id}").appendChild(tr_tag);
tr_tag.appendChild(td_tag1);
tr_tag.appendChild(td_tag2);
td_tag1.align = "center";
td_tag1.innerHTML = "(${key})&nbsp;";
td_tag2.innerHTML = '<select name="sub_job_id[${key}]" id="sub_${key}_job_id"><option value="">----</option>${jobOpt}</select>';

JSCRIPT;


		$str .= '</SCRIPT>';

		return $str;

	}

	function makeAddPostListJs($key, $tbody_id)
	{
		$postOpt = $this->makeSelectList('sub_post_id', "");

		/*
		 * 注：<select　～ </select>は必ず1行で書く事
		*/

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";
		$str .= <<< JSCRIPT

var tr_tag = document.createElement("tr");
var td_tag1 = document.createElement("td");
var td_tag2 = document.createElement("td");

document.getElementById("${tbody_id}").appendChild(tr_tag);
tr_tag.appendChild(td_tag1);
tr_tag.appendChild(td_tag2);
td_tag1.align = "center";
td_tag1.innerHTML = "(${key})&nbsp;";
td_tag2.innerHTML = '<select name="sub_post_id[${key}]" id="sub_${key}_post_id"><option value="">----</option>${postOpt}</select>';

JSCRIPT;


		$str .= '</SCRIPT>';

		return $str;

	}
	function checkExistsLoginId($login_id, $user_id="")
	{
		$args = array();
		$args[0] = $login_id;
		$args['COND'] = "";
		if ($user_id != "")
		{
			$args['COND'] = " AND user_id != " . string::replaceSql($user_id);
		}

		// 存在チェック
		$sql = $this->getQuery('CHECK_EXISTS_LOGIN_ID', $args);

		$user = $this->oDb->getOne($sql);

		if ($user != "")
		{
			return true;
		}

		if ($this->existsMailAcc($login_id))
		{
			// メールアカウントとして使用中
			return true;
		}

		if ($this->existsMlistAcc($login_id))
		{
			// メーリングリストとして使用中
			return true;
		}


		return false;
	}

	function checkExistsPbno($pbno, $user_id="")
	{
		$args = array();
		$args[0] = $pbno;
		$args['COND'] = "";
		if ($user_id != "")
		{
			$args['COND'] = " AND user_id != " . string::replaceSql($user_id);
		}

		// 存在チェック
		$sql = $this->getQuery('CHECK_EXISTS_PBNO', $args);

		$user = $this->oDb->getOne($sql);

		if ($user != "")
		{
			return true;
		}

		return false;
	}

	function makeLoginId($eijisei, $eijimei)
	{
		$eijisei = string::zen2han($eijisei);
		$eijimei = string::zen2han($eijimei);

		$eijisei = strtolower($eijisei);
		$eijimei = strtolower($eijimei);

		$eijisei = str_replace("-", "", $eijisei);
		$eijimei = str_replace("-", "", $eijimei);

		// ランダム文字列取得
		$aryChars = $this->getAry('rand_tow_chars');

		$rand = $aryChars[array_rand($aryChars, 1)];

		// 20 - 4 = 16 eijisei.eijimei.xx
		if (strlen($eijisei.$eijimei) > 16)
		{
			$len = strlen($eijisei.$eijimei) - 16;

			for ($i = 0 ; $i < $len ; $i++)
			{
				if (strlen($eijimei) > 1)
				{
					$eijimei = substr($eijimei, 0, -1);
				}
				else
				{
					$eijisei = substr($eijisei, 0, -1);
				}
			}
		}

		$id = $eijisei . "." . $eijimei . "." . $rand;

		// 存在チェック
		if ($this->checkExistsLoginId($id))
		{
			$id = self::makeLoginId($eijisei, $eijimei);
		}

		return $id;
	}

	function existsLoginId($login_id, $user_id="")
	{
		if ($this->checkExistsLoginId($login_id, $user_id))
		{
			$msg = "既に登録されている統合IDです。";
		}
		else
		{
			$msg = "重複はありません。使用可能です。";
		}

		return $msg;
	}

	function existsPbno($pbno, $user_id="")
	{
		if ($this->checkExistsPbno($pbno, $user_id))
		{
			$msg = "既に登録されているPHS番号です。\n※重複していても登録は可能です。";
		}
		else
		{
			$msg = "重複はありません。";
		}

		return $msg;
	}

	function makeWardstatusChangeJs($status, $prefix="")
	{
		$str = "";
		// 取得
		$aryWard = $this->getWardAry($status);

		if (!is_array($aryWard))
		{
			return $str;
		}

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";

		//
		// オプションリスト生成JS
		//

		$ward_elem_id = $prefix . "wardcode";
		$ward_opt_cnt = count($aryWard) + 1;

		$str .= 'document.getElementById("' . $ward_elem_id . '").length = ';
		$str .= $ward_opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		foreach ($aryWard AS $key => $value)
		{
			$str .= 'document.getElementById("' . $ward_elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";
		}

		$str .= '</SCRIPT>';

		return $str;
	}

	function makeProfessionstatusChangeJs($status, $prefix="")
	{
		$str = "";
		// 取得
		$aryProf = $this->getProfessionAry($status);

		if (!is_array($aryProf))
		{
			return $str;
		}

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";

		//
		// オプションリスト生成JS
		//

		$prof_elem_id = $prefix . "professioncode";
		$prof_opt_cnt = count($aryProf) + 1;

		$str .= 'document.getElementById("' . $prof_elem_id . '").length = ';
		$str .= $prof_opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		foreach ($aryProf AS $key => $value)
		{
			$str .= 'document.getElementById("' . $prof_elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";
		}

		$str .= '</SCRIPT>';

		return $str;
	}

	function makeDeptstatusChangeJs($status, $prefix="")
	{
		$str = "";
		// 取得
		$aryDept = $this->getDeptAry($status);

		if (!is_array($aryDept))
		{
			return $str;
		}

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";

		//
		// オプションリスト生成JS
		//

		$dept_elem_id = $prefix . "deptcode";
		$dept_opt_cnt = count($aryDept) + 1;

		$str .= 'document.getElementById("' . $dept_elem_id . '").length = ';
		$str .= $dept_opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		$aryGroup = array();
		foreach ($aryDept AS $key => $value)
		{
			$str .= 'document.getElementById("' . $dept_elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";

			// グループを取得
			$aryBuff = $this->getDeptgroupAry($key);
			if (is_array($aryBuff))
			{
				$aryGroup += $aryBuff;
			}
		}

		if (is_array($aryGroup))
		{
			//
			// グループのオプションリスト生成JS
			//
			$str .= $this->getDeptgroupList($prefix, $aryGroup);
		}

		$str .= '</SCRIPT>';

		return $str;
	}

	function makeDeptcodeChangeJs($code, $prefix="")
	{
		$aryTmp = $this->getDeptgroupAry($code);

		$str = "";
		if (is_array($aryTmp))
		{
			$str .= '<SCRIPT type="text/javascript">';
			$str .= "\n";
			$str .= $this->getDeptgroupList($prefix, $aryTmp);
			$str .= '</SCRIPT>';
		}

		return $str;
	}

	function getDeptgroupList($prefix, $aryGroup)
	{
		$group_elem_id = $prefix . "deptgroupcode";
		$group_opt_cnt = count($aryGroup) + 1;

		$str .= 'document.getElementById("' . $group_elem_id . '").length = ';
		$str .= $group_opt_cnt;
		$str .= ';';
		$str .= "\n";
		$cnt = 1;
		foreach ($aryGroup AS $key => $value)
		{
			$str .= 'document.getElementById("' . $group_elem_id . '").options[' . $cnt++;
			$str .= '] = new Option("' . $value . '", "' . $key . '");';
			$str .= "\n";
		}

		return $str;
	}


	function makeAddHisDataListJs($key, $tbody_id, $kanjiname, $kananame)
	{
		$today = date("Y/m/d");

		$wardstOpt = $this->makeSelectList('sub_wardstatus', "");
		$wardOpt = $this->makeSelectList('sub_wardcode', "");
		$profstOpt = $this->makeSelectList('sub_professionstatus', "");
		$profOpt = $this->makeSelectList('sub_professioncode', "");
		$gradeOpt = $this->makeSelectList('sub_gradecode', "");
		$deptstOpt = $this->makeSelectList('sub_deptstatus', "");
		$deptOpt = $this->makeSelectList('sub_deptcode', "");
		$deptgrOpt = $this->makeSelectList('sub_deptgroupcode', "");

		/*
		 * 注：<select　～ </select>は必ず1行で書く事
		*/

		$str .= '<SCRIPT type="text/javascript">';
		$str .= "\n";
		$str .= <<< JSCRIPT

var tr_tag1 = document.createElement("tr");
var tr_tag2 = document.createElement("tr");
var td_tag1 = document.createElement("td");
var td_tag2 = document.createElement("td");
var tab_tag = document.createElement("table");
var ctr_tag1 = document.createElement("tr");
var ctd_tag1 = document.createElement("td");

document.getElementById("${tbody_id}").appendChild(tr_tag1);
document.getElementById("${tbody_id}").appendChild(tr_tag2);
tr_tag1.appendChild(td_tag1);
tr_tag2.appendChild(td_tag2);

td_tag1.innerHTML = '<img src="image/space.gif" width="1" height="5" /><input type="hidden" name="sub_his_init[${key}]" id="sub_${key}_his_init" value="0">';
td_tag2.innerHTML = '<div id="sub_${key}_area"></div>';

var divHtml = "";
divHtml += '<table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">';
divHtml += '<tr>';
divHtml += '<th colspan="6" scope="col"><div class="CheckBoxTab">';
divHtml += '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
divHtml += '<tr>';
divHtml += '<td width="24"><input type="checkbox" name="sub_his_flg[${key}]" id="sub_${key}_his_flg" value="1" checked onclick="changeHisFlg(this, \'sub_${key}_\');" /></td>';
divHtml += '<td width="166"><label for="sub_${key}_his_flg">電カル連携情報（サブ${key}）</label></td>';
divHtml += '<td><span class="inputRule">※電カル連携する場合はチェックしてください。</span></td>';
divHtml += '<td align="right">有効開始日<span class="hissu">*</span>：</td>';
divHtml += '<td width="100"><input name="sub_send_date[${key}]" type="text" id="sub_${key}_send_date" onchange="sub_cal_sdd[${key}].getFormValue(); sub_cal_sdd[${key}].hide();" onclick="sub_cal_sdd[${key}].write();" size="12" maxlength="10" value="${today}" /><br /><div id="sub_${key}_caldiv_sdd"></div></td>';
divHtml += '</tr>';
divHtml += '</table>';
divHtml += '</div>';
divHtml += '</th>';
divHtml += '</tr>';
divHtml += '<tr>';
divHtml += '<th width="100" scope="col">ログインID<span class="hissu">*</span></th>';
divHtml += '<td scope="col"><input name="sub_staffcode[${key}]" type="text" id="sub_${key}_staffcode" value="" size="10" maxlength="8" />\\n';
divHtml += '<span class="inputRule">(半角数字8桁)</span></td>';
divHtml += '<th width="100">漢字氏名<span class="hissu">*</span></th>';
divHtml += '<td><input name="sub_kanjiname[${key}]" type="text" id="sub_${key}_kanjiname" size="16" value="${kanjiname}" /></td>';
divHtml += '<th width="80">カナ氏名<span class="hissu">*</span></th>';
divHtml += '<td scope="col"><input name="sub_kananame[${key}]" type="text" id="sub_${key}_kananame" size="16" value="${kananame}" /></td>';
divHtml += '</tr>';
divHtml += '<tr>';
divHtml += '<th>HISパスワード</th>';
divHtml += '<td colspan="5"><input name="sub_password[${key}]" type="text" id="sub_${key}_password" size="30" value="" />\\n';
divHtml += '[<a id="sub_${key}_password_create" href="javascript:;" onclick="makePassword(\'sub_${key}_password\');">自動生成</a><span id="sub_${key}_password_text" style="display:none;">自動生成</span>]\\n';
divHtml += '<span class="inputRule">(半角英数字　4～10文字)</span></td>';
divHtml += '</tr>';
divHtml += '<tr>';
divHtml += '<th>部署</th>';
divHtml += '<td>';
divHtml += '<select name="sub_wardstatus[${key}]" id="sub_${key}_wardstatus" onchange="wardstatusChange(this.value, \'sub_${key}_\');" style="width:100px;" ><option value="">---部署区分---</option>${wardstOpt}</select>';
divHtml += '<div class="floatLeft" id="sub_${key}_wardstatusJs"></div>\\n';
divHtml += '<select name="sub_wardcode[${key}]" id="sub_${key}_wardcode" style="width:120px;" ><option value="">---部署---</option>${wardOpt}</select>';
divHtml += '</td>';
divHtml += '<th>職種</th>';
divHtml += '<td colspan="3">';
divHtml += '<select name="sub_professionstatus[${key}]" id="sub_${key}_professionstatus" onchange="professionstatusChange(this.value, \'sub_${key}_\');" style="width:100px;" ><option value="">---職種区分---</option>${profstOpt}</select>';
divHtml += '<div class="floatLeft" id="sub_${key}_professionstatusJs"></div>\\n';
divHtml += '<select name="sub_professioncode[${key}]" id="sub_${key}_professioncode" style="width:120px;" ><option value="">---職種---</option>${profOpt}</select>';
divHtml += '</td>';
divHtml += '</tr>';
divHtml += '<tr>';
divHtml += '<th>役職</th>';
divHtml += '<td>';
divHtml += '<select name="sub_gradecode[${key}]" id="sub_${key}_gradecode" ><option value="">----</option>${gradeOpt}</select>';
divHtml += '</td>';
divHtml += '<th>診療グループ</th>';
divHtml += '<td colspan="3">';
divHtml += '<select name="sub_deptstatus[${key}]" id="sub_${key}_deptstatus" onchange="deptstatusChange(this.value, \'sub_${key}_\');" style="width:100px;" ><option value="">---科区分---</option>${deptstOpt}</select>';
divHtml += '<div class="floatLeft" id="sub_${key}_deptstatusJs"></div>\\n';
divHtml += '<select name="sub_deptcode[${key}]" id="sub_${key}_deptcode" onchange="deptcodeChange(this.value, \'sub_${key}_\');" style="width:100px;" ><option value="">---診療科---</option>${deptOpt}</select>';
divHtml += '<div class="floatLeft" id="sub_${key}_deptcodeJs"></div>\\n';
divHtml += '<select name="sub_deptgroupcode[${key}]" id="sub_${key}_deptgroupcode" style="width:120px;" ><option value="">---グループ---</option>${deptgrOpt}</select>';
divHtml += '</td>';
divHtml += '</tr>';
divHtml += '<tr>';
divHtml += '<th>予約項目コード</th>';
divHtml += '<td><input name="sub_appcode[${key}]" type="text" id="sub_${key}_appcode" size="10" maxlength="5" value="" />\\n';
divHtml += '[<a id="sub_${key}_appcode_create" href="javascript:;" onclick="makeAppCode(\'sub_${key}_\');" >自動生成</a><span id="sub_${key}_appcode_text" style="display:none;">自動生成</span>] <span class="inputRule">(半角英数字5桁)</span></td>';
divHtml += '<th>有効期間</th>';
divHtml += '<td colspan="3"><table border="0" cellpadding="0" cellspacing="0">';
divHtml += '<tr>';
divHtml += '<td><input name="sub_validstartdate[${key}]" type="text" id="sub_${key}_validstartdate" onchange="sub_cal_vsd[${key}].getFormValue(); sub_cal_vsd[${key}].hide();" onclick="sub_cal_vsd[${key}].write();" size="12" maxlength="10" value="" /><br /><div id="sub_${key}_caldiv_vsd"></div></td>';
divHtml += '<td> 　～　 </td>';
divHtml += '<td><input name="sub_validenddate[${key}]" type="text" id="sub_${key}_validenddate" onchange="sub_cal_ved[${key}].getFormValue(); sub_cal_ved[${key}].hide();" onclick="sub_cal_ved[${key}].write();" size="12" maxlength="10" value="2099/12/31" /><br /><div id="sub_${key}_caldiv_ved"></div></td>';
divHtml += '</tr>';
divHtml += '</table></td>';
divHtml += '</tr>';
divHtml += '</table>';
document.getElementById("sub_${key}_area").innerHTML = divHtml;

sub_cal_sdd[${key}] = new JKL.Calendar("sub_${key}_caldiv_sdd","mainForm","sub_${key}_send_date");
sub_cal_sdd[${key}].setStyle( "frame_color", "#3333CC" );
sub_cal_sdd[${key}].setStyle( "typestr", "yyyy/mm/dd" );
sub_cal_vsd[${key}] = new JKL.Calendar("sub_${key}_caldiv_vsd","mainForm","sub_${key}_validstartdate");
sub_cal_vsd[${key}].setStyle( "frame_color", "#3333CC" );
sub_cal_vsd[${key}].setStyle( "typestr", "yyyy/mm/dd" );
sub_cal_ved[${key}] = new JKL.Calendar("sub_${key}_caldiv_ved","mainForm","sub_${key}_validenddate");
sub_cal_ved[${key}].setStyle( "frame_color", "#3333CC" );
sub_cal_ved[${key}].setStyle( "typestr", "yyyy/mm/dd" );

jQuery(function (){
	jQuery('select#sub_${key}_wardstatus').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_wardcode').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_professionstatus').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_professioncode').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_deptstatus').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_deptcode').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#sub_${key}_deptgroupcode').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});
});

JSCRIPT;


		$str .= '</SCRIPT>';

		return $str;

	}
}

?>
