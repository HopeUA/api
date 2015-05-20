<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction(Request $request){

        //These parameters will be used in next version
        $device = $request->query->get('device');
        $lang   = $request->query->get('lang');

        $homeService = $this->get('hope.home.service');
        $settings = array();
        $settings['banners'] = $homeService->getBannersList();
        $settings['live'] = $homeService->getLiveStreams();
        $data = $homeService->getCategories();
        $settings['categories'] = $data['categoryList'];
        $settings['top_videos'] = $data['topVideo'];
        $settings['programs'] = $homeService->getPrograms();
        $settings['about'] = $homeService->getPageList();

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