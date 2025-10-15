<?php

class Usuario
{
    private int $id_usuario;
    private string $username;
    private string $email;
    private string $senha; 
    private string $role;

    // Getters
    public function getId(): int { return $this->id_usuario; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getSenha(): string { return $this->senha; }
    public function getRole(): string { return $this->role; }

    // Setters
    public function setId(int $id): void { $this->id_usuario = $id; }
    public function setUsername(string $username): void { $this->username = $username; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setSenha(string $hash): void { $this->senha = $hash; }
    public function setRole(string $role): void { $this->role = $role; }
}