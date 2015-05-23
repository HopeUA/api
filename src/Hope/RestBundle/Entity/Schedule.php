<?php

namespace Hope\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="schedule")
 */
class Schedule
{


    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $issue_time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $program;


    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $episode;

    /**
     * Set id
     *
     * @param integer $id
     * @return Schedule
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set issue_time
     *
     * @param \DateTime $issueTime
     * @return Schedule
     */
    public function setIssueTime($issueTime)
    {
        $this->issue_time = $issueTime;

        return $this;
    }

    /**
     * Get issue_time
     *
     * @return \DateTime 
     */
    public function getIssueTime()
    {
        return $this->issue_time;
    }

    /**
     * Set program
     *
     * @param string $program
     * @return Schedule
     */
    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return string 
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set episode
     *
     * @param string $episode
     * @return Schedule
     */
    public function setEpisode($episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return string 
     */
    public function getEpisode()
    {
        return $this->episode;
    }
}
