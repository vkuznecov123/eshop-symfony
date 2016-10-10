<?php

namespace AppBundle\Services;
use AppBundle\Entity\Good;
use Doctrine\DBAL\Driver\DrizzlePDOMySql\Connection;

class CharsGetter
{
    private $conn; // DBAL connection

    public function __construct(\Doctrine\DBAL\Connection  $conn)
    {
        $this->conn = $conn;
    }

    public function getGoodChars(Good $good) {

        $sql = "SELECT name, value, priority
                                FROM `eshop`.`Cats_has_Chars` AS A
                                INNER JOIN `eshop`.`Characteristics` AS B ON A.Char_id = B.id 
                                LEFT JOIN 
                                   (SELECT Char_id, value 
                                    FROM `eshop`.`Goods_has_Chars` 
                                    WHERE Good_id = :id)
                                    AS C ON B.id = C.Char_id
                                WHERE A.Cat_id = :catId
                                ORDER BY priority DESC;";
        $stmt = $this->conn->prepare($sql);
        echo $good->getId();
        echo $good->getCategoriesId();
        $stmt -> bindValue("id", $good->getId());
        $stmt -> bindValue("catId", $good->getCategoriesId());
        $stmt -> execute();

        $chars=[];
        while ($row = $stmt->fetch()) {
            $chars[]=$row;
        }

        return $chars;
    }
}