<?php
session_start();
require_once 'conexao.php';

// Verifica se foi passado um ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<h2>Produto não encontrado.</h2>';
    exit();
}
$id = intval($_GET['id']);

// Busca o produto no banco
$stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo '<h2>Produto não encontrado.</h2>';
    exit();
}

// Imagens por cor (JSON)
$imagens_por_cor = [];
if (!empty($produto['imagens'])) {
    $imagens_por_cor = json_decode($produto['imagens'], true);
    if (!is_array($imagens_por_cor)) {
        $imagens_por_cor = [];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - Loja Oline</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="produto.css">
    <style>
        .produto-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
            display: flex;
            gap: 40px;
        }
        .produto-img-principal {
            max-width: 350px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .produto-info {
            flex: 1;
        }
        .preco {
            color: #009900;
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0 20px 0;
        }
        .cores-lista {
            margin: 20px 0;
        }
        .imagens-por-cor {
            margin-top: 30px;
        }
        .cor-titulo {
            font-weight: bold;
            margin-top: 20px;
        }
        .imagens-cor-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        .imagens-cor-grid img {
            max-width: 120px;
            border-radius: 6px;
            border: 1px solid #eee;
            background: #fafafa;
        }
    </style>
</head>
<body>
    <div class="produto-container">
        <!-- Miniaturas à esquerda -->
        <div style="display: flex; flex-direction: column; align-items: center;">
            <?php
            // Exibe as miniaturas da primeira cor como padrão
            $corPadrao = '';
            if (!empty($imagens_por_cor)) {
                $corPadrao = array_key_first($imagens_por_cor);
                foreach ($imagens_por_cor as $cor => $imagens) {
                    echo '<div class="miniaturas-por-cor" id="miniaturas-' . htmlspecialchars($cor) . '" style="display:' . ($cor === $corPadrao ? 'flex' : 'none') . '; flex-direction: column; gap: 10px;">';
                    foreach ($imagens as $img) {
                        echo '<img src="' . htmlspecialchars($img) . '" class="miniatura-img" style="width:60px;cursor:pointer;border-radius:5px;">';
                    }
                    echo '</div>';
                }
            }
            ?>
        </div>
        <!-- Imagem principal e infos -->
        <div style="flex:1; display: flex; flex-direction: column;">
            <img id="img-principal" src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" style="width:320px; border-radius:10px; margin-bottom:20px;">
            <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
            <div class="preco" style="color:green;font-size:2rem;">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
            <div><strong>Categoria:</strong> <?php echo htmlspecialchars($produto['categoria']); ?></div>
            <div style="margin:10px 0;">
                <strong>Cor:</strong>
                <?php
                $cores = explode(',', $produto['cor']);
                foreach ($cores as $i => $cor) {
                    $cor = trim($cor);
                    echo '<button type="button" class="btn-cor' . ($i === 0 ? ' ativo' : '') . '" data-cor="' . htmlspecialchars($cor) . '" style="margin-right:5px;">' . htmlspecialchars(ucfirst($cor)) . '</button>';
                }
                ?>
            </div>
            <div style="margin:10px 0;">
                <strong>Tamanho:</strong>
                <?php
                $tamanhos = explode(',', $produto['tamanhos'] ?? '');
                foreach ($tamanhos as $i => $tamanho) {
                    $tamanho = trim($tamanho);
                    if ($tamanho) {
                        echo '<button type="button" class="btn-tamanho' . ($i === 0 ? ' ativo' : '') . '" style="margin-right:5px;">' . htmlspecialchars(strtoupper($tamanho)) . '</button>';
                    }
                }
                ?>
            </div>
            <div style="margin:10px 0;">
                <strong>Estoque:</strong> <span id="estoque-produto"><?php echo (int)$produto['estoque']; ?></span>
            </div>
            <div style="margin:20px 0;">
                <button type="button" id="btn-adicionar-carrinho" style="padding:10px 20px; background:#222; color:#fff; border:none; border-radius:5px; font-size:1rem; margin-right:10px;">ADICIONAR AO CARRINHO</button>
                <button style="padding:10px 20px; background:#009900; color:#fff; border:none; border-radius:5px; font-size:1rem;">COMPRAR AGORA</button>
            </div>
        </div>
    </div>
    <script src="galeria-produto.js"></script>
    <!-- Carrinho global -->
    <div id="icone-carrinho" style="position:fixed;top:20px;right:40px;z-index:1000;cursor:pointer;background:#fff;padding:8px 18px;border-radius:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);font-weight:bold;">
      🛒 <span id="contador-carrinho">0</span>
    </div>
    <section id="carrinho" style="display:none;background:#fff;padding:30px;max-width:500px;margin:40px auto;border-radius:12px;box-shadow:0 0 25px rgba(0,0,0,0.2);font-size:16px;position:fixed;top:80px;right:40px;z-index:1001;">
      <h2>🛒 Carrinho de Compras</h2>
      <ul id="lista-carrinho"></ul>
      <p id="total-carrinho"><strong>Total:</strong> R$ 0,00</p>
      <button id="botao-finalizar">Finalizar Compra</button>
      <button id="botao-voltar">Voltar</button>
    </section>
    <script src="script.js"></script>
    <script>
      // Abrir/Fechar carrinho
      document.getElementById('icone-carrinho').addEventListener('click', function() {
        const carrinho = document.getElementById('carrinho');
        carrinho.style.display = carrinho.style.display === 'block' ? 'none' : 'block';
      });
      document.getElementById('botao-voltar').addEventListener('click', function() {
        document.getElementById('carrinho').style.display = 'none';
      });
    </script>
</body>
</html> 