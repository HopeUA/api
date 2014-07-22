<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser;

class HomeController extends Controller
{
    public function indexAction(Request $request){

        $device = $request->get('device');
        $lang = $request->get('lang');

        $settings = array();

#########  Получение списка баннеров
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

#########  Получаем Live
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




#########  Получаем список всех категорий
        $settings['categories']=array();
        $categories = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Category')
            ->findAll();

        $categoriesList = array();
        foreach($categories as $key => $obj){
            $categoriesList[$key]['id']       = $obj->getId();
            $categoriesList[$key]['title']    = $obj->getTitle();
            $categoriesList[$key]['programs'] = array();

            $programs = $obj->getPrograms();
            foreach($programs as $program){
                $categoriesList[$key]['programs'][]['id']         = $program->getId();
                $categoriesList[$key]['programs'][]['code']       = $program->getCode();
                $categoriesList[$key]['programs'][]['title']      = $program->getTitle();
                $categoriesList[$key]['programs'][]['desc_short'] = $program->getDescShort();
                $categoriesList[$key]['programs'][]['desc_full'] = $program->getDescFull();
            }

        }

        $settings['categories'] = $categoriesList;

#########  Получаем список Top Videos
        $settings['top_videos']=array();



#########  Получаем список Страниц
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

        //формат JSON
        $settingsJSON = json_encode($settings);


        $response = new Response($settingsJSON);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}