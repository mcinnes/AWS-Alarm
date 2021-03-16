<?php

require(__DIR__.'/vendor/autoload.php');

include 'media-sources/weather.php';
include 'media-sources/calendar.php';
include 'media-sources/bitcoin.php';
include 'media-sources/abcnews.php';
include 'media-sources/fox-sports.php';

use Aws\Polly\PollyClient;

class Alarm {

    public function __construct()
    {
        file_put_contents('combined.mp3', self::downloadFile(self::getDailySpeech()), self::downloadFile(self::getNewSpeech()));
        system('/Applications/VLC.app/Contents/MacOS/VLC combined.mp3');
    }

    static public function downloadFile($data, $filename)
    {
        $config = [
          'version' => 'latest',
          'region' => 'us-west-2', // Change this to your respective AWS region
          'credentials' => [ // Change these to your respective AWS credentials
            'key' => '',
            'secret' => '',
          ]
        ];
        try {
            $client = new PollyClient($config);
        } catch(Exception $e) 
        {
            print_r($e); 
            exit;
        }

        $response = $client->synthesizeSpeech($data);

        return $response['AudioStream'];
    }

    static public function getDailySpeech()
    {
        $todayDate = date("l jS \of F");

        $wordsToSpeak = getSpecialDates().getWeatherString().getCalendarEvents().getBitcoinPrice();

        $speech = [
          // Change this to whatever SSML you want to convert to audio
          'Text' => '<speak>Good morning Matt, <break strength="strong"/> Today is <say-as interpret-as="date">'.$todayDate.'</say-as>. '.$wordsToSpeak.'</speak>',
          'OutputFormat' => 'mp3',
          'TextType' => 'ssml',
          'VoiceId' => 'Joanna'
        ];


    }

    public function getNewSpeech()
    {
        $speechMale = [
        // Change this to whatever SSML you want to convert to audio
        'Text' => '<speak><break strength="strong"/>'.getNews().'<break strength="strong"/>'.getMotorsport().'</speak>',
        'OutputFormat' => 'mp3',
        'TextType' => 'ssml',
        'VoiceId' => 'Russell'
      
      ];
    }
}

$alarm = new Alarm();
