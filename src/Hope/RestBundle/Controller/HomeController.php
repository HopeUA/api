<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser;
//use Doctrine\ORM\Query\ResultSetMapping;
//use Doctrine\ORM\EntityRepository;

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

        foreach($liveStreams as $stream){
            $settings['live'][] = $stream;
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

        foreach($categories as $key => $obj){
            $categoriesList[$key]['id']       = $obj->getId();
            $categoriesList[$key]['title']    = $obj->getTitle();
            $categoriesList[$key]['programs'] = array();

            $programs = $obj->getPrograms();
            foreach($programs as $keyProgr=>$program){
                $categoriesList[$key]['programs'][$keyProgr]['id']         = $program->getId();
                $categoriesList[$key]['programs'][$keyProgr]['code']       = $program->getCode();
                $categoriesList[$key]['programs'][$keyProgr]['title']      = $program->getTitle();
                $categoriesList[$key]['programs'][$keyProgr]['desc_short'] = $program->getDescShort();
                $categoriesList[$key]['programs'][$keyProgr]['desc_full']  = $program->getDescFull();

                $videos = $program->getVideos();
                //$videoList = array();
                foreach($videos as $keyVideo => $video){
                    $vid = $video->getId();
                    $categoriesList[$key]['videos'][$vid]['id'] = $vid;
                    $categoriesList[$key]['videos'][$vid]['code'] = $video->getCode();
                    $categoriesList[$key]['videos'][$vid]['title'] = $video->getTitle();
                    $categoriesList[$key]['videos'][$vid]['descr'] = $video->getDescription();
                    $categoriesList[$key]['videos'][$vid]['author'] = $video->getAuthor();
                    $categoriesList[$key]['videos'][$vid]['duration'] = $video->getDuration();
                    $categoriesList[$key]['videos'][$vid]['publish_time'] = $video->getPublishTime();
                    $categoriesList[$key]['videos'][$vid]['hd'] = $video->getHd();
                    $categoriesList[$key]['videos'][$vid]['image'] = "http://share.yourhope.tv/".$video->getCode().'.jpg';
                    $categoriesList[$key]['videos'][$vid]['link'] = array(
                        "download" => "http://share.yourhope.tv/".$video->getCode().'.mov',
                        "watch"    => "https://www.youtube.com/watch?v=".$video->getWatch()
                    );
                    $programVideo = $video->getProgram();
                    $categoriesList[$key]['videos'][$vid]['program'] = $programVideo->getCode();
                }
            }
            //krsort($categoriesList[$key]['videos']);
            $videoList[$key][] = end($categoriesList[$key]['videos']);
            $videoList[$key][] = prev($categoriesList[$key]['videos']);
            unset($categoriesList[$key]['videos']);


        }

        $settings['categories'] = $categoriesList;
        unset($categoriesList);

        //  Получаем список Top Videos

        $settings['top_videos'] = $videoList;
/*
        $em = $this->getDoctrine()->getManager();
        $videoList = array();
        foreach($topVideos as $key=>$video){
            $videoList[$key]['id'] = $video->getId();
            $videoList[$key]['code'] = $video->getCode();
            $videoList[$key]['title'] = $video->getTitle();
            $videoList[$key]['descr'] = $video->getDescription();
            $videoList[$key]['author'] = $video->getAuthor();
            $videoList[$key]['duration'] = $video->getDuration();
            $videoList[$key]['publish_time'] = $video->getPublishTime();
            $videoList[$key]['hd'] = $video->getHd();
            $videoList[$key]['image'] = $video->getImage();
            $videoList[$key]['link'] = array(
                "download" => $video->getDownload(),
                "watch"    => $video->getWatch()
            );
            $programVideo = $video->getProgram();
            $videoList[$key]['program'] = $programVideo->getCode();

        }

        $settings['top_videos'] = $videoList;
        unset($videoList);
*/
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
        $settingsJSON = json_encode($settings);

        // вывод
        $response = new Response($settingsJSON);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}