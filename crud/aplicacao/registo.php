<?php
# Middleware para garantir que apenas utilizadores não autenticados vejam a página de registo
require_once __DIR__ . '/../src/middleware/middleware-nao-autenticado.php';

# Define o título da página
$titulo = '- Registo';
# Inicia cabeçalho
include_once __DIR__ . '/templates/cabecalho.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<body class="d-flex justify-content-center align-items-center min-vh-100" style="background: linear-gradient(135deg, #4b6cb7, #182848);">
  <div class="card shadow-lg rounded-4 position-relative" style="max-width: 600px; width: 100%; padding: 40px; background-color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
    
    <!-- Link para voltar -->
    <a href="/index.php" class="back-btn position-absolute top-0 start-0 p-3 text-decoration-none" style="color: #4b6cb7; font-size: 1.8rem; z-index: 20; padding-top: 10px; padding-left: 10px;">
      <i class="fas fa-arrow-left"></i> <!-- Ícone da seta para voltar -->
    </a>

    <!-- Título principal -->
    <h1 class="h3 mb-4 fw-bold text-center" style="color: #4b6cb7;">Criar Conta</h1>

    <!-- Mensagens de erro e sucesso -->
    <section>
      <?php
      if (isset($_SESSION['sucesso'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        echo $_SESSION['sucesso'] . '<br>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['sucesso']);
      }
      if (isset($_SESSION['erros'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        foreach ($_SESSION['erros'] as $erro) {
          echo $erro . '<br>';
        }
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['erros']);
      }
      ?>
    </section>

    <!-- Formulário de Registro -->
    <form action="/src/controlador/aplicacao/controlar-registo.php" method="post" class="register-form">
      
      <!-- Campo Nome -->
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" maxlength="100" value="<?= isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '' ?>" style="border-radius: 10px;">
        <label for="nome" class="text-secondary">Nome</label>
      </div>

      <!-- Campo Email -->
      <div class="form-floating mb-3">
        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : '' ?>" style="border-radius: 10px;">
        <label for="email" class="text-secondary">Endereço de Email</label>
      </div>

      <!-- Campo Senha -->
      <div class="form-floating mb-3 position-relative">
        <input type="password" class="form-control" id="palavra_passe" name="palavra_passe" placeholder="Palavra Passe" style="border-radius: 10px;">
        <label for="palavra_passe" class="text-secondary">Palavra Passe</label>
        <span class="toggle-password position-absolute top-50 end-0 translate-middle-y pe-3" data-target="palavra_passe" style="cursor: pointer; font-size: 1.2rem; color: #4b6cb7;">👁️</span>
      </div>

      <!-- Campo Confirmar Senha -->
      <div class="form-floating mb-3 position-relative">
        <input type="password" class="form-control" id="confirmar_palavra_passe" name="confirmar_palavra_passe" placeholder="Confirmar Palavra Passe" style="border-radius: 10px;">
        <label for="confirmar_palavra_passe" class="text-secondary">Confirmar Palavra Passe</label>
        <span class="toggle-password position-absolute top-50 end-0 translate-middle-y pe-3" data-target="confirmar_palavra_passe" style="cursor: pointer; font-size: 1.2rem; color: #4b6cb7;">👁️</span>
      </div>

      <!-- Botão de Registro -->
      <button class="btn btn-lg w-100 text-white" type="submit" name="utilizador" value="registo" style="background-color: #4b6cb7; border-radius: 10px; transition: background-color 0.3s ease;">
        <i class="fas fa-user-plus me-2"></i>Criar Conta
      </button>

      <!-- Links extras -->
      <div class="extra-links text-center mt-3">
        <span class="text-muted">Já tem conta? </span>
        <a href="/aplicacao/Login.php" class="text-decoration-none" style="color: #4b6cb7;">Faça Login</a>
      </div>
    </form>
  </div>

  <script>
    // Função para alternar a visibilidade das senhas
    const togglePasswords = document.querySelectorAll('.toggle-password');
    
    togglePasswords.forEach(function(togglePassword) {
      togglePassword.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const passwordInput = document.getElementById(targetId);
        
        // Alterna o tipo de input entre 'password' e 'text'
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Muda o ícone de olho dependendo do tipo de input
        this.textContent = type === 'password' ? '👁️' : '🙈';
      });
    });

    // Efeito de hover no card
    const card = document.querySelector('.card');
    card.addEventListener('mouseenter', function() {
      card.style.transform = 'scale(1.05)';
      card.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
    });
    card.addEventListener('mouseleave', function() {
      card.style.transform = 'scale(1)';
      card.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
    });
  </script>
</body>

