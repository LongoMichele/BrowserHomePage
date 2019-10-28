<?php
/*#######################################################################################################################*/
/*#Quotes functions######################################################################################################*/
/*#######################################################################################################################*/
	function refillFile() {
		$url = 'https://programming-quotes-api.herokuapp.com/quotes/lang/en';
		$json = json_decode(file_get_contents($url));

		$separator = '|';
		$maxlen = 90;
		$quotes = array();
		for($i = 1; $i < count($json); $i++) {
			if(strlen($json[$i]->en) <= $maxlen) {
				array_push($quotes, [$json[$i]->en, $json[$i]->author]);
			}
		}

		$file = fopen('./res/quotes.txt', 'w');
		for($i = 0; $i < count($quotes); $i++) {
			$s = $quotes[$i][0] . $separator . $quotes[$i][1];
			fwrite($file, "\n" . $s);
		}
		fclose($file);
	}

	function readLines() {
		$quotes = array();
		$file = fopen('./res/quotes.txt','r');
		while(!feof($file)) {
			array_push($quotes, fgets($file));
		}
		fclose($file);

		if(count($quotes) <= 2) {
			refillFile();
			$line = readLines();
		}
		else {
			$x = rand(0, count($quotes)-1);
			$file = fopen('./res/quotes.txt','w');
			for($i = 0; $i < count($quotes) && !feof($file); $i++) {
				if($i == $x) {
					$line = $quotes[$i];
				}
				else {
					fwrite($file, $quotes[$i]);
				}
			}
			fclose($file);
		}
		return $line;
	}

	function getQuote() {
		$string = readLines();
		return [strtok($string, '|'), strtok('|')];
	}
?>
<?php
/*#######################################################################################################################*/
/*#Weather functions#####################################################################################################*/
/*#######################################################################################################################*/
	function parseTemperature($temp) {
		$parsed = '';
		if(floor(abs($temp)) < 10) {
			$parsed .= '0';
		}
		$parsed .= abs($temp);
		$diff = (abs($temp) * 100) - (floor(abs($temp)) * 100);
		switch($diff) {
			case $diff == 0:
				$parsed .= '.00';
				break;
			case $diff > 9 && (($diff/10) - floor($diff/10)) == 0:
				$parsed .= '0';
				break;
			default:
				break;
		}
		if($diff > 0) {
			if($diff > 9) {

			}
		}
		else {
			$parsed .= '.00';
		}
		if($temp < 0) {
			$parsed = '-' . $parsed;
		}
		return $parsed;
	}

	function getWind($wind) {
		switch($wind) {
			case $wind <= 0.5:
				$wind = 'Calm';
				break;
			case $wind > 0.5 && $wind <= 1.5:
				$wind = 'Light air';
				break;
			case $wind > 1.5 && $wind <= 3.3:
				$wind = 'Light breeze';
				break;
			case $wind > 3.3 && $wind <= 5.5:
				$wind = 'Gentle breeze';
				break;
			case $wind > 5.5 && $wind <= 7.9:
				$wind = 'Moderate breeze';
				break;
			case $wind > 7.9 && $wind <= 10.7:
				$wind = 'Fresh breeze';
				break;
			case $wind > 10.7 && $wind <= 13.8:
				$wind = 'Strong breeze';
				break;
			case $wind > 13.8 && $wind <= 17.1:
				$wind = 'High wind';
				break;
			case $wind > 17.1 && $wind <= 20.7:
				$wind = 'Gale';
				break;
			case $wind > 20.7 && $wind <= 24.4:
				$wind = 'Strong gale';
				break;
			case $wind > 24.4 && $wind <= 28.4:
				$wind = 'Storm';
				break;
			case $wind > 28.4 && $wind <= 32.5:
				$wind = 'Violent storm';
				break;
			case $wind > 32.5:
				$wind = 'Hurricane';
				break;
			default:
				$wind .= ' (undefined)';
				break;
		}
		return $wind;

	}
	function getForecast(){
		if ($fh = fopen('./res/.weatherUserID', 'r')) {
			$userID = fgets($fh);
			fclose($fh);
		}
		else {
			echo 'Errore durante la ricezione dell\'userID';
			exit();
		}
		$cityID = '3171829';
		$lang = 'en';
		$url = 'http://api.openweathermap.org/data/2.5/forecast?units=metric&id=' . $cityID . '&lang=' . $lang . '&APPID=' . $userID;
		// $url = 'http://api.openweathermap.org/data/2.5/forecast?id=' . $cityID . '&units=metric&APPID=' . $userID;
		$json = json_decode(file_get_contents($url));

		$sunrise = $json->city->sunrise;
		$sunset = $json->city->sunset;
		$date = new DateTime();
		$curtime = date('H:i:s', $date->getTimestamp());

		$ampm;
		if(date('H:i:s', $date->getTimestamp()) < date('H:i:s', $sunset)) {
			$ampm = 'd';
		}
		else{
			$ampm = 'n';
		}
		$sunrise = date('H:i', $sunrise);
		$sunset = date('H:i', $sunset);

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
				$temp = parseTemperature($hour->main->temp) . '&deg;C';
				$maxtemp = parseTemperature($hour->main->temp_max) . '&deg;C';
				$mintemp = parseTemperature($hour->main->temp_min) . '&deg;C';
				$wind = getWind($hour->wind->speed);
				$weather = $hour->weather[0]->description;
				$weather = strtoupper(substr($weather, 0, 1)) . substr($weather, 1, strlen($weather)-1);
				$icon = '/' . substr($hour->weather[0]->icon, 0, 2) . $ampm . '.png';
		
				array_push($day, ['weather' => $weather, 'general' => $hour->weather[0]->main, 'icon' => $icon, 'time' => $time, 'humidity' => $humidity, 'temp' => $temp, 'maxtemp' => $maxtemp, 'mintemp' => $mintemp, 'wind' => $wind]);
		}

		return [$forecast, $sunrise, $sunset];
	}
?>


<html lang='en' style='overflow: hidden;'>
	<head>
		<title>Home</title>
		<meta charset='utf-8'>

		<link rel='icon' href='./icons/favicon.ico'>
		<link rel='stylesheet' href='./res/style.css'>
		<link rel='stylesheet' href='./res/bulma/css/bulma.min.css'>
		<link rel='stylesheet' href='./res/font-awesome/css/font-awesome.min.css'>
		
		<script type='text/javascript' src='./res/scripts.js'></script>
	</head>
	<body>
		<div class='weatherForecast'>
			<?php
				$res = getForecast();
				$forecast = $res[0];
				$sunrise = $res[1];
				$sunset = $res[2];

				echo '<div id="showForecast" onclick="showForecast()">';
				// echo '<img src="../icons/125/sample.png" style="float: left;">';
				echo '<img src="../icons/125' . $forecast[0][0]['icon'] . '" style="float: left;">';
				echo '<span style="font-size: 60; position: relative; left: 10px; top: -10px;">' . $forecast[0][0]['temp'] . '</span><br>';

				echo '<img class="sunIcon" src="../icons/50/sunrise.png">';
				echo '<span class="sunText">' . $sunrise . '</span>';
				echo ' <img class="sunIcon" src="../icons/50/sunset.png">';
				echo '<span class="sunText">' . $sunset . '</span><br>';

				echo '<img class="otherIcon" src="../icons/50/humidity.png">';
				echo '<span class="otherText">' . $forecast[0][0]['humidity'] . '</span>';
				echo '<img class="otherIcon" src="../icons/50/wind.png" style="top: -25px;">';
				echo '<span class="otherText">' . $forecast[0][0]['wind'] . '</span><br>';
				echo '</div>';

				echo '<div id="forecast" style="display: none;">';
				for($i = 1; $i < count($forecast[1]); $i++) {
					echo '<div style="position: relative; clear: left; bottom: ' . ($i-1)*15 . 'px;">';
					echo '<div style="font-size: 40; margin-top: 20px; float: left;">At ' . $forecast[1][$i]['time'] . '<br>';
					echo '<span style="font-size: 20;">' . $forecast[1][$i]['weather'] . '</span></div><br>';

					// echo '<img src="../icons/75/sample.png" style="float: left;">';
					echo '<img src="../icons/75/' . $forecast[1][$i]['icon'] . '" style="float: left;">';

					echo '<div style="position: relative; bottom: -5px;"><img src="../icons/30/maxtemp.png">' . $forecast[1][$i]['maxtemp'] . '</div>';
					echo '<div style="position: relative; bottom: -5px;"><img src="../icons/30/mintemp.png">' . $forecast[1][$i]['mintemp'] . '</div>';
					echo '</div>';
				}
				echo '</div>';
			?>
		</div>
	</body>
</html>