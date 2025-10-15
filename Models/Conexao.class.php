<?php
/**
 * Gerencia a conexão com o banco de dados usando o padrão Singleton.
 *
 * O padrão Singleton garante que exista apenas uma única instância (objeto)
 * desta classe em toda a aplicação. Isso é ideal para conexões de banco de
 * dados, pois evita o desperdício de recursos ao abrir múltiplas conexões.
 */
class Conexao
{
    /** @var ?PDO A única instância da conexão PDO. */
    private static ?PDO $instance = null;

    /**
     * O construtor é privado para impedir a criação de novas instâncias
     * com o operador 'new Conexao()'.
     */
    private function __construct() {}

    /**
     * Método estático que retorna a única instância da conexão.
     * Se a instância ainda não existir, ela é criada na primeira chamada.
     */
    public static function getInstancia(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=localhost;dbname=fanbeads;charset=utf8mb4';
            $user = 'root';
            $pass = '';

            self::$instance = new PDO($dsn, $user, $pass);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}