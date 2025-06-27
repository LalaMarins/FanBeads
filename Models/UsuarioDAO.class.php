<?php
class UsuarioDAO
{
    public function __construct(private PDO $db) {}

    public function findByUsername(string $u): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE username = :u LIMIT 1";
        $stm = $this->db->prepare($sql);
        $stm->bindValue(':u',$u);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_CLASS,Usuario::class);
        return $stm->fetch() ?: null;
    }

    public function insert(Usuario $u): void
    {
        $sql = "
          INSERT INTO usuario(username,email,senha,role)
          VALUES(:u,:e,:s,:r)
        ";
        $stm = $this->db->prepare($sql);
        $stm->bindValue(':u',$u->username);
        $stm->bindValue(':e',$u->email);
        $stm->bindValue(':s',$u->senha);
        $stm->bindValue(':r',$u->role);
        $stm->execute();
    }
}
