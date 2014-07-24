<?php

namespace Hope\RestBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EpisodeRepository extends EntityRepository
{
    public function getByParams($params)
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query
            ->select('e')
            ->from('HopeRestBundle:Episode', 'e');


        //код видео
        if(!empty($params['code'])){
            $query->andWhere('e.code = :code');
            $query->setParameter('code', $params['code']);
        }

        //код программы
        if(!empty($params['program_code'])){

            $em = $this->getEntityManager()
                ->getRepository('HopeRestBundle:Program')
                ->findByCode($params['program_code']);
            $programId = $em[0]->getId();

            $query->andWhere('e.program_id = :program_id');
            $query->setParameter('program_id', $programId);
        }

        //код категории
        if(!empty($params['program_category'])) {
            $query->leftjoin('HopeRestBundle:Program', 'p');
            $query->andWhere('p.category_id = :program_category');
            $query->setParameter('program_category',$params['program_category']);
        }

        if(!empty($params['text'])) {
            $query->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('e.title', ':text'),
                    $query->expr()->like('e.description', ':text'),
                    $query->expr()->like('e.author', ':text')
                )
            );
            $query->setParameter('text','%'.$params['text'].'%');

        }


        if(empty($params['limit'])){
            $params['limit'] = '0|10';
        }
        $limit              = explode('|',$params['limit']);
        $offset             = $limit[0];
        $quantity           = $limit[1];



        $query->setMaxResults($quantity);
        $query->setFirstResult($offset);
        $results = $query->getQuery()->getResult();

/*        $qqq = $query->getQuery();
        print_r(array(
            'sql'        => $qqq->getSQL(),
            'parameters' => $qqq->getParameters(),
        ));
        die();
*/
        return $results;
    }
} 