<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

        $settings['banners'] = $bannersList;$settings['banners'] = $bannersList;

#########  Получаем Live
        $settings['live'] = array();

#########  Получаем список всех категорий
        $settings['categories']=array();
        $categories = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Category')
            ->findAll();

        $categoriesList = array();
        foreach($categories as $key => $obj){
            $categoriesList[$key]['id']       = $obj->getId();
            $categoriesList[$key]['title']    = $obj->getTitle();
            $programs = $obj->getPrograms();
            foreach($programs as $value){
                print_r($value);
            }

        }




        $settings['categories'] = $categoriesList;

#########  Получаем список Top Videos
        $settings['top_videos']=array();

#########  Получаем список Программ
        $settings['programs']=array();


#########  Получаем список Страниц
        $settings['about']=array();

        //формат JSON
        $settingsJSON = json_encode($settings);


        $response = new Response($settingsJSON);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}