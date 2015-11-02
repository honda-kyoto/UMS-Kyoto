<?php 
require_once('SOAP/Server.php'); 

function SendMessage($array){ 
  //$result = $str." ".date("Y-m-d"); 
  $params = array('status' =>  "345"
, 'errormessage' =>  "444"
);
  return $params; 
} 

$server = new SOAP_Server(); 
$server->addToMap('SendMessage', array(), array()); 
$server->service($HTTP_RAW_POST_DATA); 
?> 
