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

// Busca o produto para editar
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$produto) {
        header("Location: painel.php?msg=Produto não encontrado");
        exit();
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
    <title>Editar Produto - Painel Oline</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="painel.css">
    <style>
        .editar-container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-danger { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="editar-container">
        <h2>Editar Produto</h2>
        
        <?php if (isset($_GET['msg'])): ?>
            <p style="color:green;"><strong><?php echo htmlspecialchars($_GET['msg']); ?></strong></p>
        <?php endif; ?>
        
        <form action="processa_produto.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
            
            <div class="form-group">
                <label>Nome do Produto:</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Preço (R$):</label>
                <input type="number" name="preco" step="0.01" value="<?php echo $produto['preco']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Categoria:</label>
                <input type="text" name="categoria" value="<?php echo htmlspecialchars($produto['categoria']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Cores (separadas por vírgula):</label>
                <input type="text" name="cor" value="<?php echo htmlspecialchars($produto['cor'] ?? ''); ?>" placeholder="Ex: preta,branca,rosa">
            </div>
            
            <div class="form-group">
                <label>Tamanhos/Opções (separados por vírgula):</label>
                <input type="text" name="tamanhos" value="<?php echo htmlspecialchars($produto['tamanhos'] ?? ''); ?>" placeholder="Ex: P,M,G,GG ou 38,39,40 ou 50ml,100ml">
            </div>
            
            <div class="form-group">
                <label>Estoque (quantidade disponível):</label>
                <input type="number" name="estoque" min="0" value="<?php echo htmlspecialchars($produto['estoque'] ?? '0'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Nova Imagem (opcional):</label>
                <input type="file" name="imagem" accept="image/*">
                <small>Deixe em branco para manter a imagem atual</small>
            </div>
            
            <div class="form-group">
                <label>Imagem Atual:</label>
                <img src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="" style="max-width:100px;border:1px solid #ddd;">
            </div>
            
            <div class="form-group">
                <button type="submit" name="acao" value="editar" class="btn btn-primary">Salvar Alterações</button>
                <a href="painel.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html> 