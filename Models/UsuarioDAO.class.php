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
    /**
 * Busca um usuário pelo seu endereço de e-mail.
 * @return Usuario|null
 */
public function findByEmail(string $email): ?Usuario
{
    $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
    
    $result = $stmt->fetch();
    return $result ?: null;
}
/**
     * Salva ou atualiza um token de redefinição de senha para um usuário.
     * Usa ON DUPLICATE KEY UPDATE para garantir que haja apenas um token por email.
     *
     * @param string $email O email do usuário.
     * @param string $token O token seguro gerado.
     * @param string $expiresAt A data/hora de expiração (formato 'Y-m-d H:i:s').
     */
    public function saveResetToken(string $email, string $token, string $expiresAt): void
    {
        $sql = "INSERT INTO password_resets (email, token, expires_at)
                VALUES (:email, :token, :expires)
                ON DUPLICATE KEY UPDATE token = :token, expires_at = :expires";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expires', $expiresAt);
        $stmt->execute();
    }

    /**
     * Busca um registro de redefinição de senha com base em um token.
     * Só retorna o registro se o token existir E não estiver expirado.
     *
     * @param string $token O token vindo da URL.
     * @return array|false Retorna um array com [email, token, expires_at] ou false.
     */
    public function findUserByValidToken(string $token): array|false
    {
        $sql = "SELECT * FROM password_resets 
                WHERE token = :token AND expires_at > NOW() 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza a senha de um usuário no banco de dados.
     *
     * @param string $email O email do usuário a ser atualizado.
     * @param string $newHashedPassword A nova senha (já com hash).
     */
    public function updatePassword(string $email, string $newHashedPassword): void
    {
        $sql = "UPDATE usuario SET senha = :senha WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':senha', $newHashedPassword);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
    }

    /**
     * Exclui um token de redefinição de senha do banco.
     * Isso é feito após a senha ser redefinida com sucesso.
     *
     * @param string $email O email associado ao token.
     */
    public function deleteResetToken(string $email): void
    {
        $sql = "DELETE FROM password_resets WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
    }
}