<?php

set_include_path('.:/usr/share/pear:/var/www/phplib');

//require_once("bat/daily_data_import.php");

$dir = "/var/www/phplib/import/";
$ido_file = $argv[1];

if ($data = file_get_contents($dir.$ido_file, FILE_USE_INCLUDE_PATH))
{

$data = mb_convert_encoding($data, "UTF-8", "UTF-8");

$aryData = explode("\n", $data);
$cnt=0;
$header='';
	foreach ($aryData AS $body)
	{

		if ($cnt == 0)
		{
			// 1行目はタイトル
			$header = $body;
			$cnt++;
			continue;
		}
		else if($body=="" || $body=="\22")
		{
		}
		else
		{
			$csv = mb_convert_encoding($header."\n".$body, "UTF-8", "UTF-8");
			file_put_contents("/var/www/phplib/import/ikkatsu_".$cnt.".csv", $csv);
		}
		$cnt++;
	}
	print_r($cnt."件終了しました"."\n");

}


?>
