<?php
namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Hope\RestBundle\Service\Tools;

/**
 * Class TimeTableController
 * @package Hope\RestBundle\Controller
 */
class TimeTableController extends Controller
{
    /**
     * Index Action
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request){
        $dateRequest = $request->get('date');
        if(strlen($dateRequest) >= 10 ){
            $dateRequest = strtotime($dateRequest);
            $dateRequest = date("Y-m-d", $dateRequest);
        }
        if(empty($dateRequest)){
            $dateRequest = date("Y-m-d");
        }
        
        $timeTable = $this->get('hope.timetable.service')->getTimeTableForDate($dateRequest);

        if(empty($timeTable)){
            $noTimeTable = array(
                'error' => true,
                'message' => 'Не нашлось записей на эту дату'
            );
            $jsonIssues = json_encode($noTimeTable, JSON_UNESCAPED_UNICODE);
            $response = new Response($jsonIssues, 404);
            $response->headers->set('Content-Type', 'application/json; charset=utf8');

        }else{
            $jsonIssues = json_encode($timeTable, JSON_UNESCAPED_UNICODE);
            $response = new Response($jsonIssues);
            $response->headers->set('Content-Type', 'application/json; charset=utf8');
        }

        return $response;

    }


} 