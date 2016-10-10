<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;


class GoodRepository extends EntityRepository
{
    public function getMainGoods() {
        /** @var $res MainGoods[] */
        $res = $this->getEntityManager()
            ->createQuery(
                'SELECT m,g FROM AppBundle:MainGoods m
                JOIN m.goods g'
            )->getResult();
        $goods = [];
        for($i=0;$i<count($res);$i++) {
            $goods[]=$res[$i]->getGoods();
        }
        return $goods;
    }
}
