<?php

class FeedsSeed extends DatabaseSeeder
{
    public function run()
    {
        $feeds = [
            [
                "active" => true,
                "url"    => "http://www.engadget.com/tag/rss/",
                "name"   => "Engadget",
            ]
        ];
        foreach ($feeds as $feed)
        {
            Feed::create($feed);
        }
    }
}