<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
  * @ORM\Entity
  * @ORM\Table(name="Main_goods")
 */

class MainGoods
{
    /**
     * @ORM\Column(name="Main_id", type="integer", unique=true, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $mainId;
    /**
     * @ORM\OneToOne(targetEntity="Good")
     * @ORM\JoinColumn(name="Goods_id", referencedColumnName="id", unique=true)
     */
    protected $goods;

    /**
     * Get mainId
     *
     * @return integer 
     */
    public function getMainId()
    {
        return $this->mainId;
    }

    /**
     * Set goods
     *
     * @param \AppBundle\Entity\Good $goods
     * @return MainGoods
     */
    public function setGoods(\AppBundle\Entity\Good $goods = null)
    {
        $this->goods = $goods;

        return $this;
    }

    /**
     * Get goods
     *
     * @return \AppBundle\Entity\Good 
     */
    public function getGoods()
    {
        return $this->goods;
    }
}
