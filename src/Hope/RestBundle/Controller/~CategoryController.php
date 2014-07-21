<?php

namespace Hope\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function indexAction()
    {
        //получаем список всех категорий
        $categories = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Category')
            ->findAll();


        // TODO вынести в отдельный метод - все методы Сущности становятся элементами массива
        $categoriesList = array();
        foreach($categories as $key => $obj){
            $categoriesList[$key]['id'] = $obj->getId();
            $categoriesList[$key]['title'] = $obj->getTitle();
        }

        //формат JSON
        $cats = json_encode($categoriesList);


        //$html = $this->renderView('HopeRestBundle:Default:index.html.twig', array( 'categories' => $cats ) );
        //вывод
        $response = new Response($cats);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function categoryAction($id){
        //получаем список всех категорий
        $getCategory = $this->getDoctrine()
            ->getRepository('HopeRestBundle:Category')
            ->find($id);

        $categor = array();
        $category['id'] = $getCategory->getId();
        $category['title'] = $getCategory->getTitle();

        //формат JSON
        $cat = json_encode($category);


        //вывод
        $response = new Response($cat);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
