<?php
class Conexao
{
    private static ?PDO $instance = null;

    public static function getInstancia(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new PDO(
              'mysql:host=localhost;dbname=fanbeads;charset=utf8mb4',
              'root',''
            );
            self::$instance->setAttribute(
              PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
            );
        }
        return self::$instance;
    }
}
