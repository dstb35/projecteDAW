<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Table
 */
class Table
{
    /**
     * @var integer
     */
    private $tableid;

    /**
     * @var \AppBundle\Entity\Restaurant
     */
    private $restaurantid;
	
	/**
     * @var string
     */
    private $name;

    /**
     * Get tableid
     *
     * @return integer
     */

    protected $orders;

    public function __construct() {
        $this->orders = new ArrayCollection();
    }

    function getOrders() {
        return $this->orders;
    }

    public function getTableid()
    {
        return $this->tableid;
    }

    /**
     * Set restaurantid
     *
     * @param \AppBundle\Entity\Restaurant $restaurantid
     *
     * @return Table
     */
    public function setRestaurantid(\AppBundle\Entity\Restaurant $restaurantid = null)
    {
        $this->restaurantid = $restaurantid;

        return $this;
    }

    /**
     * Get restaurantid
     *
     * @return \AppBundle\Entity\Restaurant
     */
    public function getRestaurantid()
    {
        return $this->restaurantid;
    }
	
	/**
     * Set name
     *
     * @param string $name
     *
     * @return Employee
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

