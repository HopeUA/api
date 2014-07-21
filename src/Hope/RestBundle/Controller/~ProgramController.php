<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends Controller
{
    public function indexAction()
    {
        //получаем список всех категорий
        $programs = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Program')
            ->findAll();


        // TODO вынести в отдельный метод - все методы Сущности становятся элементами массива
        $programsList = array();
        foreach($programs as $key => $obj){
            $programsList[$key]['id'] = $obj->getId();
            $programsList[$key]['title'] = $obj->getTitle();
        }

        //формат JSON
        $progs = json_encode($programsList);


        //$html = $this->renderView('HopeRestBundle:Default:index.html.twig', array( 'categories' => $cats ) );
        //вывод
        $response = new Response($progs);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function programAction($id){
        //получаем список всех программ
        $getProgram = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Program')
            ->find($id);

        $program = array();
        $program['id'] = $getProgram->getId();
        $program['title'] = $getProgram->getTitle();

        //формат JSON
        $prog = json_encode($category);


        //вывод
        $response = new Response($prog);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function categoryAction($cat_id){

        //получаем список программ данной категории

    }
}
