<?php
class UsuarioDAO
{
    public function __construct(private PDO $db) {}

    /**
     * Busca um usuário pelo seu nome de usuário.
     * @return Usuario|null
     */
    public function findByUsername(string $username): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE username = :u LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':u', $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Insere um novo usuário no banco de dados.
     */
    public function insert(Usuario $u): void
    {
        $sql = "INSERT INTO usuario (username, email, senha, role)
                VALUES (:u, :e, :s, :r)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':u', $u->getUsername());
        $stmt->bindValue(':e', $u->getEmail());
        $stmt->bindValue(':s', $u->getSenha());
        $stmt->bindValue(':r', $u->getRole());
        $stmt->execute();
    }
}