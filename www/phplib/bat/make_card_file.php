<?php

set_include_path('.:/usr/share/pear:/var/www/phplib');

//require_once("bat/daily_data_import.php");
		//
		// CSV作成
		//

		$aryCsvBase = array();
		//
		$aryCsvBase[] = array('header' => '0', 'val' => '{command}',			'title' => 'コマンド');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{key_number}',			'title' => '個人番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '属性');
		$aryCsvBase[] = array('header' => '1', 'val' => '{kanjiname}',			'title' => '氏名');
		$aryCsvBase[] = array('header' => '1', 'val' => '{kananame}',			'title' => 'ﾌﾘｶﾞﾅ');
		$aryCsvBase[] = array('header' => '1', 'val' => '{sexplus}',			'title' => '性別');
		$aryCsvBase[] = array('header' => '1', 'val' => '{birthday}',			'title' => '生年月日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => 'TEL番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '端末操作権限');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '通行連動番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '分類番号');
		$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_1}',		'title' => '所属１所属番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '所属１有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '所属１有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_2}',		'title' => '所属２所属番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '所属２有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '所属２有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_3}',		'title' => '所属３所属番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '所属３有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '所属３有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_4}',		'title' => '所属４所属番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '所属４有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '所属４有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{card_type}',			'title' => '認証端末１認証端末種別');
		$aryCsvBase[] = array('header' => '1', 'val' => '{uid}',				'title' => '認証端末１認証ＩＤ番号');
		$aryCsvBase[] = array('header' => '1', 'val' => '{start_date}',			'title' => '認証端末１有効期限開始日');
		$aryCsvBase[] = array('header' => '1', 'val' => '{end_date}',			'title' => '認証端末１有効期限終了日');
		$aryCsvBase[] = array('header' => '1', 'val' => '{suspend_flg}',		'title' => '認証端末１失効フラグ');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末１発行回数');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末１暗証番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末２認証端末種別');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末２認証ＩＤ番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末２有効期限開始日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末２有効期限終了日');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末２失効フラグ');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末２発行回数');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '認証端末２暗証番号');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '0', 'val' => '',	'title' => 'ダミー');
		$aryCsvBase[] = array('header' => '1', 'val' => '{sec_name}',			'title' => '文字情報01');
		$aryCsvBase[] = array('header' => '1', 'val' => '{chg_name}',			'title' => '文字情報02');
		$aryCsvBase[] = array('header' => '1', 'val' => '{post_name}',			'title' => '文字情報03');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '文字情報04');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '文字情報05');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '文字情報06');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '文字情報07');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '文字情報08');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '文字情報09');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '文字情報10');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '写真ファイル名');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '予備１');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '予備２');
		$aryCsvBase[] = array('header' => '0', 'val' => '',						'title' => '予備３');

		$title = "";
		$header = "";
		$line = "";
		$sep = "";
		foreach ($aryCsvBase AS $aryBase)
		{
			$title .= $sep . '"' . $aryBase['title'] . '"';
			$header .= $sep . '"' . $aryBase['header'] . '"';
			$line .= $sep . '"' . $aryBase['val'] . '"';
			$sep = ",";
		}

$dir = "/var/www/phplib/bat/";
$ido_file = $argv[1];
// 取り込みファイルを取得
$dir = "/var/www/phplib/bat/";
$ido_file = $argv[1];
$line_all = "";

if ($data = file_get_contents($dir.$ido_file, FILE_USE_INCLUDE_PATH))
{

	$data = mb_convert_encoding($data, "UTF-8", "shift-jis,sjis-win");

	$aryData = explode("\n", $data);
	$cnt=0;
	foreach ($aryData AS $body)
	{

		if ($cnt == 0)
		{
			// 1行目はタイトル
			$cnt++;
			continue;
		}
		else if($body=="" || $body=="\22")
		{
			continue;
		}
		else
		{
			$aryUser = explode(",", $body);

			// 更新の場合
			$command = "A";

			$kanjiname = str_replace(array("\r\n","\n","\r"),"",$aryUser[1]);
			$kananame = str_replace(array("\r\n","\n","\r"),"",$aryUser[2]);
			if( str_replace(array("\r\n","\n","\r"),"",$aryUser[4]) == "男" )
			{
				$sexplus = "1";
			}
			else
			{
				$sexplus = "2";
			}
			
			$birthday = str_replace(array("\r\n","\n","\r"),"",$aryUser[3]);
			$belong_info_1 = str_replace(array("\r\n","\n","\r"),"",$aryUser[7]);
			$belong_info_2 = str_replace(array("\r\n","\n","\r"),"",$aryUser[8]);
			$belong_info_3 = str_replace(array("\r\n","\n","\r"),"",$aryUser[9]);
			$belong_info_4 = str_replace(array("\r\n","\n","\r"),"",$aryUser[10]);


			// ハンズフリータグ：70、カード：50
			if(mb_strlen($aryUser[11]) == 5){
				$card_type = "50";
			}else if(mb_strlen($aryUser[11]) == 8){
				$card_type = "70";
				$aryUser[11] = "T" . (substr($aryUser[11], 3 , 7));
			}

			
			$uid = str_replace(array("\r\n","\n","\r"),"",$aryUser[12]);
			$start_date = "2015/03/30";
			$end_date = "2030/12/31";
			$suspend_flg = "0";
			$sec_name = "";
			$chg_name = "";
			$post_name = str_replace(array("\r\n","\n","\r"),"",$aryUser[5]);

			$aryUser[11] = str_pad($aryUser[11], 16, "0", STR_PAD_LEFT);

			// キー番号は０埋め16桁
			$key_number = $aryUser[11];

			// 置換
			$line = str_replace("{command}", $command, $line);
			$line = str_replace("{key_number}", $key_number, $line);
			$line = str_replace("{kanjiname}", $kanjiname, $line);
			$line = str_replace("{kananame}", $kananame, $line);
			$line = str_replace("{sexplus}", $sexplus, $line);
			$line = str_replace("{birthday}", $birthday, $line);
			$line = str_replace("{belong_info_1}", $belong_info_1, $line);
			$line = str_replace("{belong_info_2}", $belong_info_2, $line);
			$line = str_replace("{belong_info_3}", $belong_info_3, $line);
			$line = str_replace("{belong_info_4}", $belong_info_4, $line);
			$line = str_replace("{card_type}", $card_type, $line);
			$line = str_replace("{uid}", $uid, $line);
			$line = str_replace("{start_date}", $start_date, $line);
			$line = str_replace("{end_date}", $end_date, $line);
			$line = str_replace("{suspend_flg}", $suspend_flg, $line);
			$line = str_replace("{sec_name}", $sec_name, $line);
			$line = str_replace("{chg_name}", $chg_name, $line);
			$line = str_replace("{post_name}", $post_name, $line);
			
			$line_all .= $line . "\n";

			// 初期化
			$line = "";
			$sep = "";
			foreach ($aryCsvBase AS $aryBase)
			{
				$line .= $sep . '"' . $aryBase['val'] . '"';
				$sep = ",";
			}

		}
		$cnt++;
	}
}
		$strCsv = $title . "\n" . $header . "\n" . $line_all;

		$strCsv = mb_convert_encoding($strCsv, "sjis-win", "UTF-8");

		$ret = file_put_contents($dir . "LDKOJIN.csv", $strCsv);

?>
