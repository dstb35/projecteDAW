<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 */
class Product
{

    /**
     * @var integer
     */
    private $productid;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $image;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $published = TRUE;

    /**
     * @var \AppBundle\Entity\Restaurant
     */
    private $restaurantid;

    /**
     * @var \AppBundle\Entity\Category
     */
    private $categoryid;

    /**
     * @var ArrayCollection
     */
    protected $allergens;

    public function __construct()
    {
        $this->allergens = new ArrayCollection;
    }

    /**
     * Get productid
     *
     * @return integer
     */
    public function getProductid()
    {
        return $this->productid;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
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
     * @return Product
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
     * Set published
     *
     * @param boolean $published
     *
     * @return Product
     */
    public function setpublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getpublished()
    {
        return $this->published;
    }

    /**
     * Add allergen
     *
     * @param \AppBundle\Entity\Allergen $allergen
     *
     *
     */
    public function addAllergen(\AppBundle\Entity\Allergen $allergen)
    {
        $this->allergens->add($allergen);
    }

    /**
     * Remove tag
     *
     * @param \AppBundle\Entity\Allergen
     */
    public function removeAllergen(\AppBundle\Entity\Allergen $allergen)
    {
        $this->allergens->removeElement($allergen);
    }

    function getAllergens()
    {
        return $this->allergens;
    }

    /**
     * Set categoryid
     *
     * @param \AppBundle\Entity\Category $categoryid
     *
     * @return Product
     */
    public function setCategory(\AppBundle\Entity\Category $categoryid = null)
    {
        $this->categoryid = $categoryid;

        return $this;
    }

    /**
     * Get categoryid
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->categoryid;
    }
}
