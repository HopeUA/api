<?php
// src/Hope/RestBundle/Entity/Category.php
namespace Hope\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort;

    /**
     * @ORM\OneToMany(targetEntity="Program", mappedBy="category")
     */
    protected $programs;


    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    /**
     * Set Id
     *
     * @param integer $id
     * @return Category
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
     * Set title
     *
     * @param string $title
     * @return Category
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
     * Set sort
     *
     * @param string $order
     * @return Category
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Get programs
     *
     * @return string
     */
    public function getPrograms()
    {
        return $this->programs;
    }

    /**
     * Add programs
     *
     * @param \Hope\RestBundle\Entity\Program $programs
     * @return Category
     */
    public function addProgram(\Hope\RestBundle\Entity\Program $programs)
    {
        $this->programs[] = $programs;

        return $this;
    }

    /**
     * Remove programs
     *
     * @param \Hope\RestBundle\Entity\Program $programs
     */
    public function removeProgram(\Hope\RestBundle\Entity\Program $programs)
    {
        $this->programs->removeElement($programs);
    }
}
