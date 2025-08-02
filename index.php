<?php
session_start();
require_once 'conexao.php';
$produtos = $pdo->query('SELECT * FROM produtos')->fetchAll(PDO::FETCH_ASSOC);
$usuario_logado = isset($_SESSION['usuario_id']);
$usuario_nome = $usuario_logado ? $_SESSION['usuario_nome'] : '';
$categorias = $pdo->query('SELECT DISTINCT categoria FROM produtos')->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Loja Oline</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <!-- 🔲 BARRA SUPERIOR PRETA -->
  <header id="barra-topo">
    <div class="infos-usuario">
      <?php if ($usuario_logado): ?>
        <span>Bem-vindo, <strong><?php echo htmlspecialchars($usuario_nome); ?></strong> | <a href="logout.php">Sair</a></span>
      <?php else: ?>
        <span>Bem-vindo, <a href="login.php">faça login</a> ou <a href="cadastro.php">cadastre-se</a></span>
      <?php endif; ?>
    </div>

    <div class="acoes-usuario">
      <a href="#">Meus pedidos</a>
      <a href="#">Minha conta</a>

      <div class="busca-topo">
        <input type="text" id="campoBusca" placeholder="Buscar produto..." />
      </div>

      <div id="icone-carrinho">
        🛒 <span id="contador-carrinho">0</span>
      </div>
      <div class="suporte-topo">
        <a href="https://wa.me/5511999999999?text=Olá!%20Preciso%20de%20ajuda%20com%20meu%20pedido." target="_blank">
          <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" />
          <span>Fale com suporte no WhatsApp</span>
        </a>
      </div>
    </div>
  </header>

  <!-- 🔳 CONTEÚDO PRINCIPAL -->
  <div id="container">
    <!-- TOPO VISUAL (logo etc) -->
    <div id="topo">
      <div id="profile">
        <img src="imagens/logo.png" alt="Logo da loja" />
      </div>
    </div>

    <!-- PRODUTOS -->
    <section id="produtos">
      <h2>Produtos em destaque</h2>

      <div class="filtros-categorias">
        <button class="filtro" data-categoria="todos">Todos</button>
        <?php foreach ($categorias as $cat): ?>
          <button class="filtro" data-categoria="<?= htmlspecialchars($cat) ?>">
            <?= ucfirst(htmlspecialchars($cat)) ?>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="grande-produtos">
        <?php foreach ($produtos as $produto): ?>
          <div class="produto" data-categoria="<?php echo htmlspecialchars($produto['categoria']); ?>">
            <a href="produto.php?id=<?php echo $produto['id']; ?>">
              <img src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" />
            </a>
            <p class="nome"><?php echo htmlspecialchars($produto['nome']); ?></p>
            <p class="preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            <div class="botoes-produto">
              <button class="botao-comprar" data-nome="<?php echo htmlspecialchars($produto['nome']); ?>" data-preco="<?php echo htmlspecialchars($produto['preco']); ?>">Comprar</button>
              <button class="botao-adicionar-carrinho" data-nome="<?php echo htmlspecialchars($produto['nome']); ?>" data-preco="<?php echo htmlspecialchars($produto['preco']); ?>">🛒</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- SUPORTE WHATSAPP -->
    <!-- RASTREIO DE PEDIDO -->
    <!-- SIMULADOR DE FRETE E PAGAMENTO -->

    <!-- MENSAGEM DE ERRO DE BUSCA -->
    <p id="mensagemNaoEncontrado" style="display: none; color: red; text-align: center; font-weight: bold; margin-top: 20px;">
      Nenhum produto encontrado.
    </p>
  </div> <!-- fim do #container -->

  <!-- ✅ CARRINHO FORA do topo e do container -->
  <section id="carrinho">
    <h2>🛒 Carrinho de Compras</h2>
    <ul id="lista-carrinho"></ul>
    <p id="total-carrinho"><strong>Total:</strong> R$ 0,00</p>
    <button id="botao-finalizar">Finalizar Compra</button>
    <button id="botao-voltar">Voltar</button>
  </section>

  <!-- SCRIPT -->
  <script src="script.js"></script>
  <script>
    var usuarioLogado = <?php echo $usuario_logado ? 'true' : 'false'; ?>;
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.botao-comprar, .botao-adicionar-carrinho').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          if (!usuarioLogado) {
            e.preventDefault();
            alert('Você precisa fazer login para comprar ou adicionar ao carrinho!');
            window.location.href = 'login.php';
          }
        });
      });
    });

    document.querySelectorAll('.filtro').forEach(function(botao) {
      botao.addEventListener('click', function() {
        document.querySelectorAll('.filtro').forEach(b => b.classList.remove('ativo'));
        this.classList.add('ativo');
        const categoria = this.getAttribute('data-categoria');
        document.querySelectorAll('.produto').forEach(prod => {
          if (categoria === 'todos' || prod.getAttribute('data-categoria').toLowerCase() === categoria.toLowerCase()) {
            prod.style.display = '';
          } else {
            prod.style.display = 'none';
          }
        });
        document.getElementById('mensagemNaoEncontrado').style.display =
          document.querySelectorAll('.produto:not([style*=\"display: none\"])').length === 0 ? 'block' : 'none';
      });
    });

    document.getElementById('campoBusca').addEventListener('input', function() {
      var termo = this.value.toLowerCase();
      var algumMostrado = false;
      document.querySelectorAll('.produto').forEach(function(prod) {
        var nome = prod.querySelector('.nome').textContent.toLowerCase();
        var categoria = prod.getAttribute('data-categoria').toLowerCase();
        if (nome.includes(termo) || categoria.includes(termo)) {
          prod.style.display = '';
          algumMostrado = true;
        } else {
          prod.style.display = 'none';
        }
      });
      document.getElementById('mensagemNaoEncontrado').style.display = algumMostrado ? 'none' : 'block';
    });
  </script>
</body>
</html>
