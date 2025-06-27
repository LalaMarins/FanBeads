<?php
require_once 'Models/Conexao.class.php';
require_once 'Models/UsuarioDAO.class.php';
require_once 'Models/Usuario.class.php';

class AuthController
{
    private PDO $db;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->db = Conexao::getInstancia();
    }

    // Exibe formulário de login
    public function loginForm(): void
    {
        require 'Views/login.php';
    }

    // Processa submissão de login
    public function login(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $user = trim($_POST['username'] ?? '');
        $pass = $_POST['senha']     ?? '';

        $dao = new UsuarioDAO($this->db);
        $u   = $dao->findByUsername($user);

        // usa propriedade pública senha
        if ($u && password_verify($pass, $u->senha)) {
            $_SESSION['user'] = [
                'id'       => $u->getId(),
                'username' => $u->getUsername(),
                'role'     => $u->getRole(),
            ];
            header('Location: /fanbeads/');
            exit;
        }

        $error = 'Credenciais inválidas';
        require 'Views/login.php';
    }

    // Faz logout
    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
        header('Location: /fanbeads/');
        exit;
    }

    // Exibe formulário de cadastro
    public function registerForm(): void
    {
        require 'Views/register.php';
    }

    // Processa submissão de cadastro
    public function register(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $user  = trim($_POST['username'] ?? '');
        $email = trim($_POST['email']    ?? '');
        $pass  = $_POST['senha']         ?? '';
        $hash  = password_hash($pass, PASSWORD_DEFAULT);

        $u = new Usuario();
        // atribui diretamente às propriedades públicas
        $u->username = $user;
        $u->email    = $email;
        $u->senha    = $hash;
        $u->role     = 'user';

        $dao = new UsuarioDAO($this->db);
        $dao->insert($u);

        header('Location: /fanbeads/login');
        exit;
    }
}
