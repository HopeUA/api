<?php
namespace Hope\RestBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Class TimeTableService
 * @package Hope\RestBundle\Service
 */
class TimeTableService
{
    private $entityManager;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get TimeTable for a Date
     *
     * @param $date
     * @return array
     * @throws \Exception
     */
    public function getTimeTableForDate($date)
    {
        if (Tools::validateDate($date, 'Y-m-d')) {
            $params = [];
            $params['date_from'] = $date.' 00:00:00';
            $params['date_to']   = $date.' 23:59:59';
        }

        $timeTable = [];
        if (!empty($params)) {
            $query = $this->entityManager->createQueryBuilder();
            $query
                ->select('s')
                ->from('HopeRestBundle:Schedule', 's');
            $query->andWhere('s.issue_time >= :date_from');
            $query->setParameter('date_from', $params['date_from']);
            $query->andWhere('s.issue_time <= :date_to');
            $query->setParameter('date_to', $params['date_to']);
            $issues = $query->getQuery()->getResult();
            $issueList = [];
            if (is_array($issues)) {
                foreach ($issues as $issue) {
                    $issueList['datetime'] = $issue->getIssueTime()->format("Y-m-d H:i:s");
                    $issueList['program'] = $issue->getProgram();
                    $issueList['episode'] = $issue->getEpisode();
                    $timeTable[] = $issueList;
                }
            } else {
                throw new \Exception('Array expected but got '.gettype($issues).' type');
            }
        }

        return $timeTable;
    }
}
