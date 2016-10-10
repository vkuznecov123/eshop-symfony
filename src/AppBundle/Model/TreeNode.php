<?php

namespace AppBundle\Model;
use AppBundle\Entity;
use Symfony\Component\Validator\Constraints\Null;

class TreeNode
{
    protected $id;
    protected $name;
    protected $children=[];

    /**
     * TreeNode constructor.
     * @param $id
     * @param $name
     * @param array $children
     */
    public function __construct($id = 0, $name = "", array $children = Null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->children = $children;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param TreeNode $child
     */
    public function addChild(TreeNode $child)
    {
        $this->children[] = $child;
    }



}