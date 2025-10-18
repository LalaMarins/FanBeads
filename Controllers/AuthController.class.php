<?php
class AuthController
{
    private UsuarioDAO $usuarioDAO;

    public function __construct()
    {
        // Garante que a sessão seja iniciada sempre que o controller for usado.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        // O autoloader já carrega o UsuarioDAO, então podemos instanciá-lo aqui.
        // O DAO agora é uma propriedade do controller, evitando recriá-lo em cada método.
        $this->usuarioDAO = new UsuarioDAO(Conexao::getInstancia());
    }

    /**
     * Exibe o formulário de login.
     */
    public function loginForm(): void
    {
        require 'Views/login.php';
    }

    /**
     * Processa os dados enviados pelo formulário de login.
     */
    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['senha'] ?? '';

        // Busca o usuário no banco de dados.
        $user = $this->usuarioDAO->findByUsername($username);

        // Verifica se o usuário existe e se a senha está correta.
        // Usa o getter getSenha() para acessar a propriedade privada (encapsulamento).
        if ($user && password_verify($password, $user->getSenha())) {
            // Se as credenciais estiverem corretas, armazena os dados do usuário na sessão.
            $_SESSION['user'] = [
                'id'       => $user->getId(),
                'username' => $user->getUsername(),
                'role'     => $user->getRole(),
            ];
            header('Location: /fanbeads/');
            exit;
        }

        // Se o login falhar, exibe novamente o formulário com uma mensagem de erro.
        $loginError = 'Usuário ou senha inválidos.';
        require 'Views/login.php';
    }

    /**
     * Exibe o formulário de cadastro.
     * Renomeado de 'cadastrarForm' para 'registerForm' para consistência.
     */
    public function registerForm(): void
    {
        require 'Views/register.php';
    }

    /**
     * Processa os dados enviados pelo formulário de cadastro.
     * Renomeado de 'cadastrar' para 'register' para consistência.
     */
    public function register(): void
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['senha'] ?? '';

        // Validação básica (em um projeto real, a validação seria mais complexa).
        if (empty($username) || empty($email) || empty($password)) {
            // Lógica de erro: redirecionar de volta com uma mensagem.
            header('Location: /fanbeads/register?error=campos_vazios');
            exit;
        }

        // Cria o hash da senha de forma segura.
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Cria um novo objeto Usuario e popula com os setters (encapsulamento).
        $newUser = new Usuario();
        $newUser->setUsername($username);
        $newUser->setEmail($email);
        $newUser->setSenha($hash);
        $newUser->setRole('user'); // Define o papel padrão para novos usuários.

        // Insere o novo usuário no banco de dados.
        $this->usuarioDAO->insert($newUser);

        // Redireciona para a página de login para que o novo usuário possa entrar.
        header('Location: /fanbeads/login?success=cadastro_ok');
        exit;
    }

    /**
     * Encerra a sessão do usuário (faz o logout).
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: /fanbeads/');
        exit;
    }
    /**
 * Exibe o formulário para solicitar a recuperação de senha.
 */
public function forgotPasswordForm(): void
{
    // Simplesmente carrega a view que criaremos a seguir.
    require 'Views/forgot_password.php';
}
}