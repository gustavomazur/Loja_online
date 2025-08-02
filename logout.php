<?php
// Sempre inicie a sessão antes de manipulá-la.
session_start();

// 1. Limpa todas as variáveis da sessão.
// Isso remove os dados como 'usuario_id', 'usuario_nome', etc.
$_SESSION = array();

// 2. Destrói a sessão.
// Isso remove o "carimbo" do navegador do usuário, invalidando a sessão.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// 3. Redireciona para a página de login com uma mensagem.
// (Opcional, mas dá um bom feedback para o usuário)
session_start(); // Inicia uma nova sessão SÓ para a mensagem de feedback
$_SESSION['sucesso'] = "Você saiu com segurança.";
header("Location: login.php");
exit();
?> 