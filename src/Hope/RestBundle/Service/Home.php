<?php

namespace Hope\RestBundle\Service;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class Home
{
    /**
     * @return array $banners Returning the array of Banners
     */
    public static function bannersList(){
        return [];
    }

    /**
     *  @return array $streams  Returning the array of live streams;
     */
    public static function liveStreams(){
        $yaml = new Parser();
        try {
            $liveStreams = $yaml->parse(file_get_contents(__DIR__.'/../Resources/config/main.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

        $streams = [];
        if(!empty($liveStreams)) {
            foreach ($liveStreams as $stream) {
                $streams[] = $stream;
            }
        }

        return $streams;
    }
}