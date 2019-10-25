<?php
	if ($fh = fopen('../res/.weatherUserID', 'r')) {
		$userID = fgets($fh);
		fclose($fh);
	}
	else {
		echo 'Errore durante la ricezione dell\'userID';
		exit();
	}
	$cityID = '3171829';
	// $userID = '79067760b1aa2fc545335e0877de93a6';
	$url = 'http://api.openweathermap.org/data/2.5/forecast?id=' . $cityID . '&units=metric&APPID=' . $userID;
	// $url = 'http://api.openweathermap.org/data/2.5/weather?id=' . $cityID . '&units=metric&APPID=' . $userID;
	$json = json_decode(file_get_contents($url));

	$city = $json->city->name;
	$imgDim = 100;
	$curdate = substr($json->list[0]->dt_txt, 8, 2);
	$forecast = array();
	$day = array();
	foreach($json->list as $hour) {
		if($curdate != substr($hour->dt_txt, 8, 2)) {
				$curdate = substr($hour->dt_txt, 8, 2);
				array_push($forecast, $day);
				$day = array();
			}
			$time = substr($hour->dt_txt, 11, 5);
			$humidity = $hour->main->humidity . '%';
			$temp = $hour->main->temp . '&deg;C';
			$maxtemp = $hour->main->temp_max . '&deg;C';
			$mintemp = $hour->main->temp_min . '&deg;C';
			$weather = $hour->weather[0]->description;
			$weather = strtoupper(substr($weather, 0, 1)) . substr($weather, 1, strlen($weather)-1);
			$icon = 'icons/' . $imgDim . '/' . $hour->weather[0]->icon . '.png';
	
			array_push($day, ['weather' => $weather, 'general' => $hour->weather[0]->main, 'icon' => $icon, 'time' => $time, 'humidity' => $humidity, 'temp' => $temp, 'maxtemp' => $maxtemp, 'mintemp' => $mintemp]);
	}

	echo '<h1>Showing only 1 report</h1><br>';
	echo 'city -> ' . $city;
	// echo '<br><img src="../' . $forecast[0][0]['icon'] . '">';
	echo '<br>icon -> ../' . $forecast[0][0]['icon'];
	echo '<br>weather -> ' . $forecast[0][0]['weather'];
	echo '<br>general -> ' . $forecast[0][0]['general'];
	echo '<br>time -> ' . $forecast[0][0]['time'];
	echo '<br>humidity -> ' . $forecast[0][0]['humidity'];
	echo '<br>temp -> ' . $forecast[0][0]['temp'];
	echo '<br>maxtemp -> ' . $forecast[0][0]['maxtemp'];
	echo '<br>mintemp -> ' . $forecast[0][0]['mintemp'];
?>