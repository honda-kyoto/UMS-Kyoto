<?php
class Logmessage{

	function __construct(){

	}


	public function message($message,$func){
		// 通常メッセージ
		if($func =='log'){
			echo $message;
			ob_flush();
			flush();

		// 開始メッセージ
		}elseif($func == 'start'){
			echo $message;
			echo str_pad(" ",4096)."<br />\n";

			ob_end_flush();
			ob_start('mb_output_handler');

		// 終了メッセージ
		}elseif($func == 'end'){
			echo $message;
			sleep(1);
		}
	}
}