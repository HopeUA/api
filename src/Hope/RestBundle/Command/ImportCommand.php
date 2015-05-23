<?php
namespace Hope\RestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Hope\RestBundle\Entity\Category;
use Hope\RestBundle\Entity\Program;
use Hope\RestBundle\Entity\Episode;
use Hope\RestBundle\Entity\Schedule;

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

        $doctrine  = $this->getContainer()->get('doctrine');
        $em        = $doctrine->getManager();
        $em_import = $doctrine->getManager('dbimport');

        $time_start = $this->microtimeFloat();

        $output->writeln('DB Connected.');

        $output->writeln('');
        $output->writeln('Category Read Started.');

        //читаем категории
        $categories = $doctrine
            ->getRepository('HopeImport:HopeCategory', 'dbimport')
            ->findAll()
        ;

        $categoryList    = array();
        $allCategoriesId = array();
        foreach ($categories as $key => $obj) {
            $allCategoriesId[]             = $obj->getCId();
            $categoryList[$key]['c_id']    = $obj->getCId();
            $categoryList[$key]['c_name']  = $obj->getCName();
            $categoryList[$key]['c_order'] = $obj->getCOrder();
        }

        //записываем категории
        foreach ($categoryList as $category) {

            //читаем категории API
            $apiCategory = $doctrine
                ->getRepository('HopeRestBundle:Category', 'default')
                ->find($category['c_id'])
            ;
            if ($apiCategory!=null) {
                $apiCategoryId = $apiCategory->getId();
            }
            if (empty($apiCategoryId)) {
                $categoryEntity = new Category();
                $categoryEntity->setId($category['c_id']);
                $categoryEntity->setTitle($category['c_name']);
                $categoryEntity->setSort($category['c_order']);
                $em->persist($categoryEntity);

            } else {
                $catTitle = $apiCategory->getTitle();
                $catSort  = $apiCategory->getSort();

                if ($catTitle != $category['c_name'] && !empty($category['c_name'])) {
                    $apiCategory->setTitle($category['c_name']);
                }
                if ($catSort != $category['c_order'] && !empty($category['c_order'])) {
                    $apiCategory->setSort($category['c_order']);
                }
            }

        }

        $em->flush();

        $output->writeln('');
        $output->writeln('Program Read Started.');

        //читаем программы
        $programs = $doctrine
            ->getRepository('HopeImport:HopeProgram', 'dbimport')
            ->findAll()
        ;

        $programList   = array();
        $allProgramsCode = array();
        foreach ($programs as $key => $obj) {
            if (mb_strlen($obj->getCatAlias())!=4) {
                //пропускаем программу если код программы не равен 4
                continue;
            }
            $allProgramsCode[]                  = $obj->getCatAlias();
            $programList[$key]['cat_id']        = $obj->getCatId();
            $programList[$key]['cat_alias']     = $obj->getCatAlias();
            $programList[$key]['cat_name']      = $obj->getCatName();
            $programList[$key]['cat_shortdesc'] = $obj->getCatShortdesc();
            $programList[$key]['cat_desc']      = $obj->getCatDesc();
            $programList[$key]['cat_category']  = $obj->getCatCategory();
        }

        //записываем программы
        foreach ($programList as $program) {

            $apiProgramId = null;

            //читаем категории API
            $apiProgram = $doctrine
                ->getRepository('HopeRestBundle:Program', 'default')
                ->findOneByCode($program['cat_alias'])
            ;

            if ($apiProgram != null) {
                $apiProgramId = $apiProgram->getId();
            }

            if (empty($apiProgramId)) {
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

            } else {
                $programCode        = $apiProgram->getCode();
                $programTitle       = $apiProgram->getTitle();
                $programDescShort   = $apiProgram->getDescShort();
                $programDescFull    = $apiProgram->getDescFull();
                $programCategory    = $apiProgram->getCategory()->getId();

                if ($programCode != $program['cat_alias'] && !empty($program['cat_alias'])) {
                    $apiProgram->setCode($program['cat_alias']);
                }
                if ($programTitle != $program['cat_name'] && !empty($program['cat_name'])) {
                    $apiProgram->setTitle($program['cat_name']);
                }
                if ($programDescShort != $program['cat_shortdesc'] && !empty($program['cat_shortdesc'])) {
                    $apiProgram->setDescShort($program['cat_shortdesc']);
                }
                if ($programDescFull != $program['cat_desc'] && !empty($program['cat_desc'])) {
                    $apiProgram->setDescFull($program['cat_desc']);
                }
                if ($programCategory != $program['cat_category'] && !empty($program['cat_category'])) {
                    $parent = $doctrine
                        ->getRepository('HopeRestBundle:Category')
                        ->find($program['cat_category'])
                    ;
                    $apiProgram->setCategory($parent);
                }
            }
        }
        $em->flush();

        $output->writeln('');
        $output->writeln('Video Read Started');

        //читаем видео
        $offset      = 0;
        $allVideosId = array();
        while (
            $videos = $doctrine->getRepository('HopeImport:HopeVideo', 'dbimport')->findBy(
                array(),
                array('v_id'=>'ASC'),
                1000,
                $offset
            )
        ) {
            $key       = 0;
            $videoList = array();
            foreach ($videos as $obj) {
                $allVideosId[]               = $obj->getVId();
                $videoList[$key]['v_id']     = $obj->getVId();
                $videoList[$key]['v_serial'] = $obj->getVSerial();
                $videoList[$key]['v_name']   = $obj->getVName();
                $videoList[$key]['v_cat']    = $obj->getVCat();
                $videoList[$key]['v_desc']   = $obj->getVDesc();
                $videoList[$key]['v_time']   = $obj->getVTime();
                $videoList[$key]['v_author'] = $obj->getVAuthor();
                $videoList[$key]['v_wide']   = $obj->getVWide();
                $videoList[$key]['youtube']  = $obj->getYoutube();
                $videoList[$key]['duration'] = $obj->getDuration();
                ++$key;
            }

            //записываем видео
            foreach ($videoList as $video) {

                $videoParent = $doctrine
                    ->getRepository('HopeRestBundle:Program')
                    ->findOneByCode($video['v_cat'])
                ;

                //если нет родительской программы - не записывать в БД
                if (empty($videoParent)) {
                    continue;
                }

                $apiVideo = $doctrine
                    ->getRepository('HopeRestBundle:Episode')
                    ->findOneByCode($video['v_serial'])
                ;
                unset($apiVideoId);
                if ($apiVideo != null) {
                    $apiVideoId = $apiVideo->getId();
                }


                if (empty($apiVideoId)) {
                    $videoEntity = new Episode();

                    $videoEntity->setProgram($videoParent);
                    $videoEntity->setId($video['v_id']);
                    $videoEntity->setCode($video['v_serial']);
                    $videoEntity->setTitle($video['v_name']);
                    $videoEntity->setDescription($video['v_desc']);
                    $videoEntity->setAuthor($video['v_author']);
                    $videoEntity->setDuration($video['duration']);
                    $vTime = new \DateTime(date("Y-m-d H:i:s", $video['v_time']));
                    $videoEntity->setPublishTime($vTime);
                    $videoEntity->setHd($video['v_wide']);
                    $videoEntity->setWatch($video['youtube']);

                    $em->persist($videoEntity);
                } else {
                    $videoCode        = $apiVideo->getCode();
                    $videoTitle       = $apiVideo->getTitle();
                    $videoDesc        = $apiVideo->getDescription();
                    $videoAuthor      = $apiVideo->getAuthor();
                    $videoDuration    = $apiVideo->getDuration();
                    $videoPublishTime = $apiVideo->getPublishTime();
                    $videoHd          = $apiVideo->getHd();
                    $videoWatch       = $apiVideo->getWatch();
                    $videoProgram     = $apiVideo->getProgram();
                    $videoDate        = date("Y-m-d H:i:s", $video['v_time']);
                    $vTime            = new \DateTime($videoDate);

                    if ($videoCode != $video['v_serial'] && !empty($video['v_serial'])) {
                        $apiVideo->setCode($video['v_serial']);
                    }
                    if ($videoTitle != $video['v_name'] && !empty($video['v_name'])) {
                        $apiVideo->setTitle($video['v_name']);
                    }
                    if ($videoDesc != $video['v_desc'] && !empty($video['v_desc'])) {
                        $apiVideo->setDescription($video['v_desc']);
                    }
                    if ($videoAuthor != $video['v_author'] && !empty($video['v_author'])) {
                        $apiVideo->setAuthor($video['v_author']);
                    }
                    if ($videoDuration != $video['duration'] && !empty($video['duration'])) {
                        $apiVideo->setDuration($video['duration']);
                    }
                    if ($videoPublishTime != $vTime && !empty($vTime)) {
                        $newTime = new \DateTime(date("Y-m-d H:i:s", $video['v_time']));
                        $apiVideo->setPublishTime($newTime);
                    }
                    if ($videoHd != $video['v_wide'] && !empty($video['v_wide'])) {
                        $apiVideo->setHd($video['v_wide']);
                    }
                    if ($videoWatch != $video['youtube'] && !empty($video['youtube'])) {
                        $apiVideo->setWatch($video['youtube']);
                    }

                    if ($videoProgram != $videoParent && !empty($videoParent)) {
                        $apiVideo->setProgram($videoParent);
                    }
                }
            }

            $em->flush();
            $offset += 1000;
        }

        //Video Program and Category Delete is not in prod DB
        $qb = $em->createQueryBuilder();
        $qb->delete('HopeRestBundle:Episode', 'e')
           ->where($qb->expr()->notIn('e.id', $allVideosId));
        $qb->getQuery()->execute();

        $qb = $em->createQueryBuilder();
        $qb->delete('HopeRestBundle:Program', 'p')
           ->where($qb->expr()->notIn('p.code', $allProgramsCode));
        $qb->getQuery()->execute();

        $qb = $em->createQueryBuilder();
        $qb->delete('HopeRestBundle:Category', 'c')
           ->where($qb->expr()->notIn('c.id', $allCategoriesId));
        $qb->getQuery()->execute();
        
        $output->writeln('');
        $output->writeln('Schedule Read Started');

        //читаем расписание
        $offset         = 0;
        $ptime7days     = new \DateTime('7 days ago');
        $allSchedulesId = array();

        $qb = $em_import->createQueryBuilder();
        while (
            $schedules = $qb
                ->select('s')
                ->from('HopeImport:HopeSchedule', 's')
                ->where('s.ptime >= :ptime')
                ->setParameters(array('ptime' => $ptime7days->format('Y-m-d H:i:s')))
            ->setMaxResults(1000)->setFirstResult($offset)->getQuery()->getResult()) {
            $key = 0;
            $timeList = array();
            foreach ($schedules as $obj) {
                $allSchedulesId[]               = $obj->getId();
                $timeList[$key]['id']           = $obj->getId();
                $timeList[$key]['program']      = $obj->getProgram();
                $timeList[$key]['series']       = $obj->getSeries();
                $timeList[$key]['ptime']        = $obj->getPtime();
                $timeList[$key]['duration']     = $obj->getDuration();
                $timeList[$key]['checked_out']  = $obj->getCheckedOut();
                $timeList[$key]['state']        = $obj->getState();
                $timeList[$key]['session']      = $obj->getSession();
                ++$key;
            }

            //записываем расписание
            foreach ($timeList as $obj) {

                $apiTime = $doctrine
                    ->getRepository('HopeRestBundle:Schedule')
                    ->find($obj['id'])
                ;

                $apiTimeId = null;
                if ($apiTime != null) {
                    $apiTimeId = $apiTime->getId();
                }
                if (empty($apiTimeId)) {
                    $timeEntity = new Schedule();
                    $timeEntity->setId($obj['id']);
                    $timeEntity->setIssueTime($obj['ptime']);
                    $timeEntity->setProgram($obj['series']);
                    $timeEntity->setEpisode($obj['program']);
                    $em->persist($timeEntity);
                } else {
                    $timeIssueTime  = $apiTime->getIssueTime();
                    $timeProgram    = $apiTime->getProgram();
                    $timeEpisode    = $apiTime->getEpisode();

                    if ($timeIssueTime != $obj['ptime'] && !empty($obj['ptime'])) {
                        $apiTime->setIssueTime($obj['ptime']);
                    }

                    if ($timeProgram != $obj['series'] && !empty($obj['series'])) {
                        $apiTime->setProgram($obj['series']);
                    }

                    if ($timeEpisode != $obj['program'] && !empty($obj['program'])) {
                        $apiTime->setEpisode($obj['program']);
                    }

                }
            }

            $em->flush();

            $qb      = $em_import->createQueryBuilder();
            $offset += 1000;
        }

        $qb = $em->createQueryBuilder();
        $qb->delete('HopeRestBundle:Schedule', 's')
           ->where($qb->expr()->notIn('s.id', $allSchedulesId));
        $qb->getQuery()->execute();

        $time_end = $this->microtimeFloat();

        $output->writeln('');
        $output->writeln('<info>Successfully Finished in '.($time_end-$time_start).' s</info>');

    }

    protected function microtimeFloat()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}
