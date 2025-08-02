<?php
// Inclui o arquivo de configuração seguro
require_once 'config.php';

// Obtém as configurações do banco de dados
$db_config = getDbConfig();

try {
    // Cria a conexão usando PDO com as configurações seguras
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8", 
        $db_config['user'], 
        $db_config['pass']
    );
    
    // Configura o PDO para nos avisar sobre qualquer erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Se a conexão falhar, mostra uma mensagem de erro genérica por segurança
    die("Erro ao conectar ao banco de dados. Verifique as configurações.");
}

// Se tudo deu certo, a variável $pdo estará disponível para outros arquivos
?> 