#!/usr/bin/php
<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function reportError($message){
	$report=sendMessage($message,'error');
	if($report==false){
		echo 'failed communicating to broker'; 
	}
}
function reportCommuncation($message){
	sendMessage($message,'network');
	//if($report==false){
	//	echo 'failed communicating to broker'; 
	//}	
}
function sendMessage($message,$type){
	$client = new RabbitMQClient("networkLogger.ini","testServer");
	$request=array();
	$request['type']=$type;
	$request['message']=$message;
	$response=$client->send_request($request);
	if ($response=="MESSAGE FAIL"){
                $client = new rabbitMQClient("networkLogger_Backup.ini","testServer");
                $response = $client->send_request($request);
	}
	echo "Message Sent";
	return $response;

}

echo reportCommuncation("test");

?>
