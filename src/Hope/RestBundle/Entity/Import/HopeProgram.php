<?php

namespace Hope\RestBundle\Entity\Import;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hope_video_cats")
 */
class HopeProgram
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $cat_id;

    /**
     * @ORM\Column(type="string", length=4)
     */
    protected $cat_alias;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $cat_name;

    /**
     * @ORM\Column(type="text")
     */
    protected $cat_shortdesc;

    /**
     * @ORM\Column(type="text")
     */
    protected $cat_desc;

    /**
     * @ORM\Column(type="integer")
     */
    protected $cat_category;


    /**
     * Set cat_id
     *
     * @param integer $catId
     * @return HopeProgram
     */
    public function setCatId($catId)
    {
        $this->cat_id = $catId;

        return $this;
    }
    /**
     * Get cat_id
     *
     * @return integer 
     */
    public function getCatId()
    {
        return $this->cat_id;
    }

    /**
     * Set cat_alias
     *
     * @param string $catAlias
     * @return HopeProgram
     */
    public function setCatAlias($catAlias)
    {
        $this->cat_alias = $catAlias;

        return $this;
    }

    /**
     * Get cat_alias
     *
     * @return string 
     */
    public function getCatAlias()
    {
        return $this->cat_alias;
    }

    /**
     * Set cat_name
     *
     * @param string $catName
     * @return HopeProgram
     */
    public function setCatName($catName)
    {
        $this->cat_name = $catName;

        return $this;
    }

    /**
     * Get cat_name
     *
     * @return string 
     */
    public function getCatName()
    {
        return $this->cat_name;
    }

    /**
     * Set cat_shortdesc
     *
     * @param string $catShortdesc
     * @return HopeProgram
     */
    public function setCatShortdesc($catShortdesc)
    {
        $this->cat_shortdesc = $catShortdesc;

        return $this;
    }

    /**
     * Get cat_shortdesc
     *
     * @return string 
     */
    public function getCatShortdesc()
    {
        return $this->cat_shortdesc;
    }

    /**
     * Set cat_desc
     *
     * @param string $catDesc
     * @return HopeProgram
     */
    public function setCatDesc($catDesc)
    {
        $this->cat_desc = $catDesc;

        return $this;
    }

    /**
     * Get cat_desc
     *
     * @return string 
     */
    public function getCatDesc()
    {
        return $this->cat_desc;
    }

    /**
     * Set cat_category
     *
     * @param integer $catCategory
     * @return HopeProgram
     */
    public function setCatCategory($catCategory)
    {
        $this->cat_category = $catCategory;

        return $this;
    }

    /**
     * Get cat_category
     *
     * @return integer 
     */
    public function getCatCategory()
    {
        return $this->cat_category;
    }
}
