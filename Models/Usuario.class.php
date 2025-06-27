<?php
class Usuario
{
    public int    $id_usuario;
    public string $username;
    public string $email;
    public string $senha;
    public string $role;
    public function __construct() {}
    public function getId(): int       { return $this->id_usuario; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string  { return $this->role; }
}
