<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Hope\RestBundle\Service\Home;


class HomeController extends Controller
{
    public function indexAction(Request $request){

        // Получаем GET переменные
        $device = $request->get('device');
        $lang   = $request->get('lang');

        $settings = array();

        //  Получение списка баннеров
        $settings['banners']=Home::bannersList();

        //  Получаем Live
        $settings['live'] = Home::liveStreams();

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
        $videoList = array();
        $topVideo  = array();
        foreach($categories as $key => $obj){
            $categoriesList[$key]['id']       = $obj->getId();
            $categoriesList[$key]['title']    = $obj->getTitle();
            $categoriesList[$key]['programs'] = array();

            $programs = $obj->getPrograms();
            $programsIds = array();
            foreach($programs as $keyProgr=>$program){
                $programsIds[] = $program->getId();
                $categoriesList[$key]['programs'][$keyProgr]['id']         = $program->getId();
                $categoriesList[$key]['programs'][$keyProgr]['code']       = $program->getCode();
                $categoriesList[$key]['programs'][$keyProgr]['title']      = $program->getTitle();
                $categoriesList[$key]['programs'][$keyProgr]['desc_short'] = $program->getDescShort();
                $categoriesList[$key]['programs'][$keyProgr]['desc_full']  = $program->getDescFull();
            }

            //получаем видео для программ из данной категории
            $catVideos = $this->getDoctrine()->getRepository('HopeRestBundle:Episode')
                        ->getTopTwoVideos($programsIds);

            foreach($catVideos as $video){
                $vid                             = $video->id;
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
                $topVideo[]                      = $videoList[$vid];

                unset($videoList[$vid]);
            }

        }

        $settings['categories'] = $categoriesList;
        unset($categoriesList);
        $publishTime = array();

        foreach($topVideo as $key=>$video){
            $publishTime[$key] = $video['publish_time'];

        }
        array_multisort($publishTime, SORT_DESC, $topVideo);

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