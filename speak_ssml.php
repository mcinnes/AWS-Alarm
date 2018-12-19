<?php

include 'media-sources/weather.php';
include 'media-sources/calendar.php';
include 'media-sources/bitcoin.php';
include 'media-sources/abcnews.php';
include 'media-sources/fox-sports.php';


$todayDate = date("l jS \of F");

$wordsToSpeak = getSpecialDates().getWeatherString().getCalendarEvents().getBitcoinPrice();

$speech = [

  // Change this to whatever SSML you want to convert to audio
  'Text' => '
    <speak>Good morning Matt, <break strength="strong"/> Today is <say-as interpret-as="date">'.$todayDate.'</say-as>. '.$wordsToSpeak.'</speak>',

  'OutputFormat' => 'mp3',
  'TextType' => 'ssml',
  'VoiceId' => 'Joanna'

];

$speechMale = [
  
    // Change this to whatever SSML you want to convert to audio
    'Text' => '
      <speak><break strength="strong"/>'.getNews().'<break strength="strong"/>'.getMotorsport().'</speak>',
  
    'OutputFormat' => 'mp3',
    'TextType' => 'ssml',
    'VoiceId' => 'Russell'
  
  ];

var_dump($speech);
var_dump($speechMale);

$config = [
  'version' => 'latest',
  'region' => 'us-west-2', // Change this to your respective AWS region
  'credentials' => [ // Change these to your respective AWS credentials
    'key' => '',
    'secret' => '',
  ]
];


require(__DIR__.'/vendor/autoload.php');

use Aws\Polly\PollyClient;


// get service handle
try {$client = new PollyClient($config);}
catch(Exception $e) {print_r($e); exit;}

// get speech
$response = $client->synthesizeSpeech($speech);
$newsResponse = $client->synthesizeSpeech($speechMale);
// save response file
file_put_contents('ssml.mp3', $response['AudioStream']);
file_put_contents('ssml1.mp3', $newsResponse['AudioStream']);


file_put_contents('combined.mp3',
  file_get_contents('ssml.mp3') .
  file_get_contents('ssml1.mp3'));

echo '<br><br> <a href="combined.mp3">View Output</a>';

// DEBUG
//print_r($response); exit;
//todo
//slow down voice
//add in text messages to tell me what to do today
//me being able to reply
