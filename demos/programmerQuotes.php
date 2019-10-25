<?php
    $url = 'https://programming-quotes-api.herokuapp.com/quotes/lang/en';
	$json = json_decode(file_get_contents($url));

	$quotes = array();
	foreach($json as $quote) {
		if(strlen($quote->en) <= 120) {
			array_push($quotes, ['author' => $quote->author, 'text' => $quote->en]);
			echo $quote->en . '<br>';
		}
	}
?>