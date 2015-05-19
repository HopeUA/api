<?php

namespace Hope\RestBundle\Service;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class HomeService
{
    private $entityManager;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Banner List
     *
     * @return array
     * @throws \Exception
     */
    public function getBannersList(){

        $banners = $this->entityManager
            ->getRepository('HopeRestBundle:Banner')
            ->findAll();

        $bannersList = array();
        if (is_array($banners)) {
            foreach ($banners as $key => $obj) {
                $bannersList[$key]['id'] = $obj->getId();
                $bannersList[$key]['image'] = $obj->getImage();
                $bannersList[$key]['url'] = $obj->getUrl();
            }
        } else {
            throw new \Exception('Array expected but got '.gettype($banners).' type');
        }

        return $bannersList;
    }

    /**
     * Get Live Streams
     *
     * @return array
     * @throws \Exception
     */
    public function getLiveStreams(){

        $liveStreams = array();
        $yaml = new Parser();
        try {
            $liveStreams = $yaml->parse(file_get_contents(__DIR__.'/../Resources/config/main.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

        $live = array();
        if(is_array($liveStreams) && isset($liveStreams['live']['hopeua'])) {
            $live['stream'] = $liveStreams['live']['hopeua'];
        } else {
            throw new \Exception('LiveStreams: Array expected but got '.gettype($liveStreams).' type');
        }
        return $live;
    }

    public function getCategories()
    {
        $categories = $this->entityManager
            ->getRepository('HopeRestBundle:Category')
            ->findBy(
                array(),
                array('sort' => 'ASC')
            );

        $categoriesList = array();
        $topVideo  = array();
        if (is_array($categories)) {
            foreach ($categories as $key => $obj) {
                $categoriesList[$key]['id'] = $obj->getId();
                $categoriesList[$key]['title'] = $obj->getTitle();

                $programs = $obj->getPrograms();
                $programsIds = array();
                if (is_object($programs)) {
                    foreach ($programs as $keyProgr => $program) {
                        $programsIds[] = $program->getId();
                    }
                } else {
                    throw new \Exception('Programs: Object expected but got '.gettype($programs).' type');
                }
                //getting vide for current category
                $catVideos = $this->entityManager->getRepository('HopeRestBundle:Episode')
                    ->getTopTwoVideos($programsIds);

                $videoList = [];
                $vid = 0;
                if (is_array($catVideos)) {
                    foreach ($catVideos as $video) {
                        $videoList[$vid]['code'] = $video->code;
                        $videoList[$vid]['title'] = $video->title;
                        $videoList[$vid]['desc'] = $video->description;
                        $videoList[$vid]['author'] = $video->author;
                        $videoList[$vid]['duration'] = $video->duration;
                        $videoList[$vid]['publish_time'] = $video->publish_time;
                        $videoList[$vid]['hd'] = $video->hd;
                        $videoList[$vid]['image'] = "http://share.yourhope.tv/" . $video->code . '.jpg';
                        $videoList[$vid]['link'] = array(
                            "download" => "http://share.yourhope.tv/" . $video->code . '.mov',
                            "watch" => "https://www.youtube.com/watch?v=" . $video->watch
                        );

                        $videoList[$vid]['program'] = $video->program;
                        $vid++;
                    }
                } else {
                    throw new \Exception('catVideos: Array expected but got '.gettype($catVideos).' type');
                }

                if ($obj->getTitle() == 'музыкальные') {
                    $topVideo[] = [
                        'section' => 'main',
                        'items' => $videoList,
                    ];
                }

                $topVideo[] = [
                    'section' => $obj->getTitle(),
                    'items' => $videoList,
                ];
            }
        } else {
            throw new \Exception('Categories: Array expected but got '.gettype($categories).' type');
        }

        return  array(
            'categoryList' => $categoriesList,
            'topVideo' => $topVideo
        );
    }

    public function getPrograms()
    {
        $programs = $this->entityManager
            ->getRepository('HopeRestBundle:Program')
            ->findBy(
                array(),
                array('title' => 'ASC')
            );
        $programList = [];
        if (is_array($programs)) {
            foreach ($programs as $key => $program) {
                $programList[$key]['code'] = $program->getCode();
                $programList[$key]['title'] = $program->getTitle();
                $programList[$key]['desc_short'] = $program->getDescShort();
                $programList[$key]['desc_full'] = $program->getDescFull();
                $programList[$key]['category_id'] = $program->getCategoryId();
                $programList[$key]['image'] = 'http://hope.ua/images/programs/' . $program->getCode() . '.png';
            }
        } else {
            throw new \Exception('Programs: Array expected but got '.gettype($programs).' type');
        }
        return $programList;
    }

    public function getPageList()
    {
        $pages = $this->entityManager
            ->getRepository('HopeRestBundle:Page')
            ->findAll();

        $pageList = array();
        foreach($pages as $key => $obj){
            $pageList[$key]['id']      = $obj->getId();
            $pageList[$key]['section'] = $obj->getSection();
            $pageList[$key]['title']   = $obj->getTitle();
            $pageList[$key]['text']    = $obj->getText();
        }
        return $pageList;
    }
}