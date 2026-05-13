<?php

class Database 
{   
    private static $instance = null; //хранит ед экз класса(доступно внутри этого класса)
    private $connection = null;  
 
    protected function __construct() //нельзя создать новый объект через new Database()
    {   
        $this->connection = new \PDO(
            'mysql:host=127.0.0.1;dbname=kindergarten_new;charset=utf8', // изменено с alfa на kindergarten_new
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //выброс искл при ошибках
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //возв рез в виде ассоц массива 
                PDO::ATTR_EMULATE_PREPARES => false //отключение эмуляции , т е исп настоящие подг запросы
            ]
        );
    }

    protected function __clone() {} 
    
    public function __wakeup() 
    {
        throw new \BadMethodCallException('Unable to deserialize database connection'); 
    }

    public static function getInstance(): Database
    {
        if (null === self::$instance) { 
            self::$instance = new static(); 
        }
        return self::$instance; //возв существующего (self::$instance - обращение к стат св-ву)
    }

    public static function connection(): \PDO 
    {
        return static::getInstance()->connection;
    }

    public static function prepare($statement): \PDOStatement //м подготовки запроса
    {
        return static::connection()->prepare($statement);
    }

    public static function lastInsertId(): int 
    {
        return intval(static::connection()->lastInsertId());
    }
}
?>