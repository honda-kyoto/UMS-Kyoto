<?php
/**********************************************************
* File         : salaryDataExport.php
* Authors      : suio imoto
* Date         : 2013.05.23
* Last Update  : 2013.05.23
* Copyright    :
***********************************************************/

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/salary_data_export.class.php");

//=================================
// メイン
//=================================
$_POST["mode"] = "output";

// 処理オブジェクト生成
$mgr = new salary_data_export($_POST);

// 処理開始
$ret = $mgr->run();

// 終了
exit;

?>
