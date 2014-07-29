<?php
namespace Hope\RestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $output->writeln('DB Connected.');

        $output->writeln('Category Read Started.');

        //читаем категории
        $categories = $doctrine
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
        foreach($categoryList as $category){
            $categoryEntity = new Category();
            $categoryEntity->setId($category['c_id']);
            $categoryEntity->setTitle($category['c_name']);
            $categoryEntity->setSort($category['c_order']);
            $em->persist($categoryEntity);
        }
        $em->flush();

        $output->writeln('');
        $output->writeln('Program Read Started.');

        //читаем программы
        $programs = $doctrine
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


        $output->writeln('Program Import Started');

        //записываем программы
        foreach($programList as $program){
            $programEntity = new Program();

            $programEntity->setId($program['cat_id']);
            $programEntity->setCode($program['cat_alias']);
            $programEntity->setTitle($program['cat_name']);
            $programEntity->setDescShort($program['cat_shortdesc']);
            $programEntity->setDescFull($program['cat_desc']);

            $parent = $doctrine
                ->getRepository('HopeRestBundle:Category')
                ->find($program['cat_category'])
            ;
            $programEntity->setCategory($parent);

            $em->persist($programEntity);
        }
        $em->flush();

        $output->writeln('');
        $output->writeln('Video Read Started');

        //читаем видео
        $offset = 0;


        while($videos = $doctrine->getRepository('HopeImport:HopeVideo', 'dbimport')->findBy(array(), array('v_id'=>'ASC'),100, $offset)){
            $key = 0;
            $videoList = array();
            foreach($videos as $obj){
                $videoList[$key]['v_id']     = $obj->getVId();
                $videoList[$key]['v_serial'] = $obj->getVSerial();
                $videoList[$key]['v_name']   = $obj->getVName();
                $videoList[$key]['v_cat']    = $obj->getVCat();
                $videoList[$key]['v_date']   = $obj->getVDate();
                $videoList[$key]['v_desc']   = $obj->getVDesc();
                $videoList[$key]['v_time']   = $obj->getVTime();
                $videoList[$key]['v_author'] = $obj->getVAuthor();
                $videoList[$key]['v_wide']   = $obj->getVWide();
                $videoList[$key]['youtube']  = $obj->getYoutube();
                $videoList[$key]['duration'] = $obj->getDuration();
                ++$key;
            }

            //записываем видео
            foreach($videoList as $video){
                $videoEntity = new Episode();

                $parent = $doctrine
                    ->getRepository('HopeRestBundle:Program')
                    ->findByCode($video['v_cat'])
                ;
                if(!empty($parent[0])){
                    $videoEntity->setProgram($parent[0]);
                }else{
                    continue;
                }

                $videoEntity->setId($video['v_id']);
                $videoEntity->setCode($video['v_serial']);
                $videoEntity->setTitle($video['v_name']);
                $videoEntity->setDescription($video['v_desc']);
                $videoEntity->setAuthor($video['v_author']);
                $videoEntity->setDuration($video['duration']);

                $timeStamp = strtotime($video['v_date']);
                $dateEntity = new \DateTime(date("Y-m-d H:i:s", $timeStamp));
                $videoEntity->setPublishTime($dateEntity);

                $videoEntity->setHd($video['v_wide']);
                //$videoEntity->setImage('http://share.yourhope.tv/'.$video['v_serial'].'.jpg');
                //$videoEntity->setDownload('http://share.yourhope.tv/'.$video['v_serial'].'.mov');
                $videoEntity->setWatch($video['youtube']);

                $em->persist($videoEntity);
            }
            $em->flush();

            $offset += 100;
        }


        $output->writeln('Video Finished');


    }


}