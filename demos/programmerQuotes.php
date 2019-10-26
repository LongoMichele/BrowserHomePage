<?php
	function refillFile() {
		$url = 'https://programming-quotes-api.herokuapp.com/quotes/lang/en';
		$json = json_decode(file_get_contents($url));

		$quotes = array();
		for($i = 1; $i < count($json); $i++) {
			if(strlen($json[$i]->en) <= 120) {
				array_push($quotes, [$json[$i]->en, $json[$i]->author]);
			}
			if(strlen($json[$i]->en) == 120) {
				echo $json[$i]->en;
			}
		}

		$file = fopen('../res/quotes.txt', 'w');
		for($i = 0; $i < count($quotes); $i++) {
			$s = $quotes[$i][0] . $separator . $quotes[$i][1];
			fwrite($file, "\n" . $s);
		}
		fclose($file);
	}

	function readLines() {
		$quotes = array();
		$file = fopen('../res/quotes.txt','r');
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
			$file = fopen('../res/quotes.txt','w');
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
		$token = strtok($string, '|');
		echo $token . '<br>';
		$token = strtok('|');
		echo ' - ' . $token;
	}
?>
<html>

<head>
	<meta charset='utf-8'>
</head>

<body style='background-color: #323639; font-family: "Hack"; color: rgb(250, 250, 250);'>
	<span style='font-size: 20;'>
		<?php
			echo getQuote();
		?>
	</span>
</body>

</html>