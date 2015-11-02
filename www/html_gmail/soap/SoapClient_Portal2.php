<?php
    /**
     * $location:エンドポイントURI
     */
    //$location = 'http://10.1.2.16/soap/SoapServer_His.php';
    //$uri = 'http://10.1.2.16/soap/';
    $location = 'http://10.1.2.17/soap/SoapServer_Portal2.php';
    $uri = 'http://10.1.2.17/soap/';

    try {

				$staffcode = $argv[1];
				$password = $argv[2];


        /**
         * location:エンドポイント
         */
        $client = new SoapClient(
                     null,
                     array(
                         'location'=> $location,
                         'uri' => $uri,
                         'trace' => 1,           // トレース
                         // 他のサーバから接続するときはコメントをはずす
                         //'proxy_host' => '10.90.100.152',
                         //'proxy_port' => 8080
                     ));


        $result = $client->Authenticate($staffcode, $password);

        var_dump($result);


    }
    catch (Exception $e) {
        exit;
    }


?>
