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

        $settings['banners']=array();
        $settings['live']=array();
        $settings['categories']=array();
        $settings['top_videos']=array();
        $settings['programs']=array();
        $settings['about']=array();

        //формат JSON
        $settingsJSON = json_encode($settings);


        $response = new Response($settingsJSON);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}