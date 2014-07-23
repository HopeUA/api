<?php

namespace Hope\RestBundle\Entity\Import;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hope_video_categories")
 */
class HopeCategory {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $c_id;

     /**
      * @ORM\Column(type="string", length=255)
      */
    protected $c_name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $c_order;


    /**
     * Get c_id
     *
     * @return integer 
     */
    public function getCId()
    {
        return $this->c_id;
    }

    /**
     * Set c_name
     *
     * @param string $cName
     * @return HopeCategory
     */
    public function setCName($cName)
    {
        $this->c_name = $cName;

        return $this;
    }

    /**
     * Get c_name
     *
     * @return string 
     */
    public function getCName()
    {
        return $this->c_name;
    }

    /**
     * Set c_order
     *
     * @param integer $cOrder
     * @return HopeCategory
     */
    public function setCOrder($cOrder)
    {
        $this->c_order = $cOrder;

        return $this;
    }

    /**
     * Get c_order
     *
     * @return integer 
     */
    public function getCOrder()
    {
        return $this->c_order;
    }
}
