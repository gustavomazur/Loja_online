<?php
// Inicia a sessão para que possamos usar variáveis de sessão.
session_start();

// Inclui o arquivo de conexão para podermos falar com o banco de dados.
require_once 'conexao.php';

// Medida de segurança: só processa se os dados vieram do formulário (método POST).
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recebe o e-mail e a senha do formulário.
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Validação simples para ver se os campos não estão vazios.
    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = "E-mail e senha são obrigatórios.";
        header("Location: login.php");
        exit();
    }

    try {
        // 1. Busca no banco de dados um usuário com o e-mail fornecido.
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        
        // 'fetch()' pega o primeiro resultado da busca. Se não encontrar ninguém, retorna 'false'.
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Verifica se o usuário foi encontrado E se a senha está correta.
        // A função password_verify() compara a senha digitada com o hash salvo no banco.
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // 3. Sucesso! A senha está correta.
            // Vamos guardar as informações do usuário na sessão para usar em outras páginas.
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo']; // 'cliente' ou 'admin'
            $_SESSION['usuario_email'] = $usuario['email'];

            // Inclui o arquivo de configuração para verificar se é admin
            require_once 'config.php';

            // Redirecionamento conforme o tipo de usuário
            if (isAdmin($email)) {
                // Se for o dono, vai para o painel de administração
                header("Location: painel.php");
                exit();
            } else {
                // Se for outro usuário, vai para a loja normal
                header("Location: index.php");
                exit();
            }

        } else {
            // Falha! Usuário não encontrado ou senha incorreta.
            // Mandamos de volta para o login com uma mensagem de erro genérica por segurança.
            $_SESSION['erro'] = "E-mail ou senha inválidos.";
            header("Location: login.php");
            exit();
        }

    } catch (PDOException $e) {
        // Em caso de erro com o banco, exibe uma mensagem.
        die("Erro no login: " . $e->getMessage());
    }

} else {
    // Se alguém tentar acessar o arquivo diretamente, redireciona para a página inicial.
    header("Location: index.php");
    exit();
}

if (isset($_FILES['imagem'])) {
    if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        die('Erro real do upload: ' . $_FILES['imagem']['error']);
    }
}
?> 