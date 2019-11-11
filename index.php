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

		<div class='programmerQuotes' onclick='copyToClipboard()'>
			<?php
				$quote = getQuote();
				echo '<b id="quote">' . $quote[0] . '</b><br>&nbsp;- ' . $quote[1];
			?>
		</div>
	</body>
</html>
