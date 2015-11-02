<?php
/**
 * med_work.php
 * 
 * @author		hiroyuki honda
 * @date		2015-10-21
 * @copyright	
 * @version		1.0.0
 */

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/med_work.class.php");
//=================================
// 各処理後の表示HTMLファイル
//=================================
$aryHtml[1] = "view/med_work.tpl";

//=================================
// メイン
//=================================
// モード設定
if (@$_REQUEST["mode"] == "")
{
	$_REQUEST["mode"] = "init";
}

// 処理オブジェクト生成
$mgr = new med_work($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 表示ファイル取得
$strHtml = $aryHtml[$ret];

// 画面表示
include_once($strHtml);

// 終了
exit;
?>
