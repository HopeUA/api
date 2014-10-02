<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser;
use Hope\RestBundle\Entity\Program;

class HomeController extends Controller
{
    public function indexAction(Request $request){

        // Получаем GET переменные
        $device = $request->get('device');
        $lang   = $request->get('lang');

        $settings = array();

        //  Получение списка баннеров
        $settings['banners']=array();
        $programs = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Banner')
            ->findAll();

        $bannersList = array();
        foreach($programs as $key => $obj){
            $bannersList[$key]['id']    = $obj->getId();
            $bannersList[$key]['image'] = $obj->getImage();
            $bannersList[$key]['url']   = $obj->getUrl();
        }

        $settings['banners'] = $bannersList;
        unset($bannersList);

        //  Получаем Live
        $settings['live'] = array();
        $yaml = new Parser();
        try {
            $liveStreams = $yaml->parse(file_get_contents(__DIR__.'/../Resources/config/main.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

        foreach($liveStreams as $channel){
            $settings['live'] = $channel;
        }

        //  Получаем список всех категорий
        $settings['categories']=array();
        $settings['top_videos']=array();
        $categories = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Category')
            ->findBy(
                array(),
                array('sort' => 'ASC')
            );

        $categoriesList = array();
        $topVideo  = array();

        foreach($categories as $key => $obj){
            $categoriesList[$key]['id']       = $obj->getId();
            $categoriesList[$key]['title']    = $obj->getTitle();

            $programs = $obj->getPrograms();
            $programsIds = array();
            foreach($programs as $keyProgr=>$program){
                $programsIds[] = $program->getId();
            }

            //получаем видео для программ из данной категории
            $catVideos = $this->getDoctrine()->getRepository('HopeRestBundle:Episode')
                        ->getTopTwoVideos($programsIds);

            $videoList = [];
            $vid = 0;
            foreach($catVideos as $video){
                $videoList[$vid]['code']         = $video->code;
                $videoList[$vid]['title']        = $video->title;
                $videoList[$vid]['desc']         = $video->description;
                $videoList[$vid]['author']       = $video->author;
                $videoList[$vid]['duration']     = $video->duration;
                $videoList[$vid]['publish_time'] = $video->publish_time;
                $videoList[$vid]['hd']           = $video->hd;
                $videoList[$vid]['image']        = "http://share.yourhope.tv/".$video->code.'.jpg';
                $videoList[$vid]['link']         = array(
                                                    "download" => "http://share.yourhope.tv/".$video->code.'.mov',
                                                    "watch"    => "https://www.youtube.com/watch?v=".$video->watch
                );

                $videoList[$vid]['program']      = $video->program;
                $vid++;
            }

            if ($obj->getTitle() == 'музыкальные') {
                $topVideo[] = [
                    'section' => 'main',
                    'items'   => $videoList,
                ];
            }

            $topVideo[] = [
                'section' => $obj->getTitle(),
                'items'   => $videoList,
            ];
        }

        $settings['categories'] = $categoriesList;
        unset($categoriesList);

        // Programs
        $programs = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Program')
            ->findBy(
                array(),
                array('title' => 'ASC')
            );
        $programList = [];
        /**
         * @var Program $program
         */
        foreach($programs as $key => $program) {
            $programList[$key]['code']  = $program->getCode();
            $programList[$key]['title'] = $program->getTitle();
            $programList[$key]['desc_short'] = $program->getDescShort();
            $programList[$key]['desc_full']  = $program->getDescFull();
            $programList[$key]['category_id']  = $program->getCategoryId();
            $programList[$key]['image']  = 'http://hope.ua/images/programs/' . $program->getCode() . '.png';
        }
        $settings['programs'] = $programList;
        unset($programList);

        /*
        $publishTime = array();
        foreach($topVideo as $key=>$video){
            $publishTime[$key] = $video['publish_time'];

        }
        array_multisort($publishTime, SORT_DESC, $topVideo);
        */

        //  Получаем список Top Videos
        $settings['top_videos'] = $topVideo;

        //  Получаем список Страниц
        $settings['about']=array();

        $pages = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Page')
            ->findAll();

        $pageList = array();
        foreach($pages as $key => $obj){
            $pageList[$key]['id']      = $obj->getId();
            $pageList[$key]['section'] = $obj->getSection();
            $pageList[$key]['title']   = $obj->getTitle();
            $pageList[$key]['text']    = $obj->getText();
        }
        $settings['about'] = $pageList;
        unset($pageList);

        // формат JSON
        $settingsJSON = json_encode($settings, JSON_UNESCAPED_UNICODE);

        if(empty($settings)){
            $response = new Response($settingsJSON, 404);
            $response->headers->set('Content-Type', 'application/json; charset=utf8');

        }else{
            $response = new Response($settingsJSON);
            $response->headers->set('Content-Type', 'application/json; charset=utf8');
        }

        return $response;
    }


}