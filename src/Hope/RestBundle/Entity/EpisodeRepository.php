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
                $query->leftjoin('HopeRestBundle:Program', 'p', 'WITH', 'e.program_id = p.id');
                $query->andWhere('p.code = :pcode');
                $query->setParameter('pcode', $params['program_code']);

        }

        //код категории
        if(!empty($params['program_category'])) {
            if(empty($params['program_code'])){
                $query->leftjoin('HopeRestBundle:Program', 'p', 'WITH', 'e.program_id = p.id');
            }
            $query->andWhere('p.category_id = :category');
            $query->setParameter('category',$params['program_category']);
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

        $limit              = explode('|',$params['limit']);
        $offset             = $limit[0]; // начинаем считывать с N записи
        $quantity           = $limit[1]; // количество выбираемых записей

        $query->orderBy('e.publish_time', 'DESC');
        $query->setMaxResults($quantity);
        $query->setFirstResult($offset);

        $results = $query->getQuery()->getResult();
        return $results;
    }

    public function getTopTwoVideos($ids = array()){

        //SELECT e.* FROM (SELECT * FROM video v WHERE v.program_id IN(7, 36) ORDER BY v.publish_time DESC) e GROUP by e.program_id LIMIT 2
       /* $subQuery = $this->getEntityManager()->createQueryBuilder();
        $subQuery
            ->select('v.id')
            ->from('HopeRestBundle:Episode', 'v');
        $subQuery->where($subQuery->expr()->in('v.program_id',$ids));
        $subQuery->orderBy('v.publish_time', 'DESC');

        $query = $this->getEntityManager()->createQueryBuilder();
        $query
            ->select('e')
            ->from('HopeRestBundle:Episode', 'e');
        $query->where($query->expr()->in('e.id',$subQuery->getDQL()));
        $query->groupBy('e.program_id');
        $query->setMaxResults(2);*/

        $implodeIds = implode(',', $ids);
        $sql = "SELECT e.* FROM (SELECT v.* FROM video v WHERE v.program_id IN(".$implodeIds.") ORDER BY v.publish_time DESC) e GROUP by e.program_id ORDER by e.publish_time DESC LIMIT 0,2";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $entityResult = json_decode(json_encode($results));

        return $entityResult;
    }
} 