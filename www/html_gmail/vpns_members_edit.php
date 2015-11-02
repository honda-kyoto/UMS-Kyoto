<?php
/**********************************************************
* File         : vpns_members_edit.php
* Authors      : sumio imoto
* Date         : 2013.06.19
* Last Update  : 2013.06.19
* Copyright    :
***********************************************************/

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/vpns_members_edit.class.php");

//=================================
// 各処理後の表示HTMLファイル
//=================================
$aryHtml[1] = "view/vpns_members_edit.tpl";

//=================================
// メイン
//=================================
// モード設定
if (@$_REQUEST["mode"] == "")
{
	$_REQUEST["mode"] = "init";
}

// 処理オブジェクト生成
$mgr = new vpns_members_edit($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 表示ファイル取得
$strHtml = $aryHtml[$ret];

// 画面表示
include_once($strHtml);

// 終了
exit;
?>
