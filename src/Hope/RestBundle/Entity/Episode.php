<?php

namespace Hope\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="video")
 */
class Episode {

        /**
         * @ORM\Column(type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
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
        protected $desc;

        /**
         * @ORM\Column(type="string", length=150)
         */
        protected $author;


        /**
         * @ORM\ManyToOne(targetEntity="Program", inversedBy="videos")
         * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
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
         * @ORM\Column(type="string", length=200)
         */
        protected $image;

        /**
         * @ORM\Column(type="string", length=255)
         */
        protected $download;

        /**
         * @ORM\Column(type="string", length=255)
         */
        protected $watch;

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
     * Set desc
     *
     * @param string $desc
     * @return Episode
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string 
     */
    public function getDesc()
    {
        return $this->desc;
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
     * Set image
     *
     * @param string $image
     * @return Episode
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set download
     *
     * @param string $download
     * @return Episode
     */
    public function setDownload($download)
    {
        $this->download = $download;

        return $this;
    }

    /**
     * Get download
     *
     * @return string 
     */
    public function getDownload()
    {
        return $this->download;
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
     * @param \Hope\RestBundle\Entity\Program $program
     * @return Episode
     */
    public function setProgram(\Hope\RestBundle\Entity\Program $program = null)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return \Hope\RestBundle\Entity\Program 
     */
    public function getProgram()
    {
        return $this->program;
    }
}
