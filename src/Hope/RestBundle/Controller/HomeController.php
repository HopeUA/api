<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser;


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
            $settings['live'] = $stream;
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
            $query = $this->getDoctrine()->getManager()->createQueryBuilder();
            $query->select('e')
                  ->from('HopeRestBundle:Episode', 'e');
            $query->add('where', $query->expr()->in('e.program_id', $programsIds));
            $query->orderBy('e.publish_time', 'DESC');
            $query->setMaxResults(2);
            $catVideos = $query->getQuery()->getResult();

            foreach($catVideos as $video){
                $vid = $video->getId();
                $videoList[$vid]['id'] = $vid;
                $videoList[$vid]['cat_id'] = $obj->getId();
                $videoList[$vid]['code'] = $video->getCode();
                $videoList[$vid]['title'] = $video->getTitle();
                $videoList[$vid]['desc'] = $video->getDescription();
                $videoList[$vid]['author'] = $video->getAuthor();
                $videoList[$vid]['duration'] = $video->getDuration();
                $videoList[$vid]['publish_time'] = $video->getPublishTime()->format( 'Y-m-d H:i:s' );
                $videoList[$vid]['hd'] = $video->getHd();
                $videoList[$vid]['image'] = "http://share.yourhope.tv/".$video->getCode().'.jpg';
                $videoList[$vid]['link'] = array(
                    "download" => "http://share.yourhope.tv/".$video->getCode().'.mov',
                    "watch"    => "https://www.youtube.com/watch?v=".$video->getWatch()
                );
                $programVideo = $video->getProgram();
                $videoList[$vid]['program'] = $programVideo->getCode();
            }

            $topVideo[] = $videoList[$vid];
            unset($videoList[$vid]);

        }

        $settings['categories'] = $categoriesList;
        unset($categoriesList);

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