<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se é o admin
require_once 'config.php';
if (!isAdmin($_SESSION['usuario_email'] ?? '')) {
    header("Location: index.php");
    exit();
}

// Remove o produto
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        // Busca o produto para pegar o caminho da imagem principal e imagens por cor
        $stmt = $pdo->prepare("SELECT imagem_url, imagens FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Remove o produto do banco
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        
        // Remove a imagem principal do servidor
        if ($produto && file_exists($produto['imagem_url'])) {
            unlink($produto['imagem_url']);
        }
        
        // Remove as imagens por cor do servidor
        if (!empty($produto['imagens'])) {
            $imagens_por_cor = json_decode($produto['imagens'], true);
            if (is_array($imagens_por_cor)) {
                foreach ($imagens_por_cor as $cor => $imagens) {
                    foreach ($imagens as $imagem_url) {
                        if (file_exists($imagem_url)) {
                            unlink($imagem_url);
                        }
                    }
                }
            }
        }
        
        header("Location: painel.php?msg=Produto removido com sucesso!");
        exit();
        
    } catch (PDOException $e) {
        header("Location: painel.php?msg=Erro ao remover produto: " . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: painel.php");
    exit();
}
?> 