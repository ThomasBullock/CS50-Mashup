<?php 

class Connection
{
    // Declaring class properties or methods as static makes them accessible without needing an instantiation of the class. ie Connection::make();
    // A property declared as static cannot be accessed with an instantiated class object (though a static method can).
    public static function make($config)  
    {
        try {
            return $pdo =  new PDO(
                $config['connection'].';dbname='.$config['name'], 
                $config['username'], 
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }
}

