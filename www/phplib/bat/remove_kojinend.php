<?php

$num = 0;

while ($num < 59){

	$file = "/var/www/phplib/card/load/KOJINEND.txt";

	if (file_exists($file))
	{
		unlink($file);

		file_put_contents("/var/www/phplib/card/load/KOJINSTS.txt", "\"0\",\"201408251919\",\"0\",\"0\",\"0\"" , FILE_APPEND);
	}

	$num += 1;

	print 'num = '.$num."\n";
	sleep(1);
}
?>
