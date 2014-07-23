<?php
// src/Acme/StoreBundle/Entity/Program.php
namespace Hope\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="program")
 */
class Program {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=4)
     */
    protected $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $desc_short;

    /**
     * @ORM\Column(type="text")
     */
    protected $desc_full;

    /**
     * @ORM\Column(type="integer")
     */
    protected $category_id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="programs")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
    */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="program")
     */
    protected $videos;


    public function __construct()
    {
        $this->videos = new ArrayCollection();
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
     * @return Program
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
     * @return Program
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
     * Set desc_short
     *
     * @param string $descShort
     * @return Program
     */
    public function setDescShort($descShort)
    {
        $this->desc_short = $descShort;

        return $this;
    }

    /**
     * Get desc_short
     *
     * @return string 
     */
    public function getDescShort()
    {
        return $this->desc_short;
    }

    /**
     * Set desc_full
     *
     * @param string $descFull
     * @return Program
     */
    public function setDescFull($descFull)
    {
        $this->desc_full = $descFull;

        return $this;
    }

    /**
     * Get desc_full
     *
     * @return string 
     */
    public function getDescFull()
    {
        return $this->desc_full;
    }


    /**
     * Get category
     *
     *
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category_id
     *
     * @param integer $categoryId
     * @return Program
     */
    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;

        return $this;
    }

    /**
     * Get category_id
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set category
     *
     * @param \Hope\RestBundle\Entity\Category $category
     * @return Program
     */
    public function setCategory(\Hope\RestBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Add videos
     *
     * @param \Hope\RestBundle\Entity\Episode $videos
     * @return Program
     */
    public function addVideo(\Hope\RestBundle\Entity\Episode $videos)
    {
        $this->videos[] = $videos;

        return $this;
    }

    /**
     * Remove videos
     *
     * @param \Hope\RestBundle\Entity\Episode $videos
     */
    public function removeVideo(\Hope\RestBundle\Entity\Episode $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideos()
    {
        return $this->videos;
    }
}
