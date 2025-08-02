<?php session_start(); // Inicia a sessão para poder acessar as variáveis de sessão ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Oline - Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="cadastro.css">
    <style>
      .password-container {
        position: relative;
      }
      .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        user-select: none;
        color: #888;
      }
      .cadastro-container {
        max-width: 500px;
        margin: 80px auto 0 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
        padding: 32px 28px 24px 28px;
      }
    </style>
</head>
<body>
    <div class="cadastro-container">
        <h1>📝 Cadastre-se</h1>
        <p class="subtitulo">Crie sua conta e aproveite nossas ofertas!</p>

        <?php 
            // Verifica se existe uma mensagem de erro na sessão e a exibe
            if (isset($_SESSION['erro'])) {
                echo '<p class="mensagem-erro">' . $_SESSION['erro'] . '</p>';
                // Remove a mensagem da sessão para que não seja exibida novamente
                unset($_SESSION['erro']);
            }
        ?>

        <form id="form-cadastro" action="processa_cadastro.php" method="POST">
            <!-- NOME -->
            <div class="campo">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" required placeholder="Digite seu nome completo">
            </div>

            <!-- EMAIL -->
            <div class="campo">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>

            <!-- SENHA -->
            <div class="campo">
                <label for="senha">Senha *</label>
                <div class="password-container">
                    <input type="password" id="senha" name="senha" required placeholder="Mínimo 6 caracteres">
                    <span id="toggle-password" class="toggle-password">👁️</span>
                </div>
            </div>

            <!-- BOTÕES -->
            <div class="botoes-form">
                <button type="submit" class="botao-cadastrar">Cadastrar</button>
            </div>

            <!-- LINK PARA LOGIN -->
            <p class="ja-tem-conta">
                Já tem uma conta? <a href="login.php">Faça login aqui</a>
            </p>
        </form>
    </div>

    <script>
      const togglePassword = document.querySelector('#toggle-password');
      const passwordInput = document.querySelector('#senha');

      togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        this.textContent = type === 'password' ? '👁️' : '🙈';
      });
    </script>
</body>
</html> 