<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 */
class Product {

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
	 * @var \AppBundle\Entity\Restaurant
	 */
	private $restaurantid;
	
	protected $allergens;

	public function __construct() {
		$this->allergens = new ArrayCollection;
	}

	/**
	 * Get productid
	 *
	 * @return integer
	 */
	public function getProductid() {
		return $this->productid;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 *
	 * @return Product
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set image
	 *
	 * @param string $image
	 *
	 * @return Product
	 */
	public function setImage($image) {
		$this->image = $image;

		return $this;
	}

	/**
	 * Get image
	 *
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Set price
	 *
	 * @param float $price
	 *
	 * @return Product
	 */
	public function setPrice($price) {
		$this->price = $price;

		return $this;
	}

	/**
	 * Get price
	 *
	 * @return float
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Product
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set restaurantid
	 *
	 * @param \AppBundle\Entity\Restaurant $restaurantid
	 *
	 * @return Product
	 */
	public function setRestaurantid(\AppBundle\Entity\Restaurant $restaurantid = null) {
		$this->restaurantid = $restaurantid;

		return $this;
	}

	/**
	 * Get restaurantid
	 *
	 * @return \AppBundle\Entity\Restaurant
	 */
	public function getRestaurantid() {
		return $this->restaurantid;
	}
	
	public function addProductcontains (\AppBundle\Entity\Allergen $allergen) {
		$this->allergens [] = $allergen;
		
		return $this;
	}
	
	public function getProductcontains() {
		return $this->allergens;
	}

}
