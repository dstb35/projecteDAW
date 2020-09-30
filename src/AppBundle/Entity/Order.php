<?php

namespace AppBundle\Entity;

/**
 * Order
 */
class Order
{
    /**
     * @var integer
     */
    private $orderid;

    /**
     * @var \DateTime
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     */
    private $paid = '0';

    /**
     * @var float
     */
    private $total = '0';

    /**
     * @var \AppBundle\Entity\Employee
     */
    private $employeeid;

    /**
     * @var \AppBundle\Entity\Table
     */
    private $tableid;


    /**
     * Get orderid
     *
     * @return integer
     */
    public function getOrderid()
    {
        return $this->orderid;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Order
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return Order
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Order
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set employeeid
     *
     * @param \AppBundle\Entity\Employee $employeeid
     *
     * @return Order
     */
    public function setEmployeeid(\AppBundle\Entity\Employee $employeeid = null)
    {
        $this->employeeid = $employeeid;

        return $this;
    }

    /**
     * Get employeeid
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getEmployeeid()
    {
        return $this->employeeid;
    }

    /**
     * Set tableid
     *
     * @param \AppBundle\Entity\Table $tableid
     *
     * @return Order
     */
    public function setTableid(\AppBundle\Entity\Table $tableid = null)
    {
        $this->tableid = $tableid;

        return $this;
    }

    /**
     * Get tableid
     *
     * @return \AppBundle\Entity\Table
     */
    public function getTableid()
    {
        return $this->tableid;
    }
}

