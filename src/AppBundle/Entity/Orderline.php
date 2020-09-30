<?php

namespace AppBundle\Entity;

/**
 * Orderline
 */
class Orderline
{
    /**
     * @var integer
     */
    private $orderlineid;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var float
     */
    private $subtotal;

    /**
     * @var \AppBundle\Entity\Order
     */
    private $orderid;

    /**
     * @var \AppBundle\Entity\Product
     */
    private $productid;


    /**
     * Get orderlineid
     *
     * @return integer
     */
    public function getOrderlineid()
    {
        return $this->orderlineid;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Orderline
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return Orderline
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set orderid
     *
     * @param \AppBundle\Entity\Order $orderid
     *
     * @return Orderline
     */
    public function setOrderid(\AppBundle\Entity\Order $orderid = null)
    {
        $this->orderid = $orderid;

        return $this;
    }

    /**
     * Get orderid
     *
     * @return \AppBundle\Entity\Order
     */
    public function getOrderid()
    {
        return $this->orderid;
    }

    /**
     * Set productid
     *
     * @param \AppBundle\Entity\Product $productid
     *
     * @return Orderline
     */
    public function setProductid(\AppBundle\Entity\Product $productid = null)
    {
        $this->productid = $productid;

        return $this;
    }

    /**
     * Get productid
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProductid()
    {
        return $this->productid;
    }
}

