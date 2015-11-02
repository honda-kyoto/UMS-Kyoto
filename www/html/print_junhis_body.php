<?php
/**********************************************************
* File         : print_junhis.php
* Authors      : sumio imoto
* Date         : 2013.05.29
* Last Update  : 2013.05.29
* Copyright    :
***********************************************************/

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/users_edit.class.php");
//=================================
// 各処理後の表示HTMLファイル
//=================================
$aryHtml[1] = "view_p/print_junhis_body.tpl";

//=================================
// メイン
//=================================
// モード設定
$_REQUEST["mode"] = "JunhisIDPrint";

// 処理オブジェクト生成
$mgr = new users_edit($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 表示ファイル取得
$strHtml = $aryHtml[$ret];

// 画面表示
include_once($strHtml);

// 終了
exit;
?>
