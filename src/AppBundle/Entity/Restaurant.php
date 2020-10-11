<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Restaurant
 */
class Restaurant implements UserInterface
{
    /**
     * @var integer
     */
    private $restaurantid;

    /**
     * @var string
     */
    private $roles;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $cif;

    /**
     * @var \DateTime
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $manager;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $paid = '0';

    /**
     * @var string
     */
    private $paiddate;

    /**
     * @var string
     */
    private $password;

    /**
     * @var integer
     */
    private $phone;
	
	protected $employees;
	protected $tables;
	protected $products;
	
	public function __construct() {
		$this->employees = new ArrayCollection();
		$this->tables = new ArrayCollection();
		$this->products = new ArrayCollection();
	}
	
	function getEmployees() {
		return $this->employees;
	}

	function getTables() {
		return $this->tables;
	}

	function getProducts() {
		return $this->products;
	}
	
    /**
     * Get restaurantid
     *
     * @return integer
     */
    public function getRestaurantid()
    {
        return $this->restaurantid;
    }

    /**
     * Set roles
     *
     * @param string $role
     *
     * @return Restaurant
     */
    public function setRoles($role)
    {
        $this->roles = $role;

        return $this;
    }

    /**
     * Get roles
     *
     * @return string
     */
    public function getRoles()
    {
        return array($this->roles);
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Restaurant
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set cif
     *
     * @param string $cif
     *
     * @return Restaurant
     */
    public function setCif($cif)
    {
        $this->cif = $cif;

        return $this;
    }

    /**
     * Get cif
     *
     * @return string
     */
    public function getCif()
    {
        return $this->cif;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Restaurant
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
     * Set email
     *
     * @param string $email
     *
     * @return Restaurant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set manager
     *
     * @param string $manager
     *
     * @return Restaurant
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return string
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Restaurant
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
     * Set paid
     *
     * @param boolean $paid
     *
     * @return Restaurant
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
     * Set paiddate
     *
     * @param string $paiddate
     *
     * @return Restaurant
     */
    public function setPaiddate($paiddate)
    {
        $this->paiddate = $paiddate;

        return $this;
    }

    /**
     * Get paiddate
     *
     * @return string
     */
    public function getPaiddate()
    {
        return $this->paiddate;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Restaurant
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Restaurant
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

	public function eraseCredentials() {
		
	}

	public function getSalt() {
		return null;
	}

	public function getUsername() {
		return $this->email;
	}
}