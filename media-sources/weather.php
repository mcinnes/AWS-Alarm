<?php

function getWeatherString(){
  //Get the weather
  $json_url = 'https://api.openweathermap.org/data/2.5/weather?lat=-37.9260865&lon=145.345690&units=metric&appid=';
  // jSON String for request
  // Initializing curl

  $ch = curl_init($json_url);
  
  // Configuring curl options
  $options = array(
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
  );
  // Setting curl options
  curl_setopt_array( $ch, $options );
  // Getting results
  $result = curl_exec($ch); // Getting jSON result string

  $wData = json_decode($result,true);

  //echo $wData['main']['temp_min'];
  //echo $wData['weather'][0]['description'];
  //echo $wData['weather'][0]['description'];
  $weatherString = 'The weather today will be '.$wData['weather'][0]['description'].', <break strength="strong"/> The high will be '.$wData['main']['temp_max'].' with a low of '.$wData['main']['temp_min'].'. <break strength="strong"/>';
  return $weatherString;
}

?>
