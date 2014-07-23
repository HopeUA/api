<?php
namespace Hope\RestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Hope\RestBundle\Entity\Category;
use Hope\RestBundle\Entity\Program;
use Hope\RestBundle\Entity\Episode;


class ImportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('db:import')
            ->setDescription('Hope DB Import')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
        $doctrine = $this->getContainer();
        $em = $doctrine->get('doctrine')->getManager();

        $output->writeln('DB Connected.');
        $output->writeln('Category Read Started.');

        //читаем категории
        $categories = $doctrine->get('doctrine')
            ->getRepository('HopeImport:HopeCategory', 'dbimport')
            ->findAll()
        ;

        $categoryList = array();
        foreach($categories as $key => $obj){
            $categoryList[$key]['c_id']    = $obj->getCId();
            $categoryList[$key]['c_name']  = $obj->getCName();
            $categoryList[$key]['c_order'] = $obj->getCOrder();
        }

        $output->writeln('Category Import Started');

        //записываем категории
        foreach($categoryList as $key => $category){
            $categoryEntity = new Category();
            $categoryEntity->setId($category['c_id']);
            $categoryEntity->setTitle($category['c_name']);
            $categoryEntity->setSort($category['c_order']);
            $em->persist($categoryEntity);
        }
        $em->flush();

        $output->writeln('Program Read Started.');

        //читаем программы
        $programs = $doctrine->get('doctrine')
            ->getRepository('HopeImport:HopeProgram', 'dbimport')
            ->findAll()
        ;

        $programList = array();
        foreach($programs as $key => $obj){
            $programList[$key]['cat_id']        = $obj->getCatId();
            $programList[$key]['cat_alias']     = $obj->getCatAlias();
            $programList[$key]['cat_name']      = $obj->getCatName();
            $programList[$key]['cat_shortdesc'] = $obj->getCatShortdesc();
            $programList[$key]['cat_desc']      = $obj->getCatDesc();
            $programList[$key]['cat_category']  = $obj->getCatCategory();
        }

        //записываем программы
        foreach($programList as $key => $program){
            $programEntity = new Program();

            $categoryEntity->setCatId($program['cat_id']);
            $categoryEntity->setCatAlias($program['cat_alias']);
            $categoryEntity->setCatName($program['cat_name']);
            $categoryEntity->setCatShortdesc($program['cat_shortdesc']);
            $categoryEntity->setCatDesc($program['cat_desc']);
            $categoryEntity->setCatCategory($program['cat_category']);

            $em->persist($programEntity);
        }
        $em->flush();

        $output->writeln('Program Import Started');



    }


}