<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");
$encode='UTF-8';
$str = $argv[1];
$len = 20;
$pad = ' ';
		$str_len = string::strlen($str);
print_r($str_len);
print_r('|');
		if ($str_len > $len)
		{
print_r('cut');
			return mb_substr($str, 0, $len);
		}

		while ($str_len < $len)
		{
			$str .= $pad;
			$str_len = string::strlen($str);
		}
print_r(PHP_EOL);
print_r('|');
print_r($str);
print_r('|');
print_r(PHP_EOL);


$arr = str_split($str); //1�o�C�g���z��ɕ���
foreach ($arr as $ch)
echo dechex(ord($ch)); //16�i�R�[�h�ŕ\��


exit;


?>