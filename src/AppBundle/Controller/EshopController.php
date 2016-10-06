<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class EshopController
{
    /**
     * @Route("/", name="eshop_main")
     */
    public function mainAction()
    {
        $tree = getTree();
        $id = $tree['id'];
        $renderAr = [ 'tree' => $tree ]; // массив данных для отправки шаблонизатору
        $renderAr['goods'] = getMainGoods(); // добавляем массив товаров для главной страницы

        $pathAndSubcats = getPathSubcats($tree, $id);          // добавляем путь к категории и подкатегории
        $renderAr['path'] = $pathAndSubcats['path'];           // для меню на странице
        $renderAr['subcats'] = $pathAndSubcats['subcats'];

        return $this->render('eshop/main_goods.html.twig', $renderAr);
    }

    public function catAction($cat)
    {
        $tree = getTree();
        $id = $cat;
        $renderAr = [ 'tree' => $tree ]; // массив данных для отправки шаблонизатору
        $renderAr['goods'] = getGoods($id); // добавляем массив товаров из текущей категории

        $pathAndSubcats = getPathSubcats($tree, $id);          // добавляем путь к категории и подкатегории
        $renderAr['path'] = $pathAndSubcats['path'];           // для меню на странице
        $renderAr['subcats'] = $pathAndSubcats['subcats'];

        return $this->render('eshop/goods.html.twig', $renderAr);
    }

    public function goodAction($cat,$good)
    {
        $tree = getTree();
        $id = $cat;
        $renderAr = [ 'tree' => $tree ]; // массив данных для отправки шаблонизатору
        $renderAr['goodFull'] = getGoodFull($good);    // добавляем массив с описанием товара

        $pathAndSubcats = getPathSubcats($tree, $id);          // добавляем путь к категории
        $renderAr['path'] = $pathAndSubcats['path'];           // для меню на странице

        return $this->render('eshop/good_full.html.twig', $renderAr);
    }
}