<?php
require_once("lib/pgsql.class.php");
require_once("lib/string.class.php");
require_once("lib/template.inc.php");
require_once("lib/msg.inc.php");

error_reporting(1);
set_time_limit(0);

function Debug_Trace($freeformat, $code)
{
	if (defined("_TRACE_"))
	{
		$pid = getmypid();
		$time = date("Y/m/d H:i:s");
		$path = TRACELOG_PATH . "Debug_" . date("Ymd") . ".log";

		if (file_exists($path))
		{
			if (!is_writable($path))
			{
				$path = TRACELOG_PATH . "Debug_" . date("Ymd") . "_2.log";
			}
		}

		$fp = fopen($path, "a+", true);

		if (is_array($freeformat) || is_object($freeformat))
		{
			$data = obj2str($freeformat);
		}
		else
		{
			$data = $freeformat;
		}
		//echo $data . "[" . $code . "]\n";
		//fprintf( $fp, "%s [%d]\n", $data, $code );
		fprintf( $fp, "[%d:%s]%s [%d]\n", $pid,$time,$data,$code );
		fclose($fp);
	}
	return;
}

function obj2str( $obj )
{
	ob_start() ;
	var_dump( $obj ) ;
	$result = ob_get_contents() ;
	ob_end_clean() ;
	return $result ;
}

function Debug_Print($value)
{
	if (defined("_TRACE_"))
	{
		if (is_array($value) || is_object($value))
		{
			$data = obj2str($value);
		}
		else
		{
			$data = $value;
		}

		print $data;
	}
	return;
}



?>
