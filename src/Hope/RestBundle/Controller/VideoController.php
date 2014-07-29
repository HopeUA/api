<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VideoController extends Controller
{

    public function indexAction(Request $request){

        $params = array();

        // Получаем GET переменные
        // код программы
        $params['program_code']       = $request->get('program_code');
        $params['program_category']   = $request->get('program_category');
        $params['code']               = $request->get('code');
        $params['text']               = $request->get('text');
        $params['sort']               = $request->get('sort');
        $params['limit']              = $request->get('limit');

        $em = $this->getDoctrine()->getManager();
        $videos = $em->getRepository('HopeRestBundle:Episode')
            ->getByParams($params);

        //обработка полученных видео объектов
        if(!empty($videos)){
            $videoList = array();
            foreach($videos as $video){
                $vid = $video->getId();
                $videoList[$vid]['id'] = $vid;
                $videoList[$vid]['code'] = $video->getCode();
                $videoList[$vid]['title'] = $video->getTitle();
                $videoList[$vid]['descr'] = $video->getDescription();
                $videoList[$vid]['author'] = $video->getAuthor();
                $videoList[$vid]['duration'] = $video->getDuration();
                $videoList[$vid]['publish_time'] = $video->getPublishTime()->format( 'Y-m-d H:i:s' );
                $videoList[$vid]['hd'] = $video->getHd();
                $videoList[$vid]['image'] = 'http://share.yourhope.tv/'.$video->getCode().'.jpg';
                $videoList[$vid]['link'] = array(
                    "download" => 'http://share.yourhope.tv/'.$video->getCode().'.mov',
                    "watch"    => $video->getWatch()
                );
                $programVideo = $video->getProgram();
                $videoList[$vid]['program'] = $programVideo->getCode();
            }
        }else{
            $videoList = array(
                'error' => true,
                'message' => "Ошибка"
            );


        }
        $jsonVideos = json_encode($videoList, JSON_UNESCAPED_UNICODE);

        if(empty($videos)){
            $response = new Response($jsonVideos, 404);
            //$response->headers->set('Content-Type', 'application/json');

        }else{
            $response = new Response($jsonVideos);
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;

    }

} 