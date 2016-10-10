<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GoodRepository")
 * @ORM\Table(name="Goods")
 */
class Good
{
    /**
     * @ORM\Column(type="integer", unique=true, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(name="Categories_id", type="smallint", options={"unsigned"=true})
     */
    private $categoriesId;
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $price;
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $number;
    /**
     * @ORM\Column(type="string")
     */
    private $manufacturer;
    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * Get id
     *
     * @return \integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categorie_id
     *
     * @param integer $categorieId
     * @return Good
     */
    public function setCategoriesId($categoriesId)
    {
        $this->categoriesId = $categoriesId;

        return $this;
    }

    /**
     * Get categorie_id
     *
     * @return integer 
     */
    public function getCategoriesId()
    {
        return $this->categoriesId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Good
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
     * Set price
     *
     * @param \integer $price
     * @return Good
     */
    public function setPrice(\int $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return \integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set number
     *
     * @param \integer $number
     * @return Good
     */
    public function setNumber(\int $number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return \integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set manufacturer
     *
     * @param string $manufacturer
     * @return Good
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer
     *
     * @return string 
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Good
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
}
