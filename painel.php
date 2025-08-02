<?php
session_start();
require_once 'conexao.php';

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
    <title>Painel de Controle</title>
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
                <li class="ativo"><a href="painel.php">Dashboard</a></li>
                <li><a href="painel_produtos.php">Produtos</a></li>
                <li><a href="painel_usuarios.php">Usuários</a></li>
                <li><a href="index.php" target="_blank">Ver Loja</a></li>
            </ul>
        </nav>

        <main class="conteudo-principal">
            <h2>Dashboard</h2>
            <p>Bem-vindo ao seu painel de controle. Aqui você pode gerenciar sua loja.</p>

            <!-- Formulário de Cadastro de Produto -->
            <h3 style="margin-top:40px;">Cadastrar Novo Produto</h3>
            <?php if (isset($_GET['msg'])): ?>
                <p style="color:green;"><strong><?php echo htmlspecialchars($_GET['msg']); ?></strong></p>
            <?php endif; ?>
            <form id="form-produto" action="processa_produto.php" method="POST" enctype="multipart/form-data" style="margin-bottom:30px;">
                <label>Nome do Produto:<br>
                    <input type="text" name="nome" required style="width:300px;">
                </label><br><br>
                <label>Preço (R$):<br>
                    <input type="number" name="preco" step="0.01" required style="width:150px;">
                </label><br><br>
                <label>Categoria:<br>
                    <input type="text" name="categoria" required placeholder="Ex: camiseta, perfume, tenis" style="width:200px;">
                </label><br><br>
                <label>Imagem Principal do Produto:<br>
                    <input type="file" name="imagem" accept="image/*" required>
                    <small>Esta será a imagem principal exibida na loja</small>
                </label><br><br>
                <label>Cores (separadas por vírgula):<br>
                    <input type="text" name="cores" id="input-cores" placeholder="Ex: rosa,preto,branco" style="width:200px;">
                    <small>Deixe em branco se não tiver variações de cor</small>
                </label><br><br>
                <label>Tamanhos/Opções (separados por vírgula):<br>
                    <input type="text" name="tamanhos" placeholder="Ex: P,M,G,GG ou 38,39,40 ou 50ml,100ml">
                </label><br><br>
                <label>Estoque (quantidade disponível):<br>
                    <input type="number" name="estoque" min="0" value="0" required>
                </label><br><br>
                <div id="imagens-por-cor"></div>
                <button type="submit" name="acao" value="adicionar">Adicionar Produto</button>
            </form>
        </main>
    </div>

    <script>
    const inputCores = document.getElementById('input-cores');
    const divImagensPorCor = document.getElementById('imagens-por-cor');

    inputCores.addEventListener('input', function() {
        divImagensPorCor.innerHTML = '';
        const cores = inputCores.value.split(',').map(c => c.trim()).filter(c => c);

        if (cores.length > 0) {
            const titulo = document.createElement('h4');
            titulo.textContent = 'Imagens por Cor:';
            titulo.style.marginTop = '20px';
            titulo.style.marginBottom = '10px';
            divImagensPorCor.appendChild(titulo);
        }

        cores.forEach((cor) => {
            const container = document.createElement('div');
            container.style.border = '1px solid #ddd';
            container.style.padding = '15px';
            container.style.marginBottom = '10px';
            container.style.borderRadius = '5px';
            container.style.backgroundColor = '#f9f9f9';

            const label = document.createElement('label');
            label.innerHTML = `<strong>Imagens da cor: ${cor}</strong><br>`;
            label.style.display = 'block';
            label.style.marginBottom = '10px';

            const input = document.createElement('input');
            input.type = 'file';
            input.name = `imagens_${cor.replace(/\s+/g, '_')}[]`;
            input.accept = 'image/*';
            input.multiple = true;
            input.style.width = '100%';
            input.style.padding = '5px';

            const info = document.createElement('small');
            info.textContent = 'Selecione múltiplas imagens para esta cor (Ctrl+Click para selecionar várias)';
            info.style.color = '#666';
            info.style.display = 'block';
            info.style.marginTop = '5px';

            container.appendChild(label);
            container.appendChild(input);
            container.appendChild(info);
            divImagensPorCor.appendChild(container);
        });
    });
    </script>
</body>
</html>
