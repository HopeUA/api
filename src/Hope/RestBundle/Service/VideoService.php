<?php
namespace Hope\RestBundle\Service;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Doctrine\ORM\EntityManager;

class VideoService
{

    private $entityManager;
    private $options;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getVideos($params)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($params);

        if (count($this->options)>1) {
            $videos = $this->entityManager->getRepository('HopeRestBundle:Episode')
                ->getByParams($this->options);
        }

        $videoList = [];
        if (!empty($videos)) {
            foreach ($videos as $key => $video) {
                $videoList[$key]['code'] = $video->getCode();
                $videoList[$key]['title'] = $video->getTitle();
                $videoList[$key]['desc'] = $video->getDescription();
                $videoList[$key]['author'] = $video->getAuthor();
                $videoList[$key]['duration'] = $video->getDuration();
                $videoList[$key]['publish_time'] = $video->getPublishTime()->format('Y-m-d H:i:s');
                $videoList[$key]['hd'] = $video->getHd();
                $videoList[$key]['image'] = 'http://share.yourhope.tv/' . $video->getCode() . '.jpg';
                $videoList[$key]['link'] = array(
                    "download" => 'http://share.yourhope.tv/' . $video->getCode() . '.mov',
                    "watch" => 'https://www.youtube.com/watch?v=' . $video->getWatch()
                );
                $programVideo = $video->getProgram();
                $videoList[$key]['program'] = $programVideo->getCode();
            }

        }

        return $videoList;
    }

    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'limit' => '0|10'
        ));

        $resolver->setOptional(array('program_code'));
        $resolver->setOptional(array('program_category'));
        $resolver->setOptional(array('code'));
        $resolver->setOptional(array('text'));
        $resolver->setOptional(array('sort'));
        $resolver->setOptional(array('limit'));
    }
}
