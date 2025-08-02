<?php
// ========================================
// ARQUIVO DE EXEMPLO - COPIE PARA config.php
// ========================================
// 
// 1. Copie este arquivo para config.php
// 2. Preencha com seus dados reais
// 3. NUNCA compartilhe o config.php

// E-mail do administrador/dono da loja
$admin_email = 'seu_email@exemplo.com';

// Configurações do banco de dados
$db_host = 'localhost';
$db_name = 'nome_do_seu_banco';
$db_user = 'usuario_do_banco';
$db_pass = 'senha_do_banco';

// Configurações de segurança
$session_timeout = 3600; // 1 hora em segundos

// Configurações de upload
$upload_max_size = 5242880; // 5MB em bytes
$allowed_image_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// ========================================
// NÃO ALTERE NADA ABAIXO DESTA LINHA
// ========================================

// Função para verificar se é o admin
function isAdmin($email) {
    global $admin_email;
    return $email === $admin_email;
}

// Função para obter configurações do banco
function getDbConfig() {
    global $db_host, $db_name, $db_user, $db_pass;
    return [
        'host' => $db_host,
        'dbname' => $db_name,
        'user' => $db_user,
        'pass' => $db_pass
    ];
}
?> 