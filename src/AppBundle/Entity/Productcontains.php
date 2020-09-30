<?php

namespace AppBundle\Entity;

/**
 * Productcontains
 */
class Productcontains
{
    /**
     * @var string
     */
    private $productcontainsid;

    /**
     * @var \AppBundle\Entity\Allergen
     */
    private $allergenid;

    /**
     * @var \AppBundle\Entity\Product
     */
    private $productid;


    /**
     * Get productcontainsid
     *
     * @return string
     */
    public function getProductcontainsid()
    {
        return $this->productcontainsid;
    }

    /**
     * Set allergenid
     *
     * @param \AppBundle\Entity\Allergen $allergenid
     *
     * @return Productcontains
     */
    public function setAllergenid(\AppBundle\Entity\Allergen $allergenid = null)
    {
        $this->allergenid = $allergenid;

        return $this;
    }

    /**
     * Get allergenid
     *
     * @return \AppBundle\Entity\Allergen
     */
    public function getAllergenid()
    {
        return $this->allergenid;
    }

    /**
     * Set productid
     *
     * @param \AppBundle\Entity\Product $productid
     *
     * @return Productcontains
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

