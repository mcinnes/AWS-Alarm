<?php
require './vendor/autoload.php';

use PicoFeed\Reader\Reader;

function getMotorsport(){

    try {
        
                $reader = new Reader;
                $resource = $reader->download('https://www.foxsports.com.au/content-feeds/motorsport/');
        
                $parser = $reader->getParser(
                    $resource->getUrl(),
                    $resource->getContent(),
                    $resource->getEncoding()
                );
        
                $feed = $parser->execute();
        
                $motorsportFeed = '<break strength="strong"/> In motorsport news, '.$feed->items[0]->getTitle().'<break strength="strong"/> '.$feed->items[1]->getTitle();    
            }
            catch (Exception $e) {
                // Do something...
            }
            return $motorsportFeed;

}

?>

