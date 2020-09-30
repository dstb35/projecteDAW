<?php

namespace AppBundle\Entity;

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
     * Get tableid
     *
     * @return integer
     */
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
}

