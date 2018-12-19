<?php

function getBitcoinPrice(){

  $json_url = 'https://api.coindesk.com/v1/bpi/currentprice.json';
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

  $bData = json_decode($result,true);

  //echo $wData['main']['temp_min'];
  //echo $wData['weather'][0]['description'];
  //echo $wData['weather'][0]['description'];
  $bitcoinString = 'Bitcoin is currently trading at <say-as interpret-as="number">'.$bData['bpi']['USD']['rate'].'</say-as> American Dollars. ';

  return $bitcoinString;

}

?>