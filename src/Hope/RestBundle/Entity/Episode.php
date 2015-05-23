<?php

namespace Hope\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="Hope\RestBundle\Repository\EpisodeRepository")
 */
class Episode
{

        /**
         * @ORM\Column(type="integer")
         * @ORM\Id
         */
        protected $id;

        /**
         * @ORM\Column(type="string", length=9)
         */

        protected $code;

        /**
         * @ORM\Column(type="string", length=255)
         */
        protected $title;

        /**
         * @ORM\Column(type="text")
         */
        protected $description;

        /**
         * @ORM\Column(type="string", length=150)
         */
        protected $author;


        /**
         * @ORM\ManyToOne(targetEntity="Program", inversedBy="videos")
         * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
         */
        protected $program;

        /**
         * @ORM\Column(type="integer")
         */
        protected $program_id;

        /**
         * @ORM\Column(type="integer")
         */
        protected $duration;

        /**
         * @ORM\Column(type="datetime")
         */
        protected $publish_time;

        /**
         * @ORM\Column(type="boolean")
         */
        protected $hd;

        /**
         * @ORM\Column(type="string", length=255)
         */
        protected $watch;

    /**
     * Set id
     *
     * @param string $id
     * @return Episode
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
     * Set code
     *
     * @param string $code
     * @return Episode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Episode
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Episode
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Episode
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set program_id
     *
     * @param integer $programId
     * @return Episode
     */
    public function setProgramId($programId)
    {
        $this->program_id = $programId;

        return $this;
    }

    /**
     * Get program_id
     *
     * @return integer 
     */
    public function getProgramId()
    {
        return $this->program_id;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Episode
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set publish_time
     *
     * @param \DateTime $publishTime
     * @return Episode
     */
    public function setPublishTime($publishTime)
    {
        $this->publish_time = $publishTime;

        return $this;
    }

    /**
     * Get publish_time
     *
     * @return \DateTime 
     */
    public function getPublishTime()
    {
        return $this->publish_time;
    }

    /**
     * Set hd
     *
     * @param boolean $hd
     * @return Episode
     */
    public function setHd($hd)
    {
        $this->hd = $hd;

        return $this;
    }

    /**
     * Get hd
     *
     * @return boolean 
     */
    public function getHd()
    {
        return $this->hd;
    }

    /**
     * Set watch
     *
     * @param string $watch
     * @return Episode
     */
    public function setWatch($watch)
    {
        $this->watch = $watch;

        return $this;
    }

    /**
     * Get watch
     *
     * @return string 
     */
    public function getWatch()
    {
        return $this->watch;
    }

    /**
     * Set program
     *
     * @param Program $program
     * @return Episode
     */
    public function setProgram(Program $program = null)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return Program
     */
    public function getProgram()
    {
        return $this->program;
    }
}
