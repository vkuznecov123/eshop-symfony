<?php
namespace AppBundle\Model;
class Model
{
    private  $host;
    private  $dbname;
    private  $user;
    private  $pass;
    /** @var  \PDO */
    private  $pdo;


    public function __construct($host, $dbname, $user, $pass)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->pass = $pass;
    }

    // Подключение к БД
    public function connectDB()
    {
        try {
            $pdo = new \PDO("mysql:host=$this->host ;dbname=$this->dbname;charset=utf8", $this->user, $this->pass, [\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION]);

        } catch (\PDOException $e) {
            //die('База данных недоступна 1');
            die($e->getMessage());
        }
        $this->pdo = $pdo;
    }
// Функция для получения дерева категорий из БД

    public function getTree() {

        try {

            $qres = $this->pdo -> query('SELECT * FROM Categories ORDER BY level, parentId, position;'); // Запрос категорий

        }
        catch(\PDOException $e) {
            die($e->getMessage());
        }

        $qres -> setFetchMode(\PDO::FETCH_ASSOC); // выбор формата получения результата

        $res = $qres -> fetch();
        $tree = array('name' => $res['name'],'id' => $res['id']);  // Создание массива для хранения дерева категорий


        while ($res = $qres -> fetch()) {
            if ( !$this->catTree($tree,$res) ) die('Получены некорректные данные из базы');
        }

        return $tree;
    }

    // Функция для получения товаров на главной странице из БД

    public function getMainGoods() {

        try {
            $qres = $this->pdo -> query("SELECT id, name, price, number, image, Categories_id FROM Goods  
                               INNER JOIN Main_goods ON id = Goods_id
                               ORDER BY name;");
        }
        catch(\PDOException $e) {
            die($e->getMessage());
        }

        $qres -> setFetchMode(\PDO::FETCH_ASSOC); // выбор формата получения результата

        $goods = []; // массив товаров в текущей категории

        while ($res = $qres -> fetch()) {
            $goods[] = $res;               // запись товаров из выборки в массив
        }

        return $goods;
    }


// Функция для получения товаров в категории из БД

    public function getGoods($id) {
        try {
            // делаем запрос к БД на товары в текущей категории
            $qres = $this->pdo -> query("SELECT id, name, price, number, image, Categories_id FROM Goods  
                       WHERE Categories_id = $id ORDER BY name;");
        }
        catch(\PDOException $e) {
            die($e->getMessage());
        }

        $qres -> setFetchMode(\PDO::FETCH_ASSOC); // выбор формата получения результата

        $goods = []; // массив товаров в текущей категории

        while ($res = $qres -> fetch()) {
            $goods[] = $res;               // запись товаров из выборки в массив
        }
        return $goods;
    }


// Функция для получения подробных характеристик товара из БД

    public function getGoodFull($goodId) {

        try {
            $qres = $this->pdo -> query("SELECT * FROM Goods WHERE id = $goodId;"); // выбираем нужный товар из БД
        }
        catch(\PDOException $e) {
            die($e->getMessage());
        }

        $qres -> setFetchMode(\PDO::FETCH_ASSOC); // выбор формата получения результата

        $goodFull = $qres -> fetch(); // goodFull - ассоциативный массив с характеристиками товара

        // далее запрашиваем массив с более подробными характеристиками

        try {
            $qres = $this->pdo -> query(" SELECT name, value, priority
                                FROM `eshop`.`Cats_has_Chars` AS A
                                INNER JOIN `eshop`.`Characteristics` AS B ON A.Char_id = B.id 
                                LEFT JOIN 
                                   (SELECT Char_id, value 
                                    FROM `eshop`.`Goods_has_Chars` 
                                    WHERE Good_id = $goodId) 
                                AS C ON B.id = C.Char_id
                                WHERE A.Cat_id = {$goodFull['Categories_id']}
                                ORDER BY priority DESC; "); // запрашиваем массив с характеристиками из БД
        }
        catch(\PDOException $e) {
            die($e->getMessage());
        }

        $qres -> setFetchMode(\PDO::FETCH_ASSOC); // выбор формата получения результата

        $chars=[]; // массив с характеристиками

        while ($res = $qres -> fetch()) {
            $chars[] = $res;               // запись характеристик из выборки в массив
        }

        $goodFull['chars'] = $chars; // добавление массива с дополнительными характеристиками в массив с основными

        return $goodFull;
    }

// Функция для получение пути к категории (товару) и подкатегорий

    public function getPathSubcats (array &$tree, $id, &$good = NULL) {
        $path=[]; // путь к текущей категории
        $subcats=[];  // дочерние категории для текущей

        if (isset($good)) {                // если находимся на странице с товаром
            $id = $good['Categories_id'];  // ищем путь к категории товара
        }

        $this->findPath($tree,$id,$path,$subcats); // поиск пути к текущей категории и ее дочерних категорий

        if (isset($good)) {                // если находимся на странице с товаром
            $path[] = array('name' => $good['name']);  // добавляем имя товара в путь
        }

        return array('path' => $path, 'subcats' => $subcats);

    }


// Рекурсивная функция поиска пути к заданной категории и ee дочерних категорий

    public function findPath(array &$tree, $id, array &$path, array &$subcats){
        if (in_array($id,$tree)) {                                          // элемент найден
            array_unshift($path,['name'=>$tree['name'],'id'=>$tree['id']]);   // запись в массив найденной категории
            for($i=0;$i<count($tree)-2;$i++) {
                $subcats[] = ['name'=>$tree[$i]['name'],'id'=>$tree[$i]['id']]; // запись в массив дочерних категорий для найденной
            }
            return true;
        }
        for($i=0;$i<count($tree)-2;$i++) {
            if ($this->findPath($tree[$i],$id,$path,$subcats)) {                           // элемент найден в дочерних категориях
                array_unshift($path,['name'=>$tree['name'],'id'=>$tree['id']]); // запись в массив родительских категорий
                return true;
            }
        }
        return false;
    }

// Рекурсивная функция для создания дерева категорий из выборки

    public function catTree (array &$tree, array &$res) {
        if ($res['parentId'] == $tree['id']) {                      // место для записи элемента найдено
            $tree[] = ['name' => $res['name'], 'id' => $res['id']]; // добавляем новый массив в первый свободный индекс текущего
            return true;
        }
        for($i=0;$i<count($tree)-2;$i++) {                // если текущий массив не подходит для записи - ищем в дочерних
            if ( $this->catTree($tree[$i], $res) ) return true;  // рекурсивный вызов
        }
        return false;
    }
}