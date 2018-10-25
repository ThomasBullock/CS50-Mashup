<?php

class QueryBuilder  // needs a PDO object
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectAll($table)
    {
        $statement = $this->pdo->prepare("select * from {$table}");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS);    
    }

    /// ADD more prepare statemnets to insert get specific table etc!!

    public function selectByGeo($table, $ne, $sw)
    {
        if($sw[1] <= $ne[1]) {
            $statement = $this->pdo->prepare("select * from {$table}
            WHERE :sw_lat <= latitude AND latitude <= :ne_lat AND (:sw_lng <= longitude AND longitude <= :ne_lng)
            ORDER BY RAND()
            LIMIT 10");
            $statement->bindParam(':sw_lat', $sw[0], PDO::PARAM_INT);
            $statement->bindParam(':ne_lat', $ne[0], PDO::PARAM_INT);
            $statement->bindParam(':sw_lng', $sw[1], PDO::PARAM_INT);
            $statement->bindParam(':ne_lng', $ne[1], PDO::PARAM_INT);                        
            $statement->execute();
        } else {
            $statement = $this->pdo->prepare("select * from {$table}
            WHERE :sw_lat <= latitude AND latitude <= :ne_lat AND (:sw_lng <= longitude OR longitude <= :ne_lng)
            ORDER BY RAND()
            LIMIT 10");
            $statement->bindParam(':sw_lat', $sw[0], PDO::PARAM_INT);
            $statement->bindParam(':ne_lat', $ne[0], PDO::PARAM_INT);
            $statement->bindParam(':sw_lng', $sw[1], PDO::PARAM_INT);
            $statement->bindParam(':ne_lng', $ne[1], PDO::PARAM_INT);                        
            $statement->execute();            
        }
        return $statement->fetchAll(PDO::FETCH_CLASS);    
    }

    public function searchByString($table, $query)
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM {$table} WHERE postal_code LIKE :n OR place_name LIKE :s OR admin_name1 LIKE :s"
        );
        $sQuery = $query . "%";
        $statement->bindParam('n', $query, PDO::PARAM_INT);
        $statement->bindParam('s', $sQuery, PDO::PARAM_STR);        

        $statement->execute(); 
        return $statement->fetchAll(PDO::FETCH_CLASS); 
    }

    public function insert($table, $parameters) // Not used
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table, 
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters)) // put a : before each parameter
        );
        try {
            $statement = $this->pdo->prepare($sql);

            $statement->execute($parameters);
        } catch (Exception $e){
            echo '<pre>' , var_dump($e) , '</pre>';      
            die('Computer says no.....');
        }

    }    

}