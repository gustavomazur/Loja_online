<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Loja Oline</title>
    <link rel="stylesheet" href="style.css">
    <style>
      .login-container {
        max-width: 400px;
        margin: 120px auto 0 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
        padding: 32px 28px 24px 28px;
      }
      .login-container h1 {
        text-align: center;
        margin-bottom: 24px;
        color: #222;
        font-size: 26px;
      }
      .login-form label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        color: #333;
      }
      .login-form input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        margin-bottom: 18px;
        box-sizing: border-box;
      }
      .login-form input:focus {
        border-color: #0af;
        outline: none;
      }
      .login-form button {
        width: 100%;
        padding: 12px;
        background: #0af;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s;
      }
      .login-form button:hover {
        background: #0099cc;
      }
      .login-links {
        text-align: center;
        margin-top: 18px;
        font-size: 14px;
      }
      .login-links a {
        color: #0af;
        text-decoration: none;
        font-weight: bold;
      }
      .login-links a:hover {
        text-decoration: underline;
      }
      .login-erro {
        color: #ff4444;
        text-align: center;
        margin-bottom: 12px;
        font-size: 14px;
        display: none;
      }
      .mensagem-erro {
        background-color: #ffdddd;
        color: #d8000c;
        border: 1px solid #ffc8c8;
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        text-align: center;
      }
      .mensagem-sucesso {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        text-align: center;
      }
      .password-container {
        position: relative;
        margin-bottom: 18px;
      }
      .password-container input {
        margin-bottom: 0;
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
    </style>
</head>
<body>
  <div class="login-container">
    <h1>Entrar na Loja</h1>

    <?php 
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
      <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
      <label for="senha">Senha</label>
      <div class="password-container">
        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
        <span class="toggle-password" id="toggle-password">👁️</span>
      </div>
      <button type="submit">Entrar</button>
    </form>
    <div class="login-links">
      Não tem conta? <a href="cadastro.php">Cadastre-se</a>
    </div>
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