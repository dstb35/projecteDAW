<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * category
 */
class Category {

	/**
	 * @var integer
	 */
	private $categoryid;
	
	/**
	 * @var string
	 */
	private $name;

	/*
	 * @var ArrayCollection
	 */
	protected $products;

	public function __construct() {
		$this->products = new ArrayCollection();
	}

	public function __toString() {
		return $this->name;
	}

	/**
	 * Get categoryid
	 *
	 * @return integer
	 */
	public function getCategoryid() {
		return $this->categoryid;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return category
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
	 * Add product
	 *
	 * @param \AppBundle\Entity\Product $product
	 *
	 * @return category
	 */
	public function addProduct(\AppBundle\Entity\Product $product) {
		$product->setCategoryid($this);
		$this->products[] = $product;
		return $this;
	}

	public function removeProduct(\AppBundle\Entity\Product $product) {
		$this->products->removeElement($product);
	}

	/**
	 * Get products
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getProducts() {
		return $this->products;
	}

}
