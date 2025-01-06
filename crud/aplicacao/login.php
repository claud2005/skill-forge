<?php
# Middleware para garantir que apenas utilizadores não autenticados vejam a página de login
require_once __DIR__ . '/../src/middleware/middleware-nao-autenticado.php';

# Define o título da página
$titulo = ' - Login';

# Inicia o cabeçalho
include_once __DIR__ . '/templates/cabecalho.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<!-- Background com gradiente e centralização do formulário -->
<body class="d-flex justify-content-center align-items-center min-vh-100" style="background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);">
  <div class="card shadow-lg border-0" style="max-width: 550px; width: 100%; border-radius: 20px; background-color: #ffffff; padding: 2rem; position: relative;">
    
    <!-- Link para voltar à página inicial com ícone de seta -->
    <a href="/" class="back-btn position-absolute top-0 start-0 p-3 text-decoration-none" style="color: #2193b0; font-size: 1.5rem;">
      <i class="fas fa-arrow-left"></i> <!-- Ícone de seta -->
    </a>

    <div class="card-body p-4 p-sm-5 text-center">
      <h1 class="mb-4 fw-bold" style="color: #333;">Bem-vindo ao <span style="color: #2193b0;">SkillForge</span>!</h1>
      <p class="text-muted mb-5">Acesse sua conta e explore novos conhecimentos.</p>

      <?php
      # Exibe mensagens de erro, se houver
      if (isset($_SESSION['erros'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        foreach ($_SESSION['erros'] as $erro) {
          echo $erro . '<br>';
        }
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        unset($_SESSION['erros']);
      }
      ?>

      <!-- Formulário de Login -->
      <form action="/src/controlador/aplicacao/controlar-autenticacao.php" method="post" class="text-start">
        
        <!-- Campo de Email -->
        <div class="form-floating mb-4">
          <input type="email" class="form-control form-control-lg" id="Email" placeholder="Endereço de Email" name="email" maxlength="255" value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : '' ?>" style="border-radius: 10px;">
          <label for="Email" class="text-secondary">Endereço de Email</label>
        </div>

        <!-- Campo de Palavra Passe com Toggle -->
        <div class="form-floating mb-4 position-relative">
          <input type="password" class="form-control form-control-lg" id="palavra_passe" placeholder="Palavra Passe" name="palavra_passe" maxlength="255" style="border-radius: 10px;">
          <label for="palavra_passe" class="text-secondary">Palavra Passe</label>
          <!-- Ícone atualizado para visibilidade da senha -->
          <span class="toggle-password position-absolute top-50 end-0 translate-middle-y pe-3" style="cursor: pointer; font-size: 1.2rem; color: #2193b0;" data-show="true">👁️</span>
        </div>

        <!-- Link para recuperação de senha -->
        <div class="text-center mb-4">
          <a href="/aplicacao/EsqueceuSenha.php" class="text-decoration-none" style="color: #2193b0;">Esqueceu sua senha?</a>
        </div>

        <!-- Botão de login com ícone -->
        <button class="btn btn-lg w-100 fw-bold text-white" type="submit" name="utilizador" value="login" style="background-color: #2193b0; border-radius: 10px;">
          <i class="fas fa-sign-in-alt me-2"></i> Entrar <!-- Ícone de login -->
        </button>

        <!-- Link para página de registro -->
        <p class="mt-4 text-secondary">Não tem uma conta? <a href="/aplicacao/Registo.php" class="text-decoration-none" style="color: #2193b0;">Crie uma conta</a></p>
      </form>
    </div>
  </div>

  <script>
    // Alterna a visibilidade da senha com ícones intuitivos
    document.querySelector('.toggle-password').addEventListener('click', function () {
      const passwordInput = document.getElementById('palavra_passe');
      const isPasswordHidden = passwordInput.getAttribute('type') === 'password';
      
      // Troca o tipo de input entre 'password' e 'text'
      passwordInput.setAttribute('type', isPasswordHidden ? 'text' : 'password');
      
      // Atualiza o ícone para indicar a ação
      this.textContent = isPasswordHidden ? '🙈' : '👁️';
    });
  </script>

  <!-- Inclui o JavaScript do Bootstrap diretamente da CDN -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0t5V06p6fB5o81mRnxj2X/4KhgKmrpT6L8BrlvYfQ1kj6bT4" crossorigin="anonymous"></script>
</body>
