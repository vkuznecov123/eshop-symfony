<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class EshopController extends Controller
{
    /**
     * @Route("/", name="eshop_main")
     */
    public function mainAction()
    {
        $model = new \AppBundle\Model\Model('127.0.0.1','eshop','eshopSymfony','pa$$w0rd');
        $model->connectDB();

        $tree = $model->getTree();
        $id = $tree['id'];
        $renderAr = [ 'currentId' => '',
                      'tree' => $tree ]; // массив данных для отправки шаблонизатору
        $renderAr['goods'] = $model->getMainGoods(); // добавляем массив товаров для главной страницы

        $pathAndSubcats = $model->getPathSubcats($tree, $id);          // добавляем путь к категории и подкатегории
        $renderAr['path'] = $pathAndSubcats['path'];           // для меню на странице
        $renderAr['subcats'] = $pathAndSubcats['subcats'];

        return $this->render('eshop/main_page.html.twig', $renderAr);

    }

    public function catAction($cat)
    {
        $model = new \AppBundle\Model\Model('127.0.0.1','eshop','eshopSymfony','pa$$w0rd');
        $model->connectDB();

        $tree = $model->getTree();
        $id = $cat;
        $renderAr = [ 'currentId' => $id,
                      'tree' => $tree ]; // массив данных для отправки шаблонизатору
        $renderAr['goods'] = $model->getGoods($id); // добавляем массив товаров из текущей категории

        $pathAndSubcats = $model->getPathSubcats($tree, $id);          // добавляем путь к категории и подкатегории
        $renderAr['path'] = $pathAndSubcats['path'];           // для меню на странице
        $renderAr['subcats'] = $pathAndSubcats['subcats'];

        return $this->render('eshop/cat_page.html.twig', $renderAr);
    }

    public function goodAction($cat,$good)
    {
        $model = new \AppBundle\Model\Model('127.0.0.1','eshop','eshopSymfony','pa$$w0rd');
        $model->connectDB();

        $tree = $model->getTree();
        $id = $cat;
        $renderAr = [ 'currentId' => $id,
                      'tree' => $tree ]; // массив данных для отправки шаблонизатору
        $renderAr['goodFull'] = $model->getGoodFull($good);    // добавляем массив с описанием товара

        $pathAndSubcats = $model->getPathSubcats($tree, $id, $renderAr['goodFull']);          // добавляем путь к категории
        $renderAr['path'] = $pathAndSubcats['path'];           // для меню на странице

        return $this->render('eshop/good_page.html.twig', $renderAr);
    }
}