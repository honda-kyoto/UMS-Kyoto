<?php
    /**
     * $location:エンドポイントURI
     */
    //$location = 'http://10.1.2.16/soap/SoapServer_His.php';
    //$uri = 'http://10.1.2.16/soap/';
    $location = 'http://10.1.2.17/soap/SoapServer_His.php';
    $uri = 'http://10.1.2.17/soap/';

    try {
        /**
         * location:エンドポイント
         */
        $client = new SoapClient(
                     null,
                     array(
                         'location'=> $location,
                         'uri' => $uri,
                         'trace' => 1,           // トレース
                         'proxy_host' => '10.90.100.152',
                         'proxy_port' => 8080
                     ));

        $result = $client->sendUserData();

        if (is_array($result))
        {
        	foreach ($result AS $aryFile)
        	{
        		$file_path = "/tmp/" . $aryFile['file_name'];
        		//$file_path = "/mnt/staffdata_test/" . $aryFile['file_name'];
        		$file_data = $aryFile['file_data'];

        		//
        		// SJISに変換
        		//
        		//echo $strUser;

        		$file_data = mb_convert_encoding($file_data, "SJIS", "UTF-8");

        		file_put_contents($file_path, $file_data, LOCK_EX);
        	}
        }

    }
    catch (Exception $e) {
        exit;
    }


?>
