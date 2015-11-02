<?php
/**********************************************************
* File         : salary_data_export_mgr.class.php
* Authors      : sumio imoto
* Date         : 2013.05.23
* Last Update  : 2013.05.23
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/salary_data_export_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class salary_data_export_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getLastOutputData()
	{
		$sql = $this->getQuery('GET_SALARY_LAST_OUTPUT');
		return $this->oDb->getOne($sql);
	}

	function downloadData($request)
	{
		$extension = pathinfo($request['file_name'], PATHINFO_EXTENSION);

		$file_path = EXPTEMP_PATH . $request['file_name'];

		$strUser = file_get_contents($file_path);

		unlink ($file_path);
		
		if ($request['end_date'] == "") 
		{
			$end_date = date("Ymd");
		}
		else
		{
			$end_date = $request['end_date'];
		}

		$condition = str_replace("/", "", $request['start_date']) . "～" . str_replace("/", "", $end_date);
		$filename = "給与明細データ" . "_" . $condition . "." . $extension;

		$ret = $this->getLastOutputData();
		if ($ret != "")
		{
			$sql = $this->getQuery("UPDATE_SALARY_LAST_OUTPUT");
		}
		else
		{
			$sql = $this->getQuery("INSERT_SALARY_LAST_OUTPUT");
		}
		
		$ret = $this->oDb->query($sql);
		
		if (!$ret)
		{
			//　エラーの場合は、ログ出力するが処理はDL済みのためエラーにしない
			Debug_Trace("給与明細データ出力日時の保存失敗　SQL=" . $sql, 0);
		}
		
		$this->strDl($filename, $strUser);
		
	}

	function outputData($start_date, $end_date)
	{
		$args = array();
		$args['COND'] = "";

		$aryCond = array();

		if($start_date != "") 
		{
			$aryCond[] = "UST.make_time::DATE >= TO_DATE('" .string::replaceSql($start_date) . "', 'YYYY/MM/DD') ";
		}
		
		if($end_date != "") 
		{
			$aryCond[] = "UST.make_time::DATE <= TO_DATE('" .string::replaceSql($end_date) . "', 'YYYY/MM/DD') ";
		}

		if (count($aryCond) > 0)
		{
			$args['COND'] = " WHERE " . join(" AND ", $aryCond);
		}

		$sql = $this->getQuery('GET_SALARY_OUTPUT_DATA', $args);

		$aryRet = $this->oDb->getAll($sql);

		$strUser = "";
		if (is_array($aryRet) && count($aryRet) > 0 )
		{
			foreach ($aryRet AS $data)
			{
				// 給与番号（職員IDの先頭1ケタ無し）
				$strUser .= '"' . $data['salary_no'] . '"';

				// 基本情報	氏名　
				$strUser .= ',"' . $data['kanjisei'] . "　" . $data['kanjimei'] . '"';

				// 所属名（空白）
				$strUser .= ',""';
				
				// 統合ID
				$strUser .= ',"' . $data['login_id'] . '"';
				
				// 給与明細パスワード
				$strUser .= ',"' . $this->passwordDecrypt($data['salary_passwd']) . '"';
				
				// 職員ID
				$strUser .= ',"' . $data['make_time'] . '"';
				
				$strUser .= "\r\n";

			}
		}
		else
		{
			echo "0|対象期間のデータは存在しません。";
			return;
		}
		
		$file = "給与明細データ" . microtime(true) . ".csv";
		$file_path = EXPTEMP_PATH . $file;

		$bytes = file_put_contents($file_path, $strUser);

		echo "1|".$file;
	}
}

?>
