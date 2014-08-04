<?php
namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Hope\RestBundle\Service\Tools;



class TimeTableController extends Controller
{
    public function indexAction(Request $request){
        $dateRequest = $request->get('date');
        if(strlen($dateRequest) >= 10 ){
            $dateRequest = strtotime($dateRequest);
            $dateRequest = date("Y-m-d", $dateRequest);
        }
        if(empty($dateRequest)){
            $dateRequest = date("Y-m-d");
        }
        
        if(Tools::validateDate($dateRequest, 'Y-m-d')){
            $params = array();
            $dateExplode = explode('-', $dateRequest);
            $params['date_from'] = $dateExplode[0].'-'.$dateExplode[1].'-'.$dateExplode[2].' 00:00:00';
            $params['date_to'] = $dateExplode[0].'-'.$dateExplode[1].'-'.$dateExplode[2].' 23:59:59';
        }

        if(!empty($params)){
            $query = $this->getDoctrine()->getManager()->createQueryBuilder();
            $query
                ->select('s')
                ->from('HopeRestBundle:Schedule', 's');
            $query->andWhere('s.issue_time >= :date_from');
            $query->setParameter('date_from',$params['date_from']);
            $query->andWhere('s.issue_time <= :date_to');
            $query->setParameter('date_to',$params['date_to']);
            //$query->orderBy('s.issue_time', 'ASC');
            $issues = $query->getQuery()->getResult();


            $issueList = array();
            $timeTable   = array();
            foreach($issues as $issue){
                $key = $issue->getId();
                $issueList['datetime'] = $issue->getIssueTime()->format("Y-m-d H:i:s");
                $issueList['program']  = $issue->getProgram();
                $issueList['episode']  = $issue->getEpisode();
                $timeTable[]   = $issueList;
            }
        }
        if(empty($timeTable)){
            $noTimeTable = array(
                'error' => true,
                'message' => 'Не нашлось записей на эту дату'
            );
        }



        if(empty($timeTable)){
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