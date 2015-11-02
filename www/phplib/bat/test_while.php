<?php

echo date("Y-m-d")."\n";

$count = 1;
while(1)
{
	echo "count:".$count."\n";
	echo checkCount($count)."\n";
	if(!checkCount($count))
	{
		break;
	}
	sleep(10);
	$count = $count + 1;

}

function checkCount($count)
{
	if($count < 5)
	{
		return true;
	}
	return false;
}

?>
