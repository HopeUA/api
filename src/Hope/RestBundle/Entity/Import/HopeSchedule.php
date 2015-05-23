<?php

namespace Hope\RestBundle\Entity\Import;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hope_programs")
 */
class HopeSchedule
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $program;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $series;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $ptime;

    /**
     * @ORM\Column(type="string", length=8)
     */
    protected $duration;

    /**
     * @ORM\Column(type="integer")
     */
    protected $checked_out;

    /**
     * @ORM\Column(type="integer")
     */
    protected $state;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $session;

    /**
     * Set id
     *
     * @param integer $id
     * @return HopeSchedule
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
     * Set program
     *
     * @param string $program
     * @return HopeSchedule
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
     * Set series
     *
     * @param string $series
     * @return HopeSchedule
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return string 
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set ptime
     *
     * @param \DateTime $ptime
     * @return HopeSchedule
     */
    public function setPtime($ptime)
    {
        $this->ptime = $ptime;

        return $this;
    }

    /**
     * Get ptime
     *
     * @return \DateTime 
     */
    public function getPtime()
    {
        return $this->ptime;
    }

    /**
     * Set duration
     *
     * @param string $duration
     * @return HopeSchedule
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set checked_out
     *
     * @param integer $checkedOut
     * @return HopeSchedule
     */
    public function setCheckedOut($checkedOut)
    {
        $this->checked_out = $checkedOut;

        return $this;
    }

    /**
     * Get checked_out
     *
     * @return integer 
     */
    public function getCheckedOut()
    {
        return $this->checked_out;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return HopeSchedule
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set session
     *
     * @param \DateTime $session
     * @return HopeSchedule
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return \DateTime 
     */
    public function getSession()
    {
        return $this->session;
    }
}
