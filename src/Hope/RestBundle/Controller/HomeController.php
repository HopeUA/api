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
            $settings['live'][] = $stream;
        }

//  Получаем список всех категорий
        $settings['categories']=array();
        $categories = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Category')
            ->findBy(
                array(),
                array('order' => 'ASC')
            );

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
                $categoriesList[$key]['programs'][]['desc_full']  = $program->getDescFull();
            }

        }

        $settings['categories'] = $categoriesList;
        unset($categoriesList);

//  Получаем список Top Videos
        $settings['top_videos']=array();

        $em = $this->getDoctrine()->getManager();
        /*'SELECT x
            FROM (SELECT v,
                   CASE
                     WHEN @category_id != p.category_id THEN @rownum := 1
                     ELSE @rownum := @rownum + 1
                   END AS rank,
                   @category_id := p.category_id AS var_category
            FROM HopeRestBundle:Episode v
            LEFT JOIN HopeRestBundle:Program p ON p.id = v.program_id
            JOIN (SELECT @rownum := NULL, @category_id := "") r
            ORDER BY p.category_id) x
        WHERE x.rank <= 2'*/
        $query = $em->createQuery(
            'SELECT v FROM   HopeRestBundle:Episode v LEFT JOIN HopeRestBundle:Program p WITH p.id = v.program_id
            WHERE ( SELECT COUNT(v1) FROM HopeRestBundle:Episode v1 LEFT JOIN HopeRestBundle:Program p1 WITH p1.id = v1.program_id WHERE p1.category_id = p.category_id AND v1.id >= v.id ) <= 2'
        );
        $topvideos = $query->getResult();
        $videoList = array();
        foreach($topvideos as $key=>$video){
            $videoList[$key]['id'] = $video->getId();
            $videoList[$key]['code'] = $video->getCode();
            $videoList[$key]['title'] = $video->getTitle();
        }

        $settings['top_videos'] = $videoList;
        unset($videoList);

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
        $settingsJSON = json_encode($settings);

// вывод
        $response = new Response($settingsJSON);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}