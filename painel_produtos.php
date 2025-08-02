<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
$nome_usuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="painel.css">
</head>
<body>
    <header id="barra-topo-painel">
        <div class="logo-painel">
            <h1>Painel Oline</h1>
        </div>
        <div class="usuario-info">
            <span>Olá, <strong><?php echo htmlspecialchars($nome_usuario); ?></strong>!</span>
            <a href="logout.php" class="botao-sair">Sair</a>
        </div>
    </header>

    <div class="container-painel">
        <nav class="menu-lateral">
            <ul>
                <li><a href="painel.php">Dashboard</a></li>
                <li class="ativo"><a href="painel_produtos.php">Produtos</a></li>
                <li><a href="painel_usuarios.php">Usuários</a></li>
                <li><a href="index.php" target="_blank">Ver Loja</a></li>
            </ul>
        </nav>

        <main class="conteudo-principal">
            <h2>Gerenciar Produtos</h2>

            <?php
            require_once 'conexao.php';
            $produtos = $pdo->query('SELECT * FROM produtos')->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <h3 style="margin-top:20px;">Produtos Cadastrados</h3>
            <table border="1" cellpadding="8" cellspacing="0" style="width:100%;margin-top:10px;">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Cores</th>
                    <th>Imagem Principal</th>
                    <th>Imagens por Cor</th>
                    <th>Tamanhos</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?php echo $produto['id']; ?></td>
                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                    <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                    <td><?php echo htmlspecialchars($produto['cor'] ?? ''); ?></td>
                    <td><img src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="" style="max-width:60px;"></td>
                    <td>
                        <?php
                        $imagens_por_cor = [];
                        if (!empty($produto['imagens'])) {
                            $imagens_por_cor = json_decode($produto['imagens'], true);
                        }
                        $total_imagens = 0;
                        if (is_array($imagens_por_cor)) {
                            foreach ($imagens_por_cor as $cor => $imagens) {
                                $total_imagens += count($imagens);
                            }
                        }
                        if ($total_imagens > 0) {
                            echo "<a href='ver_imagens_produto.php?id={$produto['id']}' style='color:blue;text-decoration:none;'>Ver {$total_imagens} imagens</a>";
                        } else {
                            echo "Nenhuma";
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($produto['tamanhos'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($produto['estoque'] ?? '0'); ?></td>
                    <td>
                        <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" style="background:#007bff;color:white;padding:5px 10px;text-decoration:none;border-radius:3px;margin-right:5px;">Editar</a>
                        <a href="remover_produto.php?id=<?php echo $produto['id']; ?>" onclick="return confirm('Tem certeza que quer remover este produto?')" style="background:#dc3545;color:white;padding:5px 10px;text-decoration:none;border-radius:3px;">Remover</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </main>
    </div>
</body>
</html>
