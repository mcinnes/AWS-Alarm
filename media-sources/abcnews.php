<?php
require './vendor/autoload.php';

use PicoFeed\Reader\Reader;

function getNews(){

    try {

        $reader = new Reader;
        $resource = $reader->download('http://www.abc.net.au/news/feed/45910/rss.xml');

        $parser = $reader->getParser(
            $resource->getUrl(),
            $resource->getContent(),
            $resource->getEncoding()
        );

        $feed = $parser->execute();

        $newsFeed = '<break strength="strong"/> In the news today, '.$feed->items[0]->getTitle().'<break strength="strong"/> '.$feed->items[1]->getTitle();    
    }
    catch (Exception $e) {
        // Do something...
    }
    return $newsFeed;
}

?>