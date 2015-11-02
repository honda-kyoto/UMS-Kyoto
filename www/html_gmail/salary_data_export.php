<?php
/**********************************************************
* File         : salary_data_export.php
* Authors      : sumio imoto
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
// 各処理後の表示HTMLファイル
//=================================
$aryHtml[1] = "view/salary_data_export.tpl";

//=================================
// メイン
//=================================
// モード設定
if (@$_REQUEST["mode"] == "")
{
	$_REQUEST["mode"] = "init";
}

// 処理オブジェクト生成
$mgr = new salary_data_export($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 表示ファイル取得
$strHtml = $aryHtml[$ret];

// 画面表示
include_once($strHtml);

// 終了
exit;
?>
