#!/usr/bin/php
<?php
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

require_once('OpenWeatherMap-PHP-Api/Examples/bootstrap.php');
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function getWeatherFromZipCode($omw, $lang, $units, $zipcode, $countryCode){
	$location = 'zip:'.$zipcode.','.$countryCode;
	$weather = $omw->getWeather($location,$units,$lang);
	return $weather;
}
function getWeatherFromName($omw, $lang, $units, $city, $countryCode){
        $location = $city.','.$countryCode;
        $weather = $omw->getWeather($location,$units,$lang);
	return $weather;
  
}
function getWeatherFromCoordinates($omw ,$lang, $units, $lat, $lon)
{
	$location = array('lat'=>$lat,'lon'=>$lon);
	$weather = $omw->getweather($location,$units,$lang);
	return $weather;
}

function requestProcessor($request)
{
	$cli = false;
	$lf = '<br>';
	if(php_sapi_name() ==='cli'){
      		$lf ="\n";
        	$cli = true;
	}
	$lang = 'en';
	$units = 'imperial';
	$omw = new OpenWeatherMap();
	$myApiKey = '2f8796eefe67558dc205b09dd336d022';
	$omw->setApiKey($myApiKey);

	echo "recieved request".PHP_EOL;
	var_dump($request);
	if(!isset($request['type']))
	{
		echo  "Error: Unsupported Message Type";
	}
	switch($request['type'])
	{
	case 'getWeatherFromName':
		echo '1';
		$weather = getWeatherFromName($omw,$lang,$units,$request['city'],$request['countryCode']);
		break;
	case 'getWeatherFromZip':
		echo '2';
		$weather = getWeatherFromZipCode($omw,$lang,$units,$request['zipCode'],$request['countryCode']);
		break;
	case 'getWeatherFromCoordinates':
		echo '3';
		$weather = getWeatherFromCoordinates($omw,$lang,$units,$request['lat'],$request['lon']);
		break;
	}

	$city = $weather->city->name;
        $country = $weather->city->country;
        $currentTemp = $weather->temperature->now;
        $minTemp = $weather->temperature->min;
        $maxTemp = $weather->temperature->max;
        $precipitation = $weather->precipitation;
        $lat = $weather->city->lat;
        $lon = $weather->city->lon;

         return array('city'=>$city,'country'=>$country,'latitude'=>$lat, 'longitude'=>$lon,'currentTemp'=>strVal($currentTemp),'minTemp'=>strVal($minTemp),'maxTemp'=>strVal($maxTemp),'precipitation'=>strVal($precipitation));


	return array("return"=>'0','message'=>"Server recieved and request");
}

$server = new rabbitMQServer("weatherServer_Backup.ini","testServer");
$serverResponse = $server->process_requests('requestProcessor');
echo $serverResponse;
/*if($serverResponse = "FAIL PROCESSOR"){

	$server = new RabbitMQServer("weatherServer_Backup.ini","testServer");
	$serverResponse = $server->process_requests('requestProcessor');
}*/
exit();

?>
