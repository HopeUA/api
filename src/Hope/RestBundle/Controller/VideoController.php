<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoController extends Controller
{

    public function indexAction(Request $request){

        $params = array();

        // Получаем GET переменные
        // код программы
        if($request->get('program_code')!=''){
            $params['program_code']       = $request->get('program_code');
        }
        if($request->get('program_category')) {
            $params['program_category']   = $request->get('program_category');
        }
        if($request->get('code')) {
            $params['code']               = $request->get('code');
        }
        if($request->get('text')){
            $params['text']               = $request->get('text');
        }
        if($request->get('sort')){
            $params['sort']               = $request->get('sort');
        }
        if($request->get('limit')){
            $params['limit']              = $request->get('limit');
        }

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($params);

        if(count($this->options)>1){
            $em = $this->getDoctrine()->getManager();
            $videos = $em->getRepository('HopeRestBundle:Episode')
                ->getByParams($this->options);
        }
        //обработка полученных видео объектов
        if(!empty($videos)){

            $videoList = array();
            foreach($videos as $key=>$video){
                $vid = $video->getId();
                $videoList[$key]['id'] = $vid;
                $videoList[$key]['code'] = $video->getCode();
                $videoList[$key]['title'] = $video->getTitle();
                $videoList[$key]['desc'] = $video->getDescription();
                $videoList[$key]['author'] = $video->getAuthor();
                $videoList[$key]['duration'] = $video->getDuration();
                $videoList[$key]['publish_time'] = $video->getPublishTime()->format( 'Y-m-d H:i:s' );
                $videoList[$key]['hd'] = $video->getHd();
                $videoList[$key]['image'] = 'http://share.yourhope.tv/'.$video->getCode().'.jpg';
                $videoList[$key]['link'] = array(
                    "download" => 'http://share.yourhope.tv/'.$video->getCode().'.mov',
                    "watch"    => $video->getWatch()
                );
                $programVideo = $video->getProgram();
                $videoList[$key]['program'] = $programVideo->getCode();


            }


        }else{
            $videoListError = array(
                'error' => true,
                'message' => "Ошибка"
            );


        }


        if(empty($videoList)){
            $jsonVideos = json_encode($videoListError, JSON_UNESCAPED_UNICODE);
            $response = new Response($jsonVideos, 404);
            $response->headers->set('Content-Type', 'application/json; charset=utf8');

        }else{
            $jsonVideos = json_encode($videoList, JSON_UNESCAPED_UNICODE);
            $response = new Response($jsonVideos);
            $response->headers->set('Content-Type', 'application/json; charset=utf8');
        }

        return $response;

    }

    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'limit' => '0|10'
        ));

        $resolver->setOptional(array('program_code'));
        $resolver->setOptional(array('program_category'));
        $resolver->setOptional(array('code'));
        $resolver->setOptional(array('text'));
        $resolver->setOptional(array('sort'));
        $resolver->setOptional(array('limit'));


    }

} 