<?php

$dir = "./";
$ido_file = 'import.csv';

if ($data = file_get_contents($dir.$ido_file, FILE_USE_INCLUDE_PATH))
{

	$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

	$aryData = explode("\n", $data);

	$cnt = 0;
	foreach ($aryData AS $body)
	{
		$cnt++;
		if ($cnt == 1)
		{
			// 1行目はタイトル
			continue;
		}
		//echo $body . "\n";
		$aryBody = explode(",", $body);
		$vals = array();
		foreach ($aryBody AS $val)
		{
			$val = trim($val);
			$val = trim($val,"\x22");
			echo $val."\n";
			
		}
//		for($i = 0; $i < strlen($str); $i++) {
//			echo $str[$i]."\n";
//		}

	}
}

exit;


?>
