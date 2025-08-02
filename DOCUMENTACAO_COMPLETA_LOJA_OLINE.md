# 📚 DOCUMENTAÇÃO COMPLETA - LOJA ONLINE
## Guia Completo para Revisão e Estudo

---

## 📋 ÍNDICE
1. [Visão Geral do Projeto](#visão-geral)
2. [Estrutura de Arquivos](#estrutura)
3. [Páginas Principais](#páginas-principais)
4. [Sistema de Login e Cadastro](#sistema-login)
5. [Banco de Dados](#banco-dados)
6. [Funcionalidades JavaScript](#javascript)
7. [Estilos CSS](#css)
8. [Ciclo Completo do Sistema](#ciclo-completo)
9. [Exemplos Práticos](#exemplos)

---

## 🎯 VISÃO GERAL DO PROJETO

**O que é?** Uma loja online completa com sistema de cadastro, login, carrinho de compras, produtos, comentários e painel administrativo.

**Tecnologias usadas:**
- **HTML**: Estrutura das páginas
- **CSS**: Visual e responsividade
- **JavaScript**: Interatividade e funcionalidades
- **PHP**: Backend e conexão com banco
- **MySQL**: Banco de dados

---

## 📁 ESTRUTURA DE ARQUIVOS

```
loja.oline/
├── 📄 index.html          (Página inicial)
├── 📄 produto.html        (Página de produto)
├── 📄 camiseta.html       (Página da camiseta)
├── 📄 login.php           (Página de login)
├── 📄 cadastro.php        (Página de cadastro)
├── 📄 painel.php          (Painel administrativo)
├── 📄 conexao.php         (Conexão com banco)
├── 📄 processa_login.php  (Processa login)
├── 📄 processa_cadastro.php (Processa cadastro)
├── 📄 logout.php          (Sair do sistema)
├── 📄 style.css           (Estilos principais)
├── 📄 produto.css         (Estilos do produto)
├── 📄 cadastro.css        (Estilos do cadastro)
├── 📄 painel.css          (Estilos do painel)
├── 📄 script.js           (JavaScript principal)
├── 📄 produto.js          (JavaScript do produto)
├── 📄 comentarios-camiseta.js (Comentários da camiseta)
└── 📁 imagens/            (Imagens do projeto)
```

---

## 🏠 PÁGINAS PRINCIPAIS

### 1. INDEX.HTML - Página Inicial

**Para que serve:** É a primeira página que o usuário vê. Mostra produtos em destaque, permite busca, acesso ao carrinho e login/cadastro.

**Principais elementos:**

```html
<!-- Barra superior preta -->
<header id="barra-topo">
    <div class="infos-usuario">
        <span>Bem-vindo, <a href="login.html">faça login</a> ou <a href="cadastro.php">cadastre-se</a></span>
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
    </div>
</header>
```

**Por que usar essa estrutura?**
- `<header>`: Agrupa elementos do topo da página
- `id="barra-topo"`: Identifica a barra para estilizar no CSS
- Links de login/cadastro: Permitem acesso rápido ao sistema
- Campo de busca: Funcionalidade essencial para encontrar produtos
- Ícone do carrinho: Acesso visual ao carrinho de compras

**Seção de produtos:**
```html
<section id="produtos">
    <h2>Produtos em destaque</h2>
    <div class="menu-categorias">
        <button class="categoria-btn ativo" data-categoria="todos">Todos</button>
        <button class="categoria-btn" data-categoria="perfume">Perfume</button>
        <button class="categoria-btn" data-categoria="camiseta">Camiseta</button>
    </div>
    <div class="grande-produtos">
        <!-- Produtos aqui -->
    </div>
</section>
```

**Por que usar `data-categoria`?**
- Permite ao JavaScript identificar qual categoria cada produto pertence
- Facilita a filtragem de produtos por categoria

### 2. PRODUTO.HTML - Página de Produto

**Para que serve:** Mostra detalhes completos de um produto específico, com opções de tamanho, quantidade, comentários e avaliações.

**Principais elementos:**

```html
<main class="produto-detalhe">
    <div class="img-principal">
        <img src="imagens/perfume1.png" alt="212 VIP Men" />
    </div>
    <div class="info-produto">
        <h2>212 VIP Men</h2>
        <p class="descricao">Um perfume sofisticado para homens modernos.</p>
        <p class="preco">R$ 400,00</p>
        <div class="tamanhos">
            <span class="opcao-tamanho">50ml</span>
            <span class="opcao-tamanho">100ml</span>
            <span class="opcao-tamanho">200ml</span>
        </div>
        <button id="botao-comprar-detalhado">Comprar agora</button>
        <button id="botao-adicionar-carrinho">Adicionar no Carrinho</button>
    </div>
</main>
```

**Seção de comentários:**
```html
<section id="comentarios">
    <div class="form-comentario">
        <h3>Deixe seu comentário</h3>
        <div class="avaliacao-estrelas">
            <input type="radio" id="estrela1" name="avaliacao" value="1">
            <label for="estrela1">⭐</label>
            <!-- Mais estrelas... -->
        </div>
        <input type="text" id="nome-comentario" placeholder="Digite seu nome">
        <textarea id="texto-comentario" placeholder="Conte sua experiência..."></textarea>
        <button id="enviar-comentario">Enviar Comentário</button>
    </div>
</section>
```

---

## 🔐 SISTEMA DE LOGIN E CADASTRO

### 1. LOGIN.PHP

**Para que serve:** Página onde o usuário faz login na loja.

**Código principal:**
```php
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Login - Loja Oline</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Entrar na Loja</h1>
        
        <?php 
        // Mostra mensagens de erro ou sucesso
        if (isset($_SESSION['sucesso'])) {
            echo '<p class="mensagem-sucesso">' . $_SESSION['sucesso'] . '</p>';
            unset($_SESSION['sucesso']);
        }
        if (isset($_SESSION['erro'])) {
            echo '<p class="mensagem-erro">' . $_SESSION['erro'] . '</p>';
            unset($_SESSION['erro']);
        }
        ?>

        <form class="login-form" action="processa_login.php" method="POST">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
            
            <label for="senha">Senha</label>
            <div class="password-container">
                <input type="password" id="senha" name="senha" required>
                <span class="toggle-password" id="toggle-password">👁️</span>
            </div>
            
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
```

**Explicação do código:**
- `<?php session_start(); ?>`: Inicia a sessão PHP para guardar dados do usuário
- `$_SESSION['sucesso']` e `$_SESSION['erro']`: Variáveis que guardam mensagens
- `unset()`: Remove a mensagem da sessão para não aparecer novamente
- `method="POST"`: Envia dados de forma segura
- `action="processa_login.php"`: Para onde os dados são enviados

### 2. CADASTRO.PHP

**Para que serve:** Página onde o usuário cria uma nova conta.

**Código principal:**
```php
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Loja Oline - Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <div id="container">
        <section id="formulario-cadastro">
            <h1>📝 Cadastre-se</h1>
            
            <?php 
            if (isset($_SESSION['erro'])) {
                echo '<p class="mensagem-erro">' . $_SESSION['erro'] . '</p>';
                unset($_SESSION['erro']);
            }
            ?>

            <form id="form-cadastro" action="processa_cadastro.php" method="POST">
                <div class="campo">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="campo">
                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="campo">
                    <label for="senha">Senha *</label>
                    <div class="password-container">
                        <input type="password" id="senha" name="senha" required>
                        <span id="toggle-password" class="toggle-password">👁️</span>
                    </div>
                </div>

                <button type="submit" class="botao-cadastrar">Cadastrar</button>
            </form>
        </section>
    </div>
</body>
</html>
```

### 3. CONEXAO.PHP

**Para que serve:** Conecta o sistema ao banco de dados MySQL.

**Código completo:**
```php
<?php
$host = 'localhost';      // Endereço do servidor MySQL
$dbname = 'loja_oline';   // Nome do banco de dados
$user = 'root';           // Usuário do banco
$pass = '';               // Senha do banco (vazia no WAMP)

try {
    // Cria a conexão usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    // Configura para mostrar erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Se der erro, mostra a mensagem
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
```

**Explicação:**
- `PDO`: Classe do PHP para conectar com banco de dados
- `try/catch`: Trata erros de conexão
- `charset=utf8`: Permite caracteres especiais (acentos)
- `$pdo`: Variável que guarda a conexão

### 4. PROCESSA_CADASTRO.PHP

**Para que serve:** Recebe os dados do cadastro, valida e salva no banco.

**Código completo:**
```php
<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe e limpa os dados
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Validação dos campos
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Por favor, preencha todos os campos.";
        header("Location: cadastro.php");
        exit();
    }

    // Validação do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "Formato de e-mail inválido.";
        header("Location: cadastro.php");
        exit();
    }
    
    // Validação da senha
    if (strlen($senha) < 6) {
        $_SESSION['erro'] = "A senha deve ter no mínimo 6 caracteres.";
        header("Location: cadastro.php");
        exit();
    }

    try {
        // Verifica se o e-mail já existe
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $_SESSION['erro'] = "Este e-mail já está cadastrado.";
            header("Location: cadastro.php");
            exit();
        }

        // Criptografa a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere o usuário
        $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$nome, $email, $senha_hash]);

        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça o login.";
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        die("Erro ao processar o cadastro: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>
```

**Explicação das validações:**
- `empty()`: Verifica se o campo está vazio
- `filter_var()`: Valida formato do e-mail
- `strlen()`: Verifica tamanho da senha
- `password_hash()`: Criptografa a senha para segurança
- `prepare()`: Prepara consulta SQL para evitar injeção de código

### 5. PROCESSA_LOGIN.PHP

**Para que serve:** Recebe dados do login, verifica no banco e cria a sessão.

**Código completo:**
```php
<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = "E-mail e senha são obrigatórios.";
        header("Location: login.php");
        exit();
    }

    try {
        // Busca o usuário pelo e-mail
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se encontrou o usuário e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Cria a sessão do usuário
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            header("Location: painel.php");
            exit();

        } else {
            $_SESSION['erro'] = "E-mail ou senha inválidos.";
            header("Location: login.php");
            exit();
        }

    } catch (PDOException $e) {
        die("Erro no login: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>
```

**Explicação:**
- `password_verify()`: Compara a senha digitada com o hash salvo
- `$_SESSION['usuario_id']`: Guarda o ID do usuário na sessão
- `$_SESSION['usuario_nome']`: Guarda o nome para mostrar no painel

### 6. PAINEL.PHP

**Para que serve:** Página restrita que só usuários logados podem acessar.

**Código completo:**
```php
<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$nome_usuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
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
            <p>Bem-vindo ao seu painel de controle.</p>
            
            <div class="caixas-info">
                <div class="caixa">
                    <h3>Total de Produtos</h3>
                    <p>0</p>
                </div>
                <div class="caixa">
                    <h3>Total de Usuários</h3>
                    <p>0</p>
                </div>
                <div class="caixa">
                    <h3>Vendas Hoje</h3>
                    <p>R$ 0,00</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
```

**Explicação:**
- `!isset($_SESSION['usuario_id'])`: Verifica se o usuário não está logado
- `htmlspecialchars()`: Protege contra ataques XSS
- Menu lateral: Navegação entre diferentes seções do painel

### 7. LOGOUT.PHP

**Para que serve:** Destroi a sessão e faz o usuário sair do sistema.

**Código completo:**
```php
<?php
session_start();

// Limpa todas as variáveis da sessão
$_SESSION = array();

// Destrói a sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Redireciona para o login
session_start();
$_SESSION['sucesso'] = "Você saiu com segurança.";
header("Location: login.php");
exit();
?>
```

**Explicação:**
- `$_SESSION = array()`: Limpa todas as variáveis da sessão
- `session_destroy()`: Destrói completamente a sessão
- `setcookie()`: Remove o cookie da sessão do navegador

---

## 🗄️ BANCO DE DADOS

### Estrutura da Tabela de Usuários

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Explicação dos campos:**
- `id`: Identificador único de cada usuário (auto-incremento)
- `nome`: Nome completo do usuário
- `email`: E-mail único (não pode repetir)
- `senha`: Senha criptografada (hash)
- `tipo`: Tipo de usuário (cliente ou administrador)
- `data_cadastro`: Data e hora do cadastro

### Comandos SQL Usados

**1. Inserir usuário:**
```sql
INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)
```

**2. Buscar usuário por e-mail:**
```sql
SELECT * FROM usuarios WHERE email = ?
```

**3. Verificar se e-mail existe:**
```sql
SELECT id FROM usuarios WHERE email = ?
```

**4. Buscar todos os usuários:**
```sql
SELECT * FROM usuarios ORDER BY nome
```

---

## ⚡ FUNCIONALIDADES JAVASCRIPT

### 1. SCRIPT.JS - Funcionalidades Principais

**Carrinho de Compras:**
```javascript
// Variáveis globais
let total = 0;
const listaCarrinho = document.getElementById('lista-carrinho');
const totalCarrinho = document.getElementById('total-carrinho');
const contadorCarrinho = document.getElementById('contador-carrinho');

// Função para adicionar produto ao carrinho
function adicionarAoCarrinho(botao) {
    const nome = botao.getAttribute('data-nome');
    const preco = parseFloat(botao.getAttribute('data-preco'));

    // Verifica se o item já está no carrinho
    const itemExistente = Array.from(listaCarrinho.children).find(
        item => item.getAttribute('data-nome') === nome
    );

    if (itemExistente) {
        console.log("Item já está no carrinho.");
        return; 
    }

    // Cria o elemento do item
    const item = document.createElement('li');
    item.setAttribute('data-nome', nome);
    item.innerHTML = `
        ${nome} - R$ ${preco.toFixed(2)}
        <button class="botao-remover">🗑️</button>
    `;

    // Adiciona evento para remover item
    item.querySelector('.botao-remover').addEventListener('click', () => {
        total -= preco;
        item.remove();
        atualizarTotal();
        atualizarContador();
    });

    listaCarrinho.appendChild(item);
    total += preco;
    atualizarTotal();
    atualizarContador();
}

// Função para atualizar o total
function atualizarTotal() {
    totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
}

// Função para atualizar o contador
function atualizarContador() {
    contadorCarrinho.textContent = listaCarrinho.children.length;
}
```

**Filtro de Categorias:**
```javascript
document.querySelectorAll('.categoria-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove classe ativo de todos os botões
        document.querySelectorAll('.categoria-btn').forEach(b => b.classList.remove('ativo'));
        // Adiciona classe ativo no botão clicado
        this.classList.add('ativo');
        
        const categoria = this.getAttribute('data-categoria');
        
        // Filtra os produtos
        document.querySelectorAll('.produto').forEach(prod => {
            if (categoria === 'todos') {
                prod.style.display = '';
            } else if (prod.getAttribute('data-categoria') === categoria) {
                prod.style.display = '';
            } else {
                prod.style.display = 'none';
            }
        });
    });
});
```

### 2. PRODUTO.JS - Sistema de Comentários

**Estrutura de dados:**
```javascript
// Dados do produto
const produtoId = 'perfume-212-vip';

// Carregar comentários do localStorage
let comentarios = JSON.parse(localStorage.getItem(`comentarios_${produtoId}`)) || [];

// Comentários de exemplo
if (comentarios.length === 0) {
    comentarios = [
        {
            id: 1,
            nome: 'Maria Silva',
            texto: 'Perfume incrível! Duração excelente.',
            avaliacao: 5,
            data: '2024-01-15',
            foto: null
        }
    ];
    salvarComentarios();
}
```

**Função para enviar comentário:**
```javascript
function enviarComentario(e) {
    e.preventDefault();

    // Validação
    if (!nomeComentario.value.trim()) {
        alert('Por favor, digite seu nome.');
        return;
    }

    if (!textoComentario.value.trim()) {
        alert('Por favor, digite seu comentário.');
        return;
    }

    // Pega avaliação selecionada
    let avaliacao = 5;
    avaliacaoEstrelas.forEach(estrela => {
        if (estrela.checked) {
            avaliacao = parseInt(estrela.value);
        }
    });

    // Cria novo comentário
    const novoComentario = {
        id: Date.now(),
        nome: nomeComentario.value.trim(),
        texto: textoComentario.value.trim(),
        avaliacao: avaliacao,
        data: new Date().toISOString().split('T')[0],
        foto: null
    };

    comentarios.unshift(novoComentario); // Adiciona no início
    salvarComentarios();
    carregarComentarios();
    limparFormulario();
}
```

**Função para salvar no localStorage:**
```javascript
function salvarComentarios() {
    localStorage.setItem(`comentarios_${produtoId}`, JSON.stringify(comentarios));
}
```

---

## 🎨 ESTILOS CSS

### 1. STYLE.CSS - Estilos Principais

**Reset CSS:**
```css
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
```

**Por que usar?** Remove margens e preenchimentos padrão do navegador para ter controle total sobre o layout.

**Barra superior:**
```css
#barra-topo {
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    position: fixed;
    top: 0;
    z-index: 1000;
}
```

**Explicação:**
- `position: fixed`: Mantém a barra fixa no topo
- `z-index: 1000`: Garante que fique acima de outros elementos
- `display: flex`: Organiza os elementos horizontalmente

**Produtos:**
```css
.produto {
    width: 200px;
    padding: 10px;
    background-color: #fff;
    border-radius: 8px;
    transition: transform 0.3s;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.produto:hover {
    transform: scale(1.03);
}
```

**Explicação:**
- `transition`: Cria animação suave
- `transform: scale(1.03)`: Aumenta o produto em 3% ao passar o mouse
- `box-shadow`: Cria sombra para dar profundidade

**Carrinho:**
```css
#carrinho {
    display: none;
    background-color: #fff;
    padding: 30px;
    max-width: 500px;
    margin: 120px auto;
    border-radius: 12px;
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
}

#carrinho.ativo {
    display: block;
}
```

**Explicação:**
- `display: none`: Esconde o carrinho por padrão
- `.ativo`: Classe que mostra o carrinho quando adicionada

### 2. PRODUTO.CSS - Estilos da Página de Produto

**Layout principal:**
```css
.produto-detalhe {
    max-width: 1000px;
    margin: 120px auto 40px;
    display: flex;
    gap: 40px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}
```

**Seleção de tamanhos:**
```css
.tamanhos span {
    display: inline-block;
    background-color: #eee;
    border: 1px solid #ccc;
    padding: 5px 10px;
    margin-right: 8px;
    cursor: pointer;
    border-radius: 4px;
}

.tamanhos .ativo {
    background-color: green;
    color: white;
    border-color: green;
}
```

**Comentários:**
```css
.comentario {
    background-color: #f9f9f9;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    border-left: 4px solid #0af;
}

.comentario-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 14px;
}
```

### 3. Responsividade

**Para tablets (max-width: 768px):**
```css
@media (max-width: 768px) {
    #barra-topo {
        padding: 8px 15px;
        font-size: 12px;
    }
    
    .produto-detalhe {
        flex-direction: column;
        gap: 20px;
    }
    
    .grande-produtos {
        justify-content: center;
    }
}
```

**Para celulares (max-width: 480px):**
```css
@media (max-width: 480px) {
    #profile img {
        width: 200px;
    }
    
    .produto {
        width: 150px;
    }
}
```

---

## 🔄 CICLO COMPLETO DO SISTEMA

### 1. Cadastro de Usuário

**Passo a passo:**

1. **Usuário acessa `cadastro.php`**
   - Vê formulário para preencher nome, e-mail e senha

2. **Preenche e envia o formulário**
   - Dados vão para `processa_cadastro.php`

3. **Validação dos dados**
   - Verifica se campos não estão vazios
   - Valida formato do e-mail
   - Verifica se senha tem pelo menos 6 caracteres

4. **Verificação no banco**
   - Consulta se o e-mail já existe
   - Se existir, mostra erro

5. **Salvamento no banco**
   - Criptografa a senha com `password_hash()`
   - Insere usuário na tabela `usuarios`

6. **Redirecionamento**
   - Vai para `login.php` com mensagem de sucesso

**Exemplo de fluxo:**
```
cadastro.php → processa_cadastro.php → validação → banco de dados → login.php
```

### 2. Login de Usuário

**Passo a passo:**

1. **Usuário acessa `login.php`**
   - Vê formulário para e-mail e senha

2. **Preenche e envia**
   - Dados vão para `processa_login.php`

3. **Busca no banco**
   - Procura usuário pelo e-mail
   - Se não encontrar, mostra erro

4. **Verifica senha**
   - Usa `password_verify()` para comparar
   - Se não bater, mostra erro

5. **Cria sessão**
   - Guarda dados do usuário na sessão
   - `$_SESSION['usuario_id']`
   - `$_SESSION['usuario_nome']`

6. **Redirecionamento**
   - Vai para `painel.php`

**Exemplo de fluxo:**
```
login.php → processa_login.php → banco de dados → verificação → painel.php
```

### 3. Acesso ao Painel

**Passo a passo:**

1. **Usuário tenta acessar `painel.php`**
   - Sistema verifica se existe sessão ativa

2. **Se não estiver logado**
   - Redireciona para `login.php`

3. **Se estiver logado**
   - Mostra painel com informações do usuário
   - Menu lateral com opções

4. **Navegação no painel**
   - Dashboard, produtos, usuários, etc.

### 4. Logout

**Passo a passo:**

1. **Usuário clica em "Sair"**
   - Vai para `logout.php`

2. **Destruição da sessão**
   - Limpa todas as variáveis da sessão
   - Remove cookie da sessão
   - Destrói a sessão

3. **Redirecionamento**
   - Vai para `login.php` com mensagem

---

## 💡 EXEMPLOS PRÁTICOS

### Exemplo 1: Cadastro de Usuário

**Dados inseridos:**
- Nome: João Silva
- E-mail: joao@email.com
- Senha: 123456

**O que acontece no banco:**
```sql
INSERT INTO usuarios (nome, email, senha) 
VALUES ('João Silva', 'joao@email.com', '$2y$10$...hash...');
```

**Resultado:**
- Usuário salvo com ID 1
- Senha criptografada
- Redirecionamento para login

### Exemplo 2: Login do Usuário

**Dados inseridos:**
- E-mail: joao@email.com
- Senha: 123456

**O que acontece:**
1. Busca no banco: `SELECT * FROM usuarios WHERE email = 'joao@email.com'`
2. Encontra o usuário
3. Verifica senha: `password_verify('123456', '$2y$10$...hash...')`
4. Senha correta → cria sessão
5. Redireciona para painel

### Exemplo 3: Adicionar Produto ao Carrinho

**Ação do usuário:**
- Clica no botão "🛒" do produto "212 VIP Men"

**O que acontece no JavaScript:**
```javascript
adicionarAoCarrinho(botao);
// Pega nome: "212 VIP Men"
// Pega preço: 400
// Cria elemento <li> com o produto
// Adiciona na lista do carrinho
// Atualiza total: R$ 400,00
// Atualiza contador: 1
```

### Exemplo 4: Comentário em Produto

**Dados inseridos:**
- Nome: Maria
- Comentário: "Perfume muito bom!"
- Avaliação: 5 estrelas

**O que acontece:**
1. Validação dos campos
2. Cria objeto do comentário
3. Adiciona no array de comentários
4. Salva no localStorage
5. Atualiza a lista na tela

---

## 🔧 CONFIGURAÇÃO DO AMBIENTE

### 1. Instalação do WAMP/XAMPP

**Passos:**
1. Baixar WAMP Server
2. Instalar no C:\wamp64
3. Iniciar o servidor
4. Colocar arquivos na pasta www

### 2. Configuração do Banco de Dados

**Criar banco:**
```sql
CREATE DATABASE loja_oline;
USE loja_oline;
```

**Criar tabela:**
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Configuração da Conexão

**Arquivo conexao.php:**
```php
$host = 'localhost';
$dbname = 'loja_oline';
$user = 'root';
$pass = '';
```

---

## 📝 RESUMO FINAL

### O que você aprendeu:

1. **HTML**: Estrutura de páginas web
2. **CSS**: Estilização e responsividade
3. **JavaScript**: Interatividade e funcionalidades
4. **PHP**: Backend e processamento
5. **MySQL**: Banco de dados
6. **Sessões**: Controle de usuários logados
7. **Segurança**: Validação e criptografia

### Funcionalidades implementadas:

✅ Sistema de cadastro e login  
✅ Painel administrativo  
✅ Carrinho de compras  
✅ Sistema de comentários  
✅ Filtro de categorias  
✅ Design responsivo  
✅ Validação de dados  
✅ Criptografia de senhas  

### Próximos passos:

- Adicionar mais produtos
- Implementar sistema de pagamento
- Criar área de pedidos
- Adicionar upload de imagens
- Implementar busca avançada

---

## 🎯 DICAS PARA REVISÃO

1. **Leia o código linha por linha** e entenda o que cada parte faz
2. **Teste cada funcionalidade** para ver como funciona na prática
3. **Modifique pequenas coisas** para ver o que muda
4. **Pratique criando** novas funcionalidades
5. **Use os comentários** no código para entender melhor

**Lembre-se:** A prática é a melhor forma de aprender! 🚀

---

*Documentação criada especialmente para facilitar o estudo e revisão do projeto Loja Online.* 