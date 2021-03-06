<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Employee
 */
class Employee
{
    /**
     * @var integer
     */
    private $employeeid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \AppBundle\Entity\Restaurant
     */
    private $restaurantid;

    protected $orders;

    public function __construct() {
        $this->orders = new ArrayCollection();
    }

    function getOrders() {
        return $this->orders;
    }

    /**
     * Get employeeid
     *
     * @return integer
     */
    public function getEmployeeid()
    {
        return $this->employeeid;
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

    /**
     * Set restaurantid
     *
     * @param \AppBundle\Entity\Restaurant $restaurantid
     *
     * @return Employee
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
}

