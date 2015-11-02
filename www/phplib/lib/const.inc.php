<?php
/**********************************************************
* File         : const.inc.php
* Authors      : Mie Tsutsui
* Date         : 2009.09.25
* Last Update  : 2009.09.25
* Copyright    :
***********************************************************/
require_once("server.inc.php");

//======================================
// 定数
//======================================

// 文字コード
define ("HTML_CHAR_SET", "UTF-8");

define ("LIST_NAVI_MAX", "10");

define ("DEFAULT_LIST_MAX", 10);

//
// 定義区分
//

// ログフィールド 物理削除の場合
define ("LOG_DELETE_ALL_COLUMNS", '_ALL_COLUMNS_');

// ログテーブル メールの場合
define ("LOG_DAILY_REPORT", '_DAILY_REPORT_');
define ("LOG_DAILY_SUBJCT", '_SUBJECT_');
define ("LOG_DAILY_BODY", '_BODY_');

define ("AGE_MAX", 85);
define ("AGE_MIN", 16);

define ("USER_TYPE_NORMAL", "1");
define ("USER_TYPE_SYSADM", "2");
define ("USER_TYPE_INFGRZ", "3");
define ("USER_TYPE_NCVCADM", "5");

define ("ENTRY_STATUS_ENTRY", "0");
define ("ENTRY_STATUS_AGREE", "1");
define ("ENTRY_STATUS_CANCEL", "6");
define ("ENTRY_STATUS_REJECT", "9");

define ("ENTRY_KBN_ADD", "1");
define ("ENTRY_KBN_EDIT", "2");
define ("ENTRY_KBN_DEL", "9");

define ("SENDER_KBN_LIMIT", "0");
define ("SENDER_KBN_FREE", "1");

define ("SENDER_SET_TYPE_MEMBER", "0");
define ("SENDER_SET_TYPE_LIMIT", "1");

define ("MLIST_KBN_MEMBER", "0");
define ("MLIST_KBN_AUTO", "1");

define ("WIRE_KBN_WIRED", "1");
define ("WIRE_KBN_WLESS", "2");
define ("WIRE_KBN_FREE", "9");

define ("IP_KBN_DHCP", "1");
define ("IP_KBN_FIXD", "2");
define ("IP_KBN_FREE", "9");

define ("JOUKIN_KBN_FULLTIME", "0");
define ("JOUKIN_KBN_PARTTIME", "1");
define ("JOUKIN_KBN_OTHER", "9");

// DHCP機器ダミーIPアドレス（ソート処理用の為、画面上表示しない）
define ("DUMY_IP_ADDR_DHCP", "999.999.999.998");
// 未割当機器ダミーIPアドレス（ソート処理用の為、画面上表示しない）
define ("DUMY_IP_ADDR_NONE", "999.999.999.999");

// 変動ロール
define ("ROLE_ID_VLAN_ADMIN", "101");
define ("ROLE_ID_MLIST_ADMIN", "102");
define ("ROLE_ID_VPN_ADMIN", "103");

define ("VPN_KBN_VPN", "1");
define ("VPN_KBN_PASSLOGIC", "2");


// ファイル転送利用権限
define ("FTRANS_USER_KBN_USR", "1");
define ("FTRANS_USER_KBN_ADM", "2");


$GLOBALS['entry_kbn_status'] = array(
	ENTRY_KBN_ADD."_".ENTRY_STATUS_ENTRY => "登録申請中",
	ENTRY_KBN_ADD."_".ENTRY_STATUS_REJECT => "登録却下",
	ENTRY_KBN_EDIT."_".ENTRY_STATUS_ENTRY => "更新申請中",
	ENTRY_KBN_EDIT."_".ENTRY_STATUS_REJECT => "更新却下",
	ENTRY_KBN_DEL."_".ENTRY_STATUS_ENTRY => "削除申請中",
	ENTRY_KBN_DEL."_".ENTRY_STATUS_REJECT => "削除却下",
	'agreed' => "承認済み",
);

$GLOBALS['entry_kbn'] = array(
	ENTRY_KBN_ADD => "新規登録",
	ENTRY_KBN_EDIT => "更新",
	ENTRY_KBN_DEL => "削除",
);

$GLOBALS['entry_status'] = array(
	ENTRY_STATUS_ENTRY => "申請中",
	ENTRY_STATUS_AGREE => "承認済み",
	ENTRY_STATUS_CANCEL => "キャンセル",
	ENTRY_STATUS_REJECT => "却下",
);

$GLOBALS['sender_kbn'] = array(
	SENDER_KBN_LIMIT => "送信可能者を指定",
	SENDER_KBN_FREE => "不特定多数が送信可能",
);

$GLOBALS['sender_set_type'] = array(
		SENDER_SET_TYPE_LIMIT => "送信可能者を別途指定",
		SENDER_SET_TYPE_MEMBER => "メンバーが送信可能",
);

$GLOBALS['mlist_kbn'] = array(
		MLIST_KBN_MEMBER => "個別指定",
		MLIST_KBN_AUTO => "動的配布",
);

$GLOBALS['wire_kbn'] = array(
		WIRE_KBN_WIRED => "有線",
		WIRE_KBN_WLESS => "無線",
);

$GLOBALS['ip_kbn'] = array(
		IP_KBN_DHCP => "DHCP",
		IP_KBN_FIXD => "固定IP",
);

$GLOBALS['vpn_kbn'] = array(
		VPN_KBN_VPN => "VPN",
		VPN_KBN_PASSLOGIC => "Passlogic",
);

$GLOBALS['ftrans_user_kbn'] = array(
		FTRANS_USER_KBN_USR => '利用者',
		FTRANS_USER_KBN_ADM => '管理者',
);

$GLOBALS['his_history_kbn'] = array(
		'0' => '指定しない',
		'1' => '部署異動',
		'2' => '役職変更',
		'3' => '氏名変更',
		'9' => 'その他（多目的）',
);

//
// 定義配列
//
// ソートフィールド用データ
$GLOBALS['sort_data']['src'][0] = 'image/sortdown_out.gif';
$GLOBALS['sort_data']['src'][1] = 'image/sortup_out.gif';
$GLOBALS['sort_data']['msg'][0] = '昇順で並び替え';
$GLOBALS['sort_data']['msg'][1] = '降順で並び替え';



// 性別
$GLOBALS['sex'] = array(
	'0' => '男性',
	'1' => '女性',
);

// 性別
$GLOBALS['joukin_kbn'] = array(
	JOUKIN_KBN_FULLTIME => '常勤',
	JOUKIN_KBN_PARTTIME => '非常勤',
	JOUKIN_KBN_OTHER => '委託・その他',
);

// 統合IDのランダム2文字
$GLOBALS['rand_tow_chars'] = array('ac','bj','cs','dt','ek','fb','gt','hr','im','jn','kz','mv','nd','py','qc','rp','sd','te','ub','vx','xc','yg','zk');

$GLOBALS['wardstatus'] = array(
		'1' => '外来',
		'2' => '病棟',
		'4' => 'その他',
		);

$GLOBALS['professionstatus'] = array(
		'1' => '医師',
		'2' => '看護師',
		'3' => '薬剤師',
		'4' => '栄養士',
		'5' => '放射線技師',
		'6' => '検査技師',
		'9' => 'システム管理',
		'0' => 'その他',
);

$GLOBALS['deptstatus'] = array(
		'1' => '医科',
		'2' => '歯科',
		'3' => '検査科',
		'0' => 'その他',
);


$GLOBALS['card_reason'] = array(
		'1' => '紛失',
		'2' => '退職',
		'3' => '勤務形態変更',
		'9' => 'その他',
);

$GLOBALS['reissue_kbn'] = array(
		'1' => '紛失',
		'2' => '破損',
		'9' => 'その他',
);
$GLOBALS['disuse_kbn'] = array(
		'1' => '退職',
		'2' => '勤務形態変更',
		'9' => 'その他',
);


// 一覧表示件数
$GLOBALS['list_max'] = array(
//	  1 =>   '1件',
//	  2 =>   '2件',
//	  3 =>   '3件',
//	  5 =>   '5件',
	 10 =>  '10件',
	 20 =>  '20件',
	 50 =>  '50件',
	100 => '100件',
);

$GLOBALS['hour12'] = array(
	'00' => '00',
	'01' => '01',
	'02' => '02',
	'03' => '03',
	'04' => '04',
	'05' => '05',
	'06' => '06',
	'07' => '07',
	'08' => '08',
	'09' => '09',
	'10' => '10',
	'11' => '11',
);

$GLOBALS['hour24'] = array(
	'00' => '00',
	'01' => '01',
	'02' => '02',
	'03' => '03',
	'04' => '04',
	'05' => '05',
	'06' => '06',
	'07' => '07',
	'08' => '08',
	'09' => '09',
	'10' => '10',
	'11' => '11',
	'12' => '12',
	'13' => '13',
	'14' => '14',
	'15' => '15',
	'16' => '16',
	'17' => '17',
	'18' => '18',
	'19' => '19',
	'20' => '20',
	'21' => '21',
	'22' => '22',
	'23' => '23',
);

$GLOBALS['min5'] = array(
	'00' => '00',
	'05' => '05',
	'10' => '10',
	'15' => '15',
	'20' => '20',
	'25' => '25',
	'30' => '30',
	'35' => '35',
	'40' => '40',
	'45' => '45',
	'50' => '50',
	'55' => '55',
);


// 曜日
$GLOBALS['dow_text'] = array(
	'0' => '日',
	'1' => '月',
	'2' => '火',
	'3' => '水',
	'4' => '木',
	'5' => '金',
	'6' => '土',
);

$GLOBALS['number_mark'] = array("", "①", "②", "③", "④", "⑤", "⑥", "⑦", "⑧", "⑨", "⑩", "⑪", "⑫", "⑬", "⑭", "⑮", "⑯", "⑰", "⑱", "⑲", "⑳");


// パスワード文字振り仮名
$GLOBALS['passwd_furigana'] = array(
	'0' => 'ゼロ',
	'1' => 'イチ',
	'2' => 'ニ',
	'3' => 'サン',
	'4' => 'ヨン',
	'5' => 'ゴ',
	'6' => 'ロク',
	'7' => 'ナナ',
	'8' => 'ハチ',
	'9' => 'キュウ',
	'a' => 'エー',
	'b' => 'ビー',
	'c' => 'シー',
	'd' => 'ディー',
	'e' => 'イー',
	'f' => 'エフ',
	'g' => 'ジー',
	'h' => 'エイチ',
	'i' => 'アイ',
	'j' => 'ジェイ',
	'k' => 'ケイ',
	'l' => 'エル',
	'm' => 'エム',
	'n' => 'エヌ',
	'o' => 'オー',
	'p' => 'ピー',
	'q' => 'キュー',
	'r' => 'アール',
	's' => 'エス',
	't' => 'ティー',
	'u' => 'ユー',
	'v' => 'ヴイ',
	'w' => 'ダブリュー',
	'x' => 'エックス',
	'y' => 'ワイ',
	'z' => 'ゼット',
	'A' => 'エー',
	'B' => 'ビー',
	'C' => 'シー',
	'D' => 'ディー',
	'E' => 'イー',
	'F' => 'エフ',
	'G' => 'ジー',
	'H' => 'エイチ',
	'I' => 'アイ',
	'J' => 'ジェイ',
	'K' => 'ケイ',
	'L' => 'エル',
	'M' => 'エム',
	'N' => 'エヌ',
	'O' => 'オー',
	'P' => 'ピー',
	'Q' => 'キュー',
	'R' => 'アール',
	'S' => 'エス',
	'T' => 'ティー',
	'U' => 'ユー',
	'V' => 'ヴイ',
	'W' => 'ダブリュー',
	'X' => 'エックス',
	'Y' => 'ワイ',
	'Z' => 'ゼット',
);

if (!defined("STAFF_ID_LEN"))
{
	define ("STAFF_ID_LEN", 6);
}

?>
