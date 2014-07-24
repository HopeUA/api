<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;

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
                array('sort' => 'ASC')
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
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('HopeRestBundle:Episode', 'v');
        $rsm->addFieldResult('v', 'id', 'id');
        $rsm->addFieldResult('v', 'code', 'code');
        $rsm->addFieldResult('v', 'title', 'title');

/*      // этот запрос в самом MySQL выполняет 4мс
        // http://sqlfiddle.com/#!2/6ad8c6/52
        // но в Доктрине выдает пустой результат
        $query = $em->createNativeQuery("
            SELECT VID, VTitle FROM (
            SELECT SequencedSet.*, @Rnum := if(mvcat = CTitle, @Rnum + 1, 1) RowNumber from (
            SELECT CTitle, VCode, VTitle, VID, @vcat mvcat,
              @VCat := CTitle  as VCAT
            FROM (
            SELECT C.title CTitle, V.code VCode, V.title VTitle, V.id VID
            FROM video V
            INNER JOIN program P
            on P.id = V.program_id
            INNER JOIN category C
            on C.id = P.category_id
            ORDER BY C.title, V.id DESC) OrderedSet) SequencedSet) X
            where X.ROWNUMBER<=2
            ", $rsm);
*/
        // этот запрос в самом MySQL выполняет 2мс
        $query = $em->createQuery(
            'SELECT v FROM   HopeRestBundle:Episode v LEFT JOIN HopeRestBundle:Program p WITH p.id = v.program_id
            WHERE ( SELECT COUNT(v1.id) FROM HopeRestBundle:Episode v1 LEFT JOIN HopeRestBundle:Program p1 WITH p1.id = v1.program_id WHERE p1.category_id = p.category_id AND v1.id >= v.id ) <= 2'
        );

        $topVideos = $query->getResult();
/*      print '<pre>';
        print_r($topVideos);
        print '</pre>';
        die();
*/
        $videoList = array();
        foreach($topVideos as $key=>$video){
            $videoList[$key]['id'] = $video->getId();
            $videoList[$key]['code'] = $video->getCode();
            $videoList[$key]['title'] = $video->getTitle();
            $videoList[$key]['descr'] = $video->getDescription();
            $videoList[$key]['author'] = $video->getAuthor();
            $videoList[$key]['duration'] = $video->getDuration();
            $videoList[$key]['publish_time'] = $video->getPublishTime();
            $videoList[$key]['hd'] = $video->getHd();
            $videoList[$key]['image'] = $video->getImage();
            $videoList[$key]['link'] = array(
                "download" => $video->getDownload(),
                "watch"    => $video->getWatch()

            );
            $programVideo = $video->getProgram();
            $videoList[$key]['program'] = $programVideo->getCode();

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