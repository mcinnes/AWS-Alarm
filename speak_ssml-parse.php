<?php
require(__DIR__.'/vendor/autoload.php');
include 'media-sources/weather.php';
include 'media-sources/calendar.php';
include 'media-sources/bitcoin.php';
include 'media-sources/abcnews.php';
include 'media-sources/fox-sports.php';

use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParseUser;
use Parse\ParseException;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
use Parse\ParseServerInfo;
use Parse\ParseAudience;

ParseClient::initialize( 'd7d3911ed9f94973a17367120889d7191d8dcb1a', null, '4087f4eb9c9bb0b9113d4eb206851fa508661716' );

ParseClient::setServerURL('http://35.197.177.57','parse');

$health = ParseClient::getServerHealth();
if($health['status'] === 200) {
    var_dump($health);
}

$query = ParseUser::query();
$query->equalTo("objectId", "YBjh3tCE5F"); 
$results = $query->first();

var_dump($results);

//$user = ParseUser::$results[0];

$selectedProviders = $results->get("serviceProviders");

$selectedArray = explode(",", $selectedProviders);

$wordsToSpeak = "";

foreach ($selectedArray as $value) {
  switch ($value) {
    case "0":
        $wordsToSpeak = $wordsToSpeak . getSpecialDates();
        break;
    case "1":
      $wordsToSpeak = $wordsToSpeak . getWeatherString();
        break;
    case "2":
      $wordsToSpeak = $wordsToSpeak . getCalendarEvents();
        break;
    case "3":
      $wordsToSpeak = $wordsToSpeak . getBitcoinPrice();
      break;
    case "4":
      $wordsToSpeak = $wordsToSpeak . getSpecialDates();
      break;
    default:
        echo "Your favorite color is neither red, blue, nor green!";
}
}

echo $wordsToSpeak;






$todayDate = date("l jS \of F");

//$wordsToSpeak = getSpecialDates().getWeatherString().getCalendarEvents().getBitcoinPrice();

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
    'key' => 'AKIAI7NGLTLK5XDKAHQQ',
    'secret' => 'WRErikjHC27LId87oiukJTCjBLPgU3u/L8YFGP8+',
  ]
];



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


$localFilePath = "combined.mp3";
$file = ParseFile::createFromFile($localFilePath, "results.mp3");
$file->save();
// The file has been saved to Parse and now has a URL.
$parseurl = $file->getURL();
echo $parseurl;
$results->set("latestRecording", $file);
$results->save(true);

// DEBUG
//print_r($response); exit;
//todo
//slow down voice
//add in text messages to tell me what to do today
//me being able to reply