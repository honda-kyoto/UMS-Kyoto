<?php
/**********************************************************
* File         : ido_data_import.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/users_detail_mgr.class.php");
require_once("sql/users_detail_sql.inc.php");


// �d�����O�o�͊֐�
function writeDoubleLog($where, $condition, $err_msg, $aryRet)
{
	global $oMgr;
	global $fields_value;

//	// �ސE���������Ă��܂�Ȃ����߁A�����݃`�F�b�N�t���O����U�O�ɍX�V
//	$sql = "update idodata set notexist_flg = '0' " . $where;
//
//	$ret = $oMgr->oDb->query($sql);
//
//	if (!$ret)
//	{
//		echo "���������Ɏ��s���܂����B";
//		exit;
//	}

	echo "��".$condition."�ŕ����f�[�^�Ɉ�v�F" . $err_msg;
	echo "\n";
	$line = 0;
	foreach ($aryRet AS $aryExt)
	{a
		$line++;
		echo "---" . $line . "����---\n";
		echo "���p��ID�F" . $aryExt['user_id'];
		echo "\n";
		foreach ($fields_value AS $key => $title)
		{
			echo $title . "�F" . $aryExt[$key];
			echo "\n";
		}
	}
	echo "---�ȏ�" . $line . "��---\n\n";
}


$dir = "import/";
$ido_file = 'idodata.csv';

$fields_value = array(
'cteiinkb' => '����敪',
'cteiinnm' => '����敪����',
'cshainno' => '�E���ԍ�',
'dhtreingb_dte' => '���ߔN����(����)',
'nnmn_ido_cde' => '�C�ƈٓ���ڃR�[�h',
'nnmn_ido_nme' => '�C�ƈٓ����',
'cnamekna' => '�J�i����',
'cnameknj' => '��������',
'kyu_kn_nme' => '�����g�p�J�i����',
'kyu_kj_nme' => '�����g�p��������',
'seibetu_kbn' => '���ʋ敪',
'seibetu_nme' => '����',
'dbirth_dte' => '���N����(����)',
'dsaiyo_dte' => '���ƌ������̗p��(����)',
'dninyo_dte' => '�C�p�N����(����)',
'kkn_cde' => '�@�փR�[�h',
'kkn_nme' => '�@�֖���',
'szk_cde' => '�����R�[�h',
'szk_nme' => '��������',
'bkyk_cde' => '���ǃR�[�h',
'bkyk_nme' => '���ǖ���',
'kkrkoza_cde' => '�|�E�u���R�[�h',
'kkrkoza_nme' => '�|�E�u������',
'knmei_cde' => '�����R�[�h',
'knmei_nme' => '��������',
'syksy_cde' => '�E��R�[�h',
'syksy_nme' => '�E�햼��',
'hjksyk_skin_cde' => '���ΐE���E���R�[�h',
'hjksyk_skin_nme' => '���ΐE���E��',
'hjksyk_misy_cde' => '���ΐE�����̃R�[�h',
'hjksyk_misy_nme' => '���ΐE������',
'dnnki_mr_dte' => '�C�������N����(����)',
'djosin_prt_dte' => '��\��������i����j',
'djirei_prt_dte' => '���߈�����i����j',
'getuji_flg' => '�����X�V�t���O',
);

$fields = array_keys($fields_value);

$has_error = false;
$oMgr = new users_detail_mgr();

//
// �t�@�C�����`�F�b�N
//
if ($data = file_get_contents($dir.$ido_file, FILE_USE_INCLUDE_PATH))
{
	// �����݃`�F�b�N�t���O����U�P�ɍX�V�i�ސE�����ς݂͏����j
//	$sql = "update idodata set notexist_flg = '1' where retire_fin_flg = '0'";

//	$ret = $oMgr->oDb->query($sql);
//
//	if (!$ret)
//	{
//		echo "���������Ɏ��s���܂����B";
//		exit;
//	}

	$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

	$aryData = explode("\n", $data);

	$cnt = 0;
	foreach ($aryData AS $body)
	{
		$cnt++;
		if ($cnt == 1)
		{
			// 1�s�ڂ̓^�C�g��
			continue;
		}

		$aryBody = explode(",", $body);

		$user_exists = false;
		$vals = array();
		foreach ($aryBody AS $key => $val)
		{
			$val = trim($val);
			$val = trim($val,"\x22");

			// ��΋敪���擾
			if ($fields[$key] == "cteiinkb")
			{
				if ($val == "1")
				{
					$joukin_kbn = JOUKIN_KBN_FULLTIME;
					$post_code_nm = "syksy_cde";
					$job_code_nm = "knmei_cde";
				}
				else
				{
					$joukin_kbn = JOUKIN_KBN_PARTTIME;
					$post_code_nm = "hjksyk_misy_cde";
					$job_code_nm = "hjksyk_skin_cde";
				}
			}

			// �J�i���͑S�p��
			if ($fields[$key] == "cnamekna" || $fields[$key] == "kyu_kn_nme")
			{
				$val = string::han2zen($val);
				$val = str_replace(" ", "�@", $val);
			}

			// ���΂̏ꍇ��E�R�[�h�ɕ������t�^�i���ꏈ���j
			if ($fields[$key] == "hjksyk_skin_cde")
			{
				if ($joukin_kbn == JOUKIN_KBN_PARTTIME)
				{
					$val = "HJK_" . $val;
				}
			}

			// �|�u�����u000000�v�̏ꍇ�A��ɂ���
			if ($fields[$key] == "kkrkoza_cde")
			{
				if ($val == "000000")
				{
					$val = "";
				}
			}

			$vals[$fields[$key]] = $val;
		}

		$sqlCshainno = $oMgr->sqlItemChar($vals['cshainno']);
		$sqlCnameknj = $oMgr->sqlItemChar($vals['cnameknj']);
		$sqlDbirthDte = $oMgr->sqlItemChar($vals['dbirth_dte']);
		$sqlSeibetuKbn = $oMgr->sqlItemChar($vals['seibetu_kbn']);


		// ����WHERE��
		$where1 = <<< SQL
 WHERE
  retire_fin_flg = '0' and
  cnameknj = $sqlCnameknj and
  dbirth_dte = $sqlDbirthDte and
  seibetu_kbn = $sqlSeibetuKbn;
SQL;

		$where2 = <<<SQL
WHERE
  retire_fin_flg = '0' and
  cshainno = $sqlCshainno;
SQL;

		$where3 = <<< SQL
 WHERE
  retire_fin_flg = '0' and
  cnameknj = $sqlCnameknj and
  dbirth_dte = $sqlDbirthDte and
  seibetu_kbn = $sqlSeibetuKbn and
  cshainno = $sqlCshainno;
SQL;


		//
		// �������V�K���`�F�b�N
		//
		if ($vals['cnameknj'] != "" || $vals['cshainno'] != "")
		{

			$where = $where2;	// �����͐E���ԍ����Z�b�g

			$sql = "select * from idodata " . $where;

			$aryRet = $oMgr->oDb->getAll($sql);


			// ��������ꍇ
			if (is_array($aryRet) && count($aryRet) >= 2)
			{
				// ���N�����ƐE���ԍ�����v������̂��Ȃ����`�F�b�N
				$hit_data_exists = false;
				foreach ($aryRet AS $no => $aryExt)
				{
					if ($aryExt['cshainno'] == $vals['cshainno'])
					{
						$aryResult = $aryExt;
						$where = $where3;	// ������ύX
						$hit_data_exists = true;
					}
				}

				// ��v������̂��Ȃ��ꍇ�̓��O�ɏo��
				if (!$hit_data_exists)
				{
					writeDoubleLog($where, "�����E���N�����E����", $err_msg, $aryRet);

					continue;
				}

			}
			else
			{
				$aryResult = @$aryRet[0];
			}

			// �����A���N�����A���ʂň�v����f�[�^�����݂��Ȃ������ꍇ
			if (!isset($aryResult['user_id']))
			{
				$err_msg = "[�E���ԍ��F" . $vals['cshainno'] . "�A�����F" . $vals['cnameknj'] . "�A���N�����F" . $vals['dbirth_dte'] . "�A���ʁF" . $vals['seibetu_nme'] . "]";

				$where = $where1;	// �����͎����A���N�����A���ʂ��Z�b�g

				$sql = "select * from idodata " . $where;

				$aryRet = $oMgr->oDb->getAll($sql);

				// ��������ꍇ
				if (is_array($aryRet) && count($aryRet) >= 2)
				{
					writeDoubleLog($where, "���E���ԍ�", $err_msg, $aryRet);
					echo $err_msg."�F���d���f�[�^\n";
					
					continue;
				}
				else
				{
//					echo $err_msg."�F���V�K�f�[�^\n";
				}
			}

			$user_id = "";

			if (isset($aryResult['user_id']))
			{
				$user_id = $aryResult['user_id'];

				// �ύX�����邩�`�F�b�N
				$has_change_data = false;
				foreach ($fields AS $key => $col)
				{
					if ($aryRet[$col] != $vals[$col])
					{
						// ����
						$has_change_data = true;
						break;
					}
				}

				$sql = "update idodata set update_time = now()";
				if ($has_change_data)
				{
					// �ύX������ꍇ�̓f�[�^�X�V�{�����󋵁��X�V����i�P�j�ɕύX
					foreach ($fields AS $key => $col)
					{
						$sql .= "," . $col . " = '" . $vals[$col] . "'";
					}
				}
				$sql .= ", notexist_flg = '0' ";

				$sql .= $where;
				echo $where."�F���X�V�f�[�^\n";

			}
			else
			{
				$has_change_data = true;

				$sql = "insert into idodata (";
				$sql .= "user_id,";
				$sql .= implode(",", $fields);
				$sql .= ") values (";
				$sql .= "NULL";
				foreach ($fields AS $key => $col)
				{
					$sql .= ",'" . $vals[$col] . "'";
				}
				$sql .= ")";
				echo $where."�F���V�K�f�[�^\n";

			}

			// �X�V�A�ǉ��łȂ���΃X�L�b�v
			if (!$has_change_data)
			{
				continue;
			}

			// �A�g���ڂ�ҏW���Ď擾
			$sql = <<< SQL
SELECT
user_id,
cshainno as staff_id,
cshainno as login_id,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(kyu_kj_nme, 0, position('�@' in kyu_kj_nme)))
else
(select substr(cnameknj, 0, position('�@' in cnameknj)))
end as kanjisei,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(kyu_kj_nme, position('�@' in kyu_kj_nme)+1))
else
(select substr(cnameknj, position('�@' in cnameknj)+1))
end as kanjimei,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(kyu_kn_nme, 0, position('�@' in kyu_kn_nme)))
else
(select substr(cnamekna, 0, position('�@' in cnamekna)))
end as kanasei,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(kyu_kn_nme, position('�@' in kyu_kn_nme)+1))
else
(select substr(cnamekna, position('�@' in cnamekna)+1))
end as kanamei,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(cnameknj, 0, position('�@' in cnameknj)))
else '' end as kanjisei_real,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(cnameknj, position('�@' in cnameknj)+1))
else '' end as kanjimei_real,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(cnamekna, 0, position('�@' in cnamekna)))
else '' end as kanasei_real,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(cnamekna, position('�@' in cnamekna)+1))
else '' end as kanamei_real,
case when seibetu_kbn = '1' then '0' when seibetu_kbn = '2' then '1' end as sex,
dbirth_dte as birthday,
case
when kkrkoza_cde != '0' and kkrkoza_cde != '' then
(select belong_chg_id from belong_chg_mst where belong_chg_code = kkrkoza_cde)
when szk_cde != '0' and szk_cde != '' then
(select belong_chg_id from belong_chg_mst where belong_chg_code = szk_cde)
when bkyk_cde != '0' and bkyk_cde != '' then
(select belong_chg_id from belong_chg_mst where belong_chg_code = bkyk_cde)
else
(select belong_chg_id from belong_chg_mst where belong_chg_code = 'XXXXXX')
end
as belong_chg_id,
(select post_id from post_mst where post_code = $post_code_nm) as post_id,
(select job_id from job_mst where job_code = $job_code_nm) as job_id,
'$joukin_kbn' as joukin_kbn,
'1' as user_type_id,
to_char(now(),'yyyy/MM/dd hh24:mm:ss') as start_date
FROM
idodata
$where
SQL;

			$request = $oMgr->oDb->getRow($sql);
			list ($y, $m, $d) = explode("/", $request['birthday']);
			$request['birth_year'] = $y;
			$request['birth_mon'] = $m;
			$request['birth_day'] = $d;


			if ($user_id != "")
			{
//�X�V

			}
			else
			{
//�V�K

			}

		}
	}

}



	echo "�������I�����܂����B\n";
exit;


?>