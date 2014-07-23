<?php
namespace Hope\RestBundle\Entity\Import;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hope_video_files2")
 */
class HopeVideo {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $v_id;

    /**
     * @ORM\Column(type="string", length=9)
     */
    protected $v_serial;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $v_name;

    /**
     * @ORM\Column(type="string", length=4)
     */
    protected $v_cat;

    /**
     * @ORM\Column(type="string", length=8)
     */
    protected $v_date;

    /**
     * @ORM\Column(type="text")
     */
    protected $v_desc;

    /**
     * @ORM\Column(type="integer")
     */
    protected $v_time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $v_author;

    /**
     * @ORM\Column(type="integer")
     */
    protected $v_wide;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $youtube;

    /**
     * @ORM\Column(type="integer")
     */
    protected $duration;

    /**
     * Get v_id
     *
     * @return integer 
     */
    public function getVId()
    {
        return $this->v_id;
    }

    /**
     * Set v_serial
     *
     * @param string $vSerial
     * @return HopeVideo
     */
    public function setVSerial($vSerial)
    {
        $this->v_serial = $vSerial;

        return $this;
    }

    /**
     * Get v_serial
     *
     * @return string 
     */
    public function getVSerial()
    {
        return $this->v_serial;
    }

    /**
     * Set v_name
     *
     * @param string $vName
     * @return HopeVideo
     */
    public function setVName($vName)
    {
        $this->v_name = $vName;

        return $this;
    }

    /**
     * Get v_name
     *
     * @return string 
     */
    public function getVName()
    {
        return $this->v_name;
    }

    /**
     * Set v_cat
     *
     * @param string $vCat
     * @return HopeVideo
     */
    public function setVCat($vCat)
    {
        $this->v_cat = $vCat;

        return $this;
    }

    /**
     * Get v_cat
     *
     * @return string 
     */
    public function getVCat()
    {
        return $this->v_cat;
    }

    /**
     * Set v_date
     *
     * @param string $vDate
     * @return HopeVideo
     */
    public function setVDate($vDate)
    {
        $this->v_date = $vDate;

        return $this;
    }

    /**
     * Get v_date
     *
     * @return string 
     */
    public function getVDate()
    {
        return $this->v_date;
    }

    /**
     * Set v_desc
     *
     * @param string $vDesc
     * @return HopeVideo
     */
    public function setVDesc($vDesc)
    {
        $this->v_desc = $vDesc;

        return $this;
    }

    /**
     * Get v_desc
     *
     * @return string 
     */
    public function getVDesc()
    {
        return $this->v_desc;
    }

    /**
     * Set v_time
     *
     * @param integer $vTime
     * @return HopeVideo
     */
    public function setVTime($vTime)
    {
        $this->v_time = $vTime;

        return $this;
    }

    /**
     * Get v_time
     *
     * @return integer 
     */
    public function getVTime()
    {
        return $this->v_time;
    }

    /**
     * Set v_author
     *
     * @param string $vAuthor
     * @return HopeVideo
     */
    public function setVAuthor($vAuthor)
    {
        $this->v_author = $vAuthor;

        return $this;
    }

    /**
     * Get v_author
     *
     * @return string 
     */
    public function getVAuthor()
    {
        return $this->v_author;
    }

    /**
     * Set v_wide
     *
     * @param integer $vWide
     * @return HopeVideo
     */
    public function setVWide($vWide)
    {
        $this->v_wide = $vWide;

        return $this;
    }

    /**
     * Get v_wide
     *
     * @return integer 
     */
    public function getVWide()
    {
        return $this->v_wide;
    }

    /**
     * Set youtube
     *
     * @param string $youtube
     * @return HopeVideo
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get youtube
     *
     * @return string 
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return HopeVideo
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
}
