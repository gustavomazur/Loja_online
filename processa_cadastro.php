<?php
// Inicia a sessão. Isso é necessário para usarmos variáveis de sessão, como as de mensagens de erro ou sucesso.
session_start();

// 1. Inclui o arquivo de conexão com o banco de dados.
// O 'require_once' garante que o arquivo seja incluído apenas uma vez e para a execução se ele não for encontrado.
require_once 'conexao.php';

// 2. Verifica se os dados foram enviados via POST.
// Isso é uma medida de segurança para garantir que o script só seja acessado pelo formulário.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Recebe e limpa os dados do formulário.
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // 4. Validação básica dos dados.
    if (empty($nome) || empty($email) || empty($senha)) {
        // Se algum campo estiver vazio, guarda uma mensagem de erro na sessão e redireciona de volta.
        $_SESSION['erro'] = "Por favor, preencha todos os campos.";
        header("Location: cadastro.php");
        exit(); // Para a execução do script.
    }

    // Validação do formato do e-mail.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "Formato de e-mail inválido.";
        header("Location: cadastro.php");
        exit();
    }
    
    // Validação do tamanho da senha.
    if (strlen($senha) < 6) {
        $_SESSION['erro'] = "A senha deve ter no mínimo 6 caracteres.";
        header("Location: cadastro.php");
        exit();
    }

    // 5. Verifica se o e-mail já existe no banco (como você pediu).
    try {
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        
        // Se 'fetch()' retornar algo, significa que encontrou um usuário com aquele e-mail.
        if ($stmt->fetch()) {
            $_SESSION['erro'] = "Este e-mail já está cadastrado.";
            header("Location: cadastro.php");
            exit();
        }

        // 6. Criptografa a senha.
        // NUNCA guarde senhas em texto plano. password_hash cria um hash seguro.
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // 7. Insere o novo usuário no banco de dados.
        $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$nome, $email, $senha_hash]);

        // 8. Redireciona para a página de login com uma mensagem de sucesso.
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça o login.";
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        // Em caso de erro com o banco de dados, mostra uma mensagem genérica.
        die("Erro ao processar o cadastro: " . $e->getMessage());
    }

} else {
    // Se alguém tentar acessar o arquivo diretamente pela URL, redireciona para a página inicial.
    header("Location: index.php");
    exit();
}
?> 