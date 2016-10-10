<?php

namespace AppBundle\Entity;

use AppBundle\Model\TreeNode;
use Doctrine\ORM\EntityRepository;


class CategoryRepository extends EntityRepository
{
    private $tree;
    public function getTree() {
        /** @var $res Category[] */
        $res = $this->createQueryBuilder('c')
            ->orderBy('c.level, c.parentId, c.position')
            ->getQuery()
            ->getResult();

        $this -> tree = new TreeNode($res[0]->getId(),$res[0]->getName()); // создание корня дерева
        for($i=1;$i<count($res);$i++) {
            $this->addNode($res[$i],$this->tree);
        }
        return $this->tree;
    }

    private function addNode(Category $cat, TreeNode $treePart) {
        if ($cat->getParentId() == $treePart->getId()) {             // место для записи элемента найдено
            $treePart ->addChild(new TreeNode($cat->getId(),$cat->getName())); // добавляем новый объект к дочерним узлам
            return true;
        }
        for($i=0;$i<count($treePart->getChildren());$i++) {                // если текущий узел не подходит для записи - ищем в дочерних
            if ( $this->addNode($cat, $treePart->getChildren()[$i]) ) return true;  // рекурсивный вызов
        }
        return false;

    }

}