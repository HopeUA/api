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
        if(empty($dateRequest)){
            $dateRequest = date("Y-m-d");
        }
        
        if(Tools::validateDate($dateRequest, 'Y-m-d')){
            $params = array();
            $dateExplode = explode('-', $dateRequest);
            $params['date_from'] = $dateExplode[0].'-'.$dateExplode[1].'-'.$dateExplode[2].' 00:00:00';
            $params['date_to'] = $dateExplode[0].'-'.$dateExplode[1].'-'.$dateExplode[2].' 23:59:59';
        }


        $query = $this->getDoctrine()->getManager()->createQueryBuilder();
        $query
            ->select('s')
            ->from('HopeRestBundle:Schedule', 's');
        $query->andWhere('s.issue_time >= :date_from');
        $query->setParameter('date_from',$params['date_from']);
        $query->andWhere('s.issue_time <= :date_to');
        $query->setParameter('date_to',$params['date_to']);
        $issues = $query->getQuery()->getResult();


        $issueList = array();
        foreach($issues as $issue){
            $key = $issue->getId();
            $issueList[$key]['datetime'] = $issue->getIssueTime();
            $issueList[$key]['program']  = $issue->getProgram();
            $issueList[$key]['episode']  = $issue->getEpisode();
        }

        $jsonIssues = json_encode($issueList, JSON_UNESCAPED_UNICODE);

        $response = new Response($jsonIssues);
        $response->headers->set('Content-Type', 'application/json; charset=utf8');
        return $response;

    }


} 