<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class VideoController extends Controller
{

    public function indexAction(Request $request)
    {

        $params = array();

        // Получаем GET переменные
        // код программы
        if ($request->query->get('program_code')!='') {
            $params['program_code']       = $request->query->get('program_code');
        }
        if ($request->query->get('program_category')) {
            $params['program_category']   = $request->query->get('program_category');
        }
        if ($request->query->get('code')) {
            $params['code']               = $request->query->get('code');
        }
        if ($request->query->get('text')) {
            $params['text']               = $request->query->get('text');
            $params['text']               = addcslashes($params['text'], "%_");
        }
        if ($request->query->get('sort')) {
            $params['sort']               = $request->query->get('sort');
        }
        if ($request->query->get('limit')) {
            $params['limit']              = $request->query->get('limit');
        }

        $videoList = $this->get('hope.video.service')->getVideos($params);

        if (empty($videoList)) {
            $videoListError = array(
                'error' => true,
                'message' => "Ошибка"
            );
            $jsonVideos = json_encode($videoListError, JSON_UNESCAPED_UNICODE);
            $response = new Response($jsonVideos, 404);
            $response->headers->set('Content-Type', 'application/json; charset=utf8');

        } else {
            $jsonVideos = json_encode($videoList, JSON_UNESCAPED_UNICODE);
            $response = new Response($jsonVideos);
            $response->headers->set('Content-Type', 'application/json; charset=utf8');
        }

        return $response;

    }
}
