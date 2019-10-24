<?php

// $url = 'http://api.openweathermap.org/data/2.5/forecast?id=3171829&units=metric&APPID=79067760b1aa2fc545335e0877de93a6';
// $url = 'http://api.openweathermap.org/data/2.5/weather?id=3171829&units=metric&APPID=79067760b1aa2fc545335e0877de93a6';
$cityID = '3171829';
$userID = '79067760b1aa2fc545335e0877de93a6';
$url = 'http://api.openweathermap.org/data/2.5/forecast?id=' . $cityID . '&units=metric&APPID=' . $userID;
// $url = 'http://api.openweathermap.org/data/2.5/weather?id=' . $cityID . '&units=metric&APPID=' . $userID;

$json = json_decode(file_get_contents($url));

echo $json->city->name;

?>