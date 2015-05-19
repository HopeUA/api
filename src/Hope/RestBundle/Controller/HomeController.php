<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction(Request $request){

        $device = $request->get('device');
        $lang   = $request->get('lang');

        $settings = array();
        $settings['banners'] = $this->get('hope.home.service')->getBannersList();
        $settings['live'] = $this->get('hope.home.service')->getLiveStreams();
        $data = $this->get('hope.home.service')->getCategories();
        $settings['categories'] = $data['categoryList'];
        $settings['top_videos'] = $data['topVideo'];
        $settings['programs'] = $this->get('hope.home.service')->getPrograms();
        $settings['about'] = $this->get('hope.home.service')->getPageList();

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