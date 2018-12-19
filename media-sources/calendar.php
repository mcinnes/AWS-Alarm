<?php

function getCalendarEvents(){
  $json_url = 'https://p03-calendarws.icloud.com/ca/subscribe/1/F0mKuoXUKIQfxbabtoBLRRTERDG3ilxZTRRQrfDTTt_G2G2cUbW4uqCh3xJ7xIJtPxM2cr6UtLJXnkDj-z3lpgmA99rlWCzn1WSDe8PkEhs';
  // jSON String for request
  // Initializing curl
  $ch = curl_init($json_url);
  $fp = fopen('cal.ics', 'w');
  
  // Configuring curl options
  $options = array(
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FILE => $fp
  );
  // Setting curl options
  curl_setopt_array( $ch, $options );
  // Getting results
  $result = curl_exec($ch); // Getting jSON result string

  require 'class.iCalReader.php';
  
  $ical   = new ICal('cal.ics');
  $events = $ical->events();

  $eventString = "";
  $counter = 0;
  
  foreach ($events as $event){
      $timestamp = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
      $date = date('Y-m-d', $timestamp);
      $today = date('Y-m-d');
      
      if ($date == $today) {
          $counter++;
        $eventString = $eventString . "At ".$event['DSTART']." you have ".$event["SUMMARY"].".<break strength='strong'/> ";
      }
  }

  if ($counter == 1){
    $countString =  'You have '.$counter.' event in your calendar for today. <break strength="strong"/>';    
  } else {
    $countString =  'You have '.$counter.' events in your calendar for today. <break strength="strong"/>';    
  }

  $returnString = $countString.$eventString;
  
  return $returnString;
  
}

function getSpecialDates(){
    $today = date("jS \of F");
    $specialDateString = "";
    echo $today;
    if ($today == "12th of November"){
      $specialDateString = "Karleys birthday is today, <break strength='strong'/>Mums birthday is in one week. ";
    } elseif ($today == "19th of November"){
      $specialDateString = "Mums birthday is today. ";
    } elseif ($today == "30th of October"){
      $specialDateString = "Today is your birthday, Happy birthday Matt. ";    
    } elseif ($today == "7th of October"){
      $specialDateString = "Dads birthday is today. ";   
    } elseif ($today == "1st of October"){
      $specialDateString = "Dads birthday is in one week. ";   
    } elseif ($today == "7th of June"){
      $specialDateString = "Bells birthday is today. ";   
    } 
  return $specialDateString;
  }

?>