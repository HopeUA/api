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

        //поиск по тексту
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

        $query->andWhere('e.publish_time <= :now')
              ->setParameter('now', new \DateTime());
        $query->andWhere('e.watch <> :empty')
              ->setParameter('empty', '');
        $query->orderBy('e.publish_time', 'DESC');
        $query->setMaxResults($quantity);
        $query->setFirstResult($offset);
        $results = $query->getQuery()->getResult();
        return $results;
    }

    public function getTopTwoVideos($ids = array()){

        $implodeIds = implode(',', $ids);
        $sql = "SELECT e.* FROM (SELECT v.*, p.code as program FROM video v LEFT JOIN program p ON p.id = v.program_id WHERE v.program_id IN(".$implodeIds.") AND v.publish_time <= NOW() AND v.watch <> '' ORDER BY v.publish_time DESC) e ORDER by e.publish_time DESC LIMIT 0,2";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();

        foreach ($results as $key=>$result){
            $results[$key]['duration'] = intval($result['duration']);
            $results[$key]['hd'] = ($result['hd']?true:false);
        }

        $entityResult = json_decode(json_encode($results));

        return $entityResult;
    }
} 