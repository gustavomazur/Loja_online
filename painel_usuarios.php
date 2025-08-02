<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se é o admin - busca o e-mail do usuário logado
$stmt = $pdo->prepare("SELECT email FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario_atual = $stmt->fetch(PDO::FETCH_ASSOC);

require_once 'config.php';
if (!isAdmin($usuario_atual['email'])) {
    header("Location: index.php");
    exit();
}

// Busca todos os usuários
$usuarios = $pdo->query('SELECT id, nome, email FROM usuarios ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);

// Se clicou em um usuário específico, busca histórico de compras
$usuario_selecionado = null;
$historico_compras = [];
if (isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT nome, email FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);
    $usuario_selecionado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario_selecionado) {
        // Busca histórico de compras (você pode adaptar conforme sua estrutura de pedidos)
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC");
        $stmt->execute([$id_usuario]);
        $historico_compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Painel Oline</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="painel.css">
    <style>
        .usuarios-container {
            padding: 20px;
        }
        .lista-usuarios {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .usuario-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .usuario-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .usuario-card.ativo {
            background: #e3f2fd;
            border: 2px solid #2196f3;
        }
        .usuario-nome {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .usuario-email {
            color: #666;
            font-size: 14px;
        }
        .usuario-data {
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }
        .historico-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .historico-titulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        .sem-compras {
            color: #666;
            font-style: italic;
        }
        .voltar-btn {
            background: #6c757d;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <header id="barra-topo-painel">
        <div class="logo-painel">
            <h1>Painel Oline</h1>
        </div>
        <div class="usuario-info">
            <span>Olá, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!</span>
            <a href="logout.php" class="botao-sair">Sair</a>
        </div>
    </header>

    <div class="container-painel">
        <nav class="menu-lateral">
            <ul>
                <li><a href="painel.php">Dashboard</a></li>
                <li><a href="painel_produtos.php">Produtos</a></li>
                <li class="ativo"><a href="painel_usuarios.php">Usuários</a></li>
                <li><a href="index.php" target="_blank">Ver Loja</a></li>
            </ul>
        </nav>

        <main class="conteudo-principal">
            <div class="usuarios-container">
                <h2>Usuários Cadastrados</h2>
                
                <?php if ($usuario_selecionado): ?>
                    <!-- Mostra histórico de compras do usuário selecionado -->
                    <a href="painel_usuarios.php" class="voltar-btn">← Voltar para lista</a>
                    
                    <div class="historico-container">
                        <div class="historico-titulo">
                            Histórico de Compras: <?php echo htmlspecialchars($usuario_selecionado['nome']); ?>
                        </div>
                        
                        <?php if (empty($historico_compras)): ?>
                            <p class="sem-compras">Este cliente ainda não fez nenhuma compra.</p>
                        <?php else: ?>
                            <table border="1" cellpadding="8" cellspacing="0" style="width:100%;">
                                <tr>
                                    <th>Data</th>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                                <?php foreach ($historico_compras as $compra): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($compra['data_pedido'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($compra['produto_nome'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($compra['quantidade'] ?? 'N/A'); ?></td>
                                    <td>R$ <?php echo number_format($compra['valor'] ?? 0, 2, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($compra['status'] ?? 'N/A'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </div>
                    
                <?php else: ?>
                    <!-- Lista de usuários -->
                    <div class="lista-usuarios">
                        <?php foreach ($usuarios as $usuario): ?>
                        <div class="usuario-card" onclick="window.location.href='painel_usuarios.php?id=<?php echo $usuario['id']; ?>'">
                            <div class="usuario-nome"><?php echo htmlspecialchars($usuario['nome']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <p><strong>Total de usuários:</strong> <?php echo count($usuarios); ?></p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html> 