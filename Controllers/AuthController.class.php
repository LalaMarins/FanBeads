<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
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
// Dentro da classe AuthController

public function forgotPasswordSubmit(): void
{
    $email = trim($_POST['email'] ?? '');

    // Habilita o debug ANTES de qualquer validação para garantir que vemos algo
    $mail = new PHPMailer(true); 
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // GARANTA QUE ESTÁ DESCOMENTADO
    echo "DEBUG: Modo de debug do PHPMailer ATIVADO.<br><hr>"; // Output inicial para teste

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Se email inválido, mostra erro e para
        echo "ERRO: Email inválido fornecido: " . htmlspecialchars($email);
        exit; 
        // header('Location: /fanbeads/forgot-password?error=email_invalido');
        // exit;
    }

    $user = $this->usuarioDAO->findByEmail($email);

    if ($user) {
        echo "DEBUG: Usuário encontrado para o email: " . htmlspecialchars($email) . "<br><hr>";
        
        $token = bin2hex(random_bytes(32)); 
        $resetLink = "http://localhost/fanbeads/reset-password?token=" . $token;

        try {
            echo "DEBUG: Configurando PHPMailer...<br>";
            // Configurações do Servidor
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'almarins34@gmail.com'; 
            $mail->Password   = 'iyrnvtrtdqmvgdtb'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS
$mail->Port       = 587; // Mude a porta para 587
            $mail->CharSet    = 'UTF-8';

            // Remetente e Destinatário
            $mail->setFrom('fanbeads25@gmail.com', 'FanBeads Loja');
            $mail->addAddress($user->getEmail(), $user->getUsername());

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - FanBeads';
            $mail->Body    = "Olá " . htmlspecialchars($user->getUsername()) . ",<br><br>Link de reset: <a href='" . $resetLink . "'>" . $resetLink . "</a><br><br>Equipe FanBeads";
            $mail->AltBody = "Olá " . htmlspecialchars($user->getUsername()) . ",\n\nLink de reset: " . $resetLink . "\n\nEquipe FanBeads";

            echo "DEBUG: Tentando enviar o email... (Debug do SMTP deve aparecer abaixo)<br><hr>";
            
            $mail->send(); // Tenta enviar
            
            // Se chegou aqui, o send() não lançou exceção
            echo "<hr>DEBUG: PHPMailer->send() EXECUTADO SEM EXCEÇÃO. Verifique sua caixa de entrada e o log de debug acima.";
            exit; // PARA A EXECUÇÃO AQUI para vermos o log

        } catch (Exception $e) {
            // Se o send() lançou uma exceção, paramos aqui e mostramos o erro
            echo "<hr>ERRO CAPTURADO PELO CATCH: {$mail->ErrorInfo}<br>Mensagem da Exceção: {$e->getMessage()}";
            error_log("Erro ao enviar email de reset: {$mail->ErrorInfo}");
            exit; // PARA A EXECUÇÃO AQUI
        }

    } else {
        // Email não encontrado
        echo "DEBUG: Email não encontrado no banco: " . htmlspecialchars($email);
        // header('Location: /fanbeads/forgot-password?success=instrucoes_enviadas'); // Redirecionamento original comentado
        exit; // PARA A EXECUÇÃO AQUI
    }

    // O redirecionamento original foi comentado/removido para este teste
    // header('Location: /fanbeads/forgot-password?success=instrucoes_enviadas');
    // exit;
}
}