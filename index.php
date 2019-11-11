<?php
/*#######################################################################################################################*/
/*#Quotes functions######################################################################################################*/
/*#######################################################################################################################*/
	function refillFile() {
		$url = 'https://programming-quotes-api.herokuapp.com/quotes/lang/en';
		$json = json_decode(file_get_contents($url));

		$maxlen = 200;
		$quotes = array();
		for($i = 0; $i < count($json); $i++) {
			if(strlen($json[$i]->en) <= $maxlen) {
				array_push($quotes, [$json[$i]->en, $json[$i]->author]);
			}
		}

		$file = fopen('./res/quotes.txt', 'w');
		fwrite($file, $quotes[0][0] . '|' . $quotes[0][1]);
		for($i = 1; $i < count($quotes); $i++) {
			fwrite($file, "\n" . $quotes[$i][0] . '|' . $quotes[$i][1]);
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
		return [strtok(readLines(), '|'), strtok('|')];
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
		$url = 'http://api.openweathermap.org/data/2.5/forecast?id=' . $cityID . '&units=metric&APPID=' . $userID;
		$json = json_decode(file_get_contents($url));

		$sunrise = $json->city->sunrise;
		$sunset = $json->city->sunset;
		$date = new DateTime();

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
		foreach($json->list as $hour) {
			if($curdate != substr($hour->dt_txt, 8, 2)) {
					break;
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
		
				array_push($forecast, ['weather' => $weather, 'general' => $hour->weather[0]->main, 'icon' => $icon, 'time' => $time, 'humidity' => $humidity, 'temp' => $temp, 'maxtemp' => $maxtemp, 'mintemp' => $mintemp, 'wind' => $wind]);
		}

		return [$forecast, $sunrise, $sunset];
	}
?>
<!--#######################################################################################################################-->
<!--#HTML##################################################################################################################-->
<!--#######################################################################################################################-->
<html lang='en' style='background-color: #323639;'>
	<head>
		<title>Home</title>

		<meta charset='utf-8'>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel='icon' href='./icons/favicon.ico'>
		<link rel='stylesheet' href='./res/style.css'>
		<link rel='stylesheet' href='./res/bulma/css/bulma.min.css'>
		<link rel='stylesheet' href='./res/font-awesome/css/font-awesome.min.css'>
		<link rel="stylesheet" type="text/css" media='screen and (max-width: 960px)' href='./res/small.css'>
		<link rel="stylesheet" type="text/css" media='screen and (min-width: 961px)' href='./res/normal.css'>
		<link rel="stylesheet" type="text/css" media='screen and (min-width: 1200px)' href='./res/big.css'>
		
		<script type='text/javascript' src='./res/scripts.js'></script>
	</head>
	<body onload='focus()'>
		<div class='googleSearch'>
			<form method='get' action='https://www.google.com/search'>
				<img src='./icons/Google.png'>
				<div class='field'>
					<p class='control has-icons-left'>
						<input id='searchBar' class='input is-medium is-rounded' type='text' name='q' size='31' placeholder='Search Google'>
						<span class='icon is-left'>
								<i class='fa fa-search' style='color: grey;'></i>
						</span>
					</p>
				</div>
			</form>
		</div>

		<div class='quickLinks'>
			<ul style='margin-top: 6px;'>
				<li id='FrequentFolder' class='first folderClosed'><a class='folder' onclick='manageFolder("Frequent")'>Frequent</a>
					<ul id='Frequent'>
						<li id='E7Folder' class='folderClosed'><a class='folder' onclick='manageFolder("E7")'>Epic Seven</a>
							<ul id='E7'>
								<li class='link'><a href='https://epic7x.com/'>Wiki</a></li>
								<li class='last Link'><a href='https://budandan.github.io/e7-damage-calc/index.html'>Dmg Calc</a></li>
							</ul>
						</li>
						<li class='link'><a href='https://www.iliad.it/account/'>Iliad</a></li>
						<li class='link'><a href='https://github.com/'>GitHub</a></li>
						<li class='last link'><a href='http://localhost:8000/'>localhost:8000</a></li>
					</ul>
				</li>
				<li id='UniFeFolder' class='folderClosed'><a class='folder' onclick='manageFolder("UniFe")'>UniFe</a>
					<ul id='UniFe'>
						<li id='InformaticaFolder' class='folderClosed'><a class='folder' onclick='manageFolder("Informatica")'>Informatica</a>
							<ul id='Informatica'>
								<li class='link'><a href='http://unife.it/scienze/informatica'>Home</a></li>
								<li class='last link'><a href='http://www.unife.it/scienze/informatica/studiare/programmi-insegnamenti-docenti/piano-degli-studi-270-ord-2016-aa2019-20'>Corsi</a></li>
							</ul>
						</li>
						<li class='last link'><a href='http://studiare.unife.it/'>Area Personale</a></li>
					</ul>
				</li>
				<li id='RedditFolder' class='folderClosed'><a class='folder' onclick='manageFolder("Reddit")'>Reddit</a>
					<ul id='Reddit'>
						<li class='link'><a href='https://www.reddit.com'>reddit.com</a></li>
						<li class='link'><a href='https://www.reddit.com/r/EpicSeven/'>r/EpicSeven</a></li>
						<li class='last link'><a href='https://www.reddit.com/r/wallpapers/'>r/wallpapers</a></li>
					</ul>
				</li>
				<li class='link'><a href='https://www.youtube.com'>YouTube</a></li>
				<li class='last link'><a href='https://www.netflix.com/browse'>Netflix</a></li>
			</ul>
		</div>

		<div class='weatherForecast'>
		<?php
				$res = getForecast();
				$forecast = $res[0];
				$sunrise = $res[1];
				$sunset = $res[2];

				echo '<div class="showForecast" id="showForecast" onclick="showForecast()">';
				echo '<img src="./icons/100' . $forecast[0]['icon'] . '" style="float: left;">';
				echo '<span style="font-size: 50; position: relative; left: 10px; top: -10px;">' . $forecast[0]['temp'] . '</span><br>';

				echo '<img class="sunIcon" src="./icons/sunrise.png">';
				echo '<span class="sunText">' . $sunrise . '</span>';
				echo ' <img class="sunIcon" src="./icons/sunset.png">';
				echo '<span class="sunText">' . $sunset . '</span><br>';

				echo '<span class="sub">';
				echo '<img class="otherIcon" src="./icons/humidity.png">';
				echo '<span class="otherText">' . $forecast[0]['humidity'] . '</span>';
				echo '<img class="otherIcon" src="./icons/wind.png" style="top: -25px;">';
				echo '<span class="otherText">' . $forecast[0]['wind'] . '</span><br>';
				echo '</span></div>';

				echo '<div id="forecast" class="forecast">';
				for($i = 1; $i < count($forecast); $i++) {
					echo '<div style="clear: left; position: relative; top: ' . -($i-1)*25 . ';">';
					echo '<div class="onLeft" style="font-size: 25;">At ' . $forecast[$i]['time'] . '<br>';
					echo '<span class="forecastText onLeft" style="font-size: 12;">' . $forecast[$i]['weather'] . '</span></div><br>';

					echo '<img class="onLeft forecastImg" src="./icons/45' . $forecast[$i]['icon'] . '">';
					echo '<div class="tempDiv"><img src="./icons/maxtemp.png"><span class="forecastText">' . $forecast[$i]['maxtemp'] . '</span></div>';
					echo '<div class="tempDiv"><img src="./icons/mintemp.png" style="position: relative; top: -8;"><span class="forecastText" style="top: -14;">' . $forecast[$i]['mintemp'] . '</span></div>';
					echo '</div>';
				}
				echo '</div>';
			?>
		</div>

		<div class='programmerQuotes'>
			<?php
				$quote = getQuote();
				echo '<b>' . $quote[0] . '</b><br>&nbsp;- ' . $quote[1];
			?>
		</div>
	</body>
</html>
