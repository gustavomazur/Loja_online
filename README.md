# Loja Online com Painel Administrativo

Sistema de loja virtual com painel administrativo completo feito em PHP e MySQL. Permite cadastro de produtos com múltiplas imagens por cor, gerenciamento de estoque, usuários e responsividade para dispositivos móveis.

---

## Demonstrações

### Painel do Cliente (Desktop)
![Cliente Desktop](screenshots/Cliente.png)

### Painel do Cliente (Mobile)
![Cliente Mobile](screenshots/Cliente-mobile.png)

### Painel do Dono da Loja
![Painel do Dono](screenshots/Painel-dono.png)

### Visualização de Produtos
![Produtos](screenshots/Produtos.png)

### Gerenciamento de Usuários
![Usuários](screenshots/Usuarios.png)

---

## Funcionalidades

- Cadastro de produtos com imagem principal e múltiplas imagens por cor (JSON).
- Controle de estoque, tamanhos e cores.
- Gerenciamento de usuários.
- Login com autenticação por sessão.
- Painel administrativo separado do painel do cliente.
- Layout responsivo para desktop e mobile.

---

## Como rodar localmente

1. Clone o repositório:

```bash
git clone https://github.com/gustavomazur/Loja_online.git

2. Importe o banco de dados MySQL (arquivo .sql se disponível).

3. Configure o arquivo conexao.php com seus dados de conexão:

$pdo = new PDO("mysql:host=localhost;dbname=nomedobanco", "usuario", "senha");

4. Suba o projeto em um servidor local (ex: WAMP, XAMPP).

5. Acesse no navegador:

http://localhost/loja.oline
\

Estrutura básica
loja.oline/
├── painel.php
├── painel_produtos.php
├── painel_usuarios.php
├── cadastro.php
├── login.php
├── index.php
├── conexao.php
├── processa_produto.php
├── style.css
├── painel.css
├── screenshots/        
│   ├── Cliente.png
│   ├── Cliente-mobile.png
│   ├── Painel-dono.png
│   ├── Produtos.png
│   └── Usuarios.png

Tecnologias
-PHP
-MySQL
-HTML5
-CSS3
-JavaScript

Autor
Gustavo-Bueno-Mazur

🔗LinkedIn
https://www.linkedin.com/in/gustavo-bueno-mazur/




