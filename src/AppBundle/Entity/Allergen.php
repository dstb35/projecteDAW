<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * Allergen
 */
class Allergen
{
    /**
     * @var integer
     */
    private $allergenid;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $name;
	
	protected $products;
	
	public function __construct() {
		$this->products = new ArrayCollection();
	}


    /**
     * Get allergenid
     *
     * @return integer
     */
    public function getAllergenid()
    {
        return $this->allergenid;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Allergen
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
     * Set name
     *
     * @param string $name
     *
     * @return Allergen
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
	
	public function getProducts(){
		return $this->products;
	}
}