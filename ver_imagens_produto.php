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

// Busca o produto e suas imagens
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Busca dados do produto
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$produto) {
        header("Location: painel.php?msg=Produto não encontrado");
        exit();
    }
    
    // Busca imagens por cor do campo 'imagens' (JSON)
    $imagens_por_cor = [];
    if (!empty($produto['imagens'])) {
        $imagens_por_cor = json_decode($produto['imagens'], true);
        if (!is_array($imagens_por_cor)) {
            $imagens_por_cor = [];
        }
    }
    
} else {
    header("Location: painel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagens do Produto - <?php echo htmlspecialchars($produto['nome']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="painel.css">
    <style>
        .container-imagens {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .produto-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .cor-section {
            margin-bottom: 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .cor-header {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .imagens-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
        }
        .imagem-item {
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: #f9f9f9;
        }
        .imagem-item img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .imagem-item .nome-arquivo {
            font-size: 12px;
            color: #666;
            word-break: break-all;
        }
        .btn-voltar {
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .btn-voltar:hover {
            background: #5a6268;
        }
        .sem-imagens {
            padding: 20px;
            text-align: center;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container-imagens">
        <a href="painel.php" class="btn-voltar">← Voltar ao Painel</a>
        
        <div class="produto-info">
            <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
            <p><strong>Preço:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            <p><strong>Categoria:</strong> <?php echo htmlspecialchars($produto['categoria']); ?></p>
            <p><strong>Cores:</strong> <?php echo htmlspecialchars($produto['cor'] ?? 'N/A'); ?></p>
        </div>
        
        <h3>Imagem Principal</h3>
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="Imagem Principal" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        </div>
        
        <?php if (!empty($imagens_por_cor)): ?>
            <h3>Imagens por Cor</h3>
            <?php foreach ($imagens_por_cor as $cor => $imagens_cor): ?>
                <div class="cor-section">
                    <div class="cor-header">
                        Cor: <?php echo htmlspecialchars($cor); ?> (<?php echo count($imagens_cor); ?> imagens)
                    </div>
                    <div class="imagens-grid">
                        <?php foreach ($imagens_cor as $imagem_url): ?>
                            <div class="imagem-item">
                                <img src="<?php echo htmlspecialchars($imagem_url); ?>" alt="Imagem da cor <?php echo htmlspecialchars($cor); ?>">
                                <div class="nome-arquivo"><?php echo basename($imagem_url); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="sem-imagens">
                <h3>Imagens por Cor</h3>
                <p>Este produto não possui imagens adicionais por cor.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 