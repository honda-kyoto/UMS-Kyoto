<?php
/**********************************************************
* File         : mlist_auto_members.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/mlists_members.class.php");

//=================================
// 各処理後の表示HTMLファイル
//=================================
$aryHtml[1] = "view/mlist_auto_members.tpl";

//=================================
// メイン
//=================================
// モード設定
if (@$_REQUEST["mode"] == "")
{
	$_REQUEST["mode"] = "view";
}

// 処理オブジェクト生成
$mgr = new mlists_members($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 表示ファイル取得
$strHtml = $aryHtml[$ret];

// 画面表示
include_once($strHtml);

// 終了
exit;
?>