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
     * Processa os dados enviados pelo formulário de login (AGORA POR EMAIL).
     */
    public function login(): void
    {
        $email = trim($_POST['email'] ?? ''); // Alterado de username para email
        $password = $_POST['senha'] ?? '';

        // Busca o usuário no banco de dados POR EMAIL
        // (Já criamos o findByEmail no passo da recuperação de senha, então podemos usar aqui)
        $user = $this->usuarioDAO->findByEmail($email);

        // Verifica se o usuário existe e se a senha está correta.
        if ($user && password_verify($password, $user->getSenha())) {
            $_SESSION['user'] = [
                'id'       => $user->getId(),
                'username' => $user->getUsername(),
                'email'    => $user->getEmail(), // É bom guardar o email na sessão tb
                'role'     => $user->getRole(),
            ];
            header('Location: /fanbeads/');
            exit;
        }

        // Se falhar
        $loginError = 'E-mail ou senha inválidos.';
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
     * AGORA VERIFICA DUPLICIDADE.
     */
    public function register(): void
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['senha'] ?? '';

        // 1. Validação básica
        if (empty($username) || empty($email) || empty($password)) {
            header('Location: /fanbeads/register?error=campos_vazios');
            exit;
        }

        // 2. VERIFICAÇÃO DE DUPLICIDADE (NOVO)
        // Verifica se o e-mail já existe
        if ($this->usuarioDAO->findByEmail($email)) {
            header('Location: /fanbeads/register?error=email_existente');
            exit;
        }
        // Verifica se o nome de usuário já existe
        if ($this->usuarioDAO->findByUsername($username)) {
            header('Location: /fanbeads/register?error=usuario_existente');
            exit;
        }

        // Cria o hash da senha
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $newUser = new Usuario();
        $newUser->setUsername($username);
        $newUser->setEmail($email);
        $newUser->setSenha($hash);
        $newUser->setRole('user');

        try {
            $this->usuarioDAO->insert($newUser);
            header('Location: /fanbeads/login?success=cadastro_ok');
            exit;
        } catch (Exception $e) {
            // Caso ocorra algum outro erro de banco não previsto
            header('Location: /fanbeads/register?error=erro_banco');
            exit;
        }
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


public function forgotPasswordSubmit(): void
    {
        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Se o email for inválido, redireciona de volta.
            header('Location: /fanbeads/forgot-password?error=email_invalido');
            exit;
        }

        $user = $this->usuarioDAO->findByEmail($email);

        // Se o e-mail NÃO for encontrado, redireciona para uma página de erro.
        if (!$user) {
            header('Location: /fanbeads/forgot-password?error=email_nao_encontrado');
            exit;
        }


        // --- GERAÇÃO DO TOKEN E EXPIRAÇÃO ---
        $token = bin2hex(random_bytes(32));
        
        // Define a expiração para 1 hora a partir de agora
        $expiresAt = (new DateTime())
            ->add(new DateInterval('PT1H')) // PT1H = Período de Tempo de 1 Hora
            ->format('Y-m-d H:i:s');

        // --- SALVA O TOKEN NO BANCO ---
        try {
            $this->usuarioDAO->saveResetToken($user->getEmail(), $token, $expiresAt);
        } catch (Exception $e) {
            // Se falhar ao salvar no banco, não podemos continuar.
            error_log('Falha ao salvar token de reset: ' . $e->getMessage());
            header('Location: /fanbeads/forgot-password?error=db_error');
            exit;
        }
        
        // --- MONTAGEM DO LINK DE REDEFINIÇÃO ---
        // (Certifique-se que seu XAMPP está rodando em localhost)
        $resetLink = "http://localhost/fanbeads/reset-password?token=" . $token;

        // --- ENVIO DO EMAIL (SEM DEBUG) ---
        $mail = new PHPMailer(true);

        try {
            // Configurações do Servidor
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'almarins34@gmail.com'; // SEU EMAIL GMAIL
            $mail->Password   = 'iyrnvtrtdqmvgdtb'; // SUA SENHA DE APP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Remetente e Destinatário
            $mail->setFrom('fanbeads25@gmail.com', 'FanBeads Loja');
            $mail->addAddress($user->getEmail(), $user->getUsername());

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - FanBeads';
            $mail->Body    = "Olá " . htmlspecialchars($user->getUsername()) . ",<br><br>Recebemos uma solicitação para redefinir sua senha. Clique no link abaixo para criar uma nova senha:<br><br><a href='" . $resetLink . "'>" . $resetLink . "</a><br><br>Se você não solicitou isso, pode ignorar este e-mail.<br>Este link expira em 1 hora.<br><br>Equipe FanBeads";
            $mail->AltBody = "Olá " . htmlspecialchars($user->getUsername()) . ",\n\nLink para redefinir sua senha (expira em 1 hora): " . $resetLink . "\n\nEquipe FanBeads";
            
            $mail->send();

        } catch (Exception $e) {
            // Se o envio do email falhar, registra o erro mas NÃO informa o usuário.
            error_log("Erro no PHPMailer ao enviar reset: {$mail->ErrorInfo}");
        }

        // Redireciona para a página de sucesso
        // (Isso agora só acontece se o $user foi encontrado)
        header('Location: /fanbeads/forgot-password?success=instrucoes_enviadas');
        exit;
    }
    /**
     * (GET) Exibe o formulário de redefinição de senha.
     * Valida o token recebido pela URL.
     */
    public function resetPasswordForm(): void
    {
        $token = trim($_GET['token'] ?? '');

        if (empty($token)) {
            // Se não veio token, exibe o erro na view
            $error = "Token inválido ou não fornecido.";
            require 'Views/reset_password.php';
            exit;
        }

        // Busca o token no banco (usando o método que criamos no DAO)
        $tokenData = $this->usuarioDAO->findUserByValidToken($token);

        if (!$tokenData) {
            // Se o token não existe ou expirou, exibe o erro
            $error = "Link inválido ou expirado. Por favor, solicite um novo.";
            require 'Views/reset_password.php';
            exit;
        }

        // Se o token for válido, apenas carrega a view.
        // A própria view (reset_password.php) usará a variável $token.
        require 'Views/reset_password.php';
    }

    /**
     * (POST) Processa o formulário de redefinição de senha.
     * Valida as senhas, atualiza o usuário e apaga o token.
     */
    public function resetPasswordSubmit(): void
    {
        $token = trim($_POST['token'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $senhaConfirm = $_POST['senha_confirm'] ?? '';

        // 1. Validação do Token (redundante, mas seguro)
        $tokenData = $this->usuarioDAO->findUserByValidToken($token);
        if (!$tokenData) {
            $error = "Link inválido ou expirado. Por favor, solicite um novo.";
            require 'Views/reset_password.php';
            exit;
        }

        // 2. Validação das Senhas
        if (empty($senha) || strlen($senha) < 6) {
            $error = "A senha deve ter pelo menos 6 caracteres.";
            require 'Views/reset_password.php'; // Passa o $token de volta para a view
            exit;
        }
        if ($senha !== $senhaConfirm) {
            $error = "As senhas não coincidem.";
            require 'Views/reset_password.php'; // Passa o $token de volta para a view
            exit;
        }

        // 3. Atualização do Usuário
        try {
            // Pega o email que estava associado ao token
            $email = $tokenData['email'];
            
            // Cria o hash da nova senha
            $newHash = password_hash($senha, PASSWORD_DEFAULT);
            
            // Atualiza a senha no banco
            $this->usuarioDAO->updatePassword($email, $newHash);
            
            // Apaga o token para que não possa ser usado novamente
            $this->usuarioDAO->deleteResetToken($email);

            // 4. Exibe mensagem de sucesso
            $success = "Sua senha foi redefinida com sucesso!";
            require 'Views/reset_password.php';

        } catch (Exception $e) {
            error_log("Erro ao redefinir senha: " . $e->getMessage());
            $error = "Ocorreu um erro interno. Tente novamente.";
            require 'Views/reset_password.php'; // Passa o $token de volta para a view
        }
    }
}