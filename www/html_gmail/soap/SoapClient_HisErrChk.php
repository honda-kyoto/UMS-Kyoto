<?php
    /**
     * $location:エンドポイントURI
     */
    //$location = 'http://10.1.2.16/soap/SoapServer_His.php';
    //$uri = 'http://10.1.2.16/soap/';
    $location = 'http://10.1.2.17/soap/SoapServer_His.php';
    $uri = 'http://10.1.2.17/soap/';

    try {


    	$dir = "/mnt/error";
    	$aryFile = array();
    	if ($handle = opendir($dir))
    	{
    		$key = 0;
    		while (false !== ($entry = readdir($handle)))
    		{
    			if ($entry != "." && $entry != "..")
    			{
    				$aryFile[$key]['file_name'] = $entry;
    				$aryFile[$key]['file_data'] = file_get_contents($dir."/".$entry);

    				unlink($dir."/".$entry);

    				$key++;
    			}
    		}
    		closedir($handle);
    	}

    	return $aryFile;


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


        $result = $client->receiveErrorData($aryFile);


    }
    catch (Exception $e) {
        exit;
    }


?>
