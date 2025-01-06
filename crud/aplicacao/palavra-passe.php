<?php
# CARREGA MIDDLEWARE PAGARA GARANTIR QUE APENAS UTILIZADORES AUTENTICADOS ACESSEM ESTE SITIO
require_once __DIR__ . '/../src/middleware/middleware-utilizador.php';

# CARREGA O CABECALHO PADRÃO COM O TÍTULO
$titulo = ' - Alterar Palavra Passe';
include_once __DIR__ . '/templates/cabecalho.php';
include_once __DIR__ . '/templates/navbar.php';

# ACESSA DE FUNÇÕES AUXILIADORAS. 
# NOTA: O SIMBOLO ARROBA SERVE PARA NÃO MOSTRAR MENSAGEM DE WARNING, POIS A FUNÇÃO ABAIXO TAMBÉM INICIA SESSÕES
@include_once __DIR__ . '/../src/auxiliadores/auxiliador.php';

# CARREGA O UTILIZADOR ATUAL. PROVENIENTE DE FUNÇÕES AUXILIADORAS ACIMA
$utilizador = utilizador();
?>
<body>
  <main class="container-xl mt-5 px-4">
    <!-- Botão Voltar -->
    <section class="mb-4 text-start">
      <a href="/aplicacao/perfil.php" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
    </section>

    <!-- Mensagens de sucesso e erro -->
    <section class="mb-4">
      <?php
      if (isset($_SESSION['sucesso'])) {
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
          echo '<i class="bi bi-check-circle-fill text-success me-2"></i>' . $_SESSION['sucesso'] . '<br>';
          echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
          unset($_SESSION['sucesso']);
      }
      if (isset($_SESSION['erros'])) {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
          echo '<i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>';
          foreach ($_SESSION['erros'] as $erro) {
              echo $erro . '<br>';
          }
          echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
          unset($_SESSION['erros']);
      }
      ?>
    </section>

    <!-- Formulário Alterar Palavra Passe -->
    <section class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card border-0 shadow-lg mb-5" style="background: #fff; border-radius: 20px;">
          <div class="card-body p-5">
            <h2 class="text-center fw-bold mb-4" style="color: #333;">Alterar Palavra-Passe</h2>
            <p class="text-muted text-center mb-5">Para segurança, escolha uma palavra-passe forte e não compartilhe com ninguém.</p>
            <form action="/src/controlador/admin/controlar-utilizador.php" method="post">
              <!-- Nome -->
              <div class="mb-4">
                <label for="nome" class="form-label" style="color: #555;">Nome</label>
                <input type="text" readonly class="form-control rounded-pill" name="nome" placeholder="<?= $utilizador['nome'] ?>" value="<?= $utilizador['nome'] ?>" style="background-color: #f8f9fa;">
              </div>
              <!-- Palavra Passe -->
              <div class="mb-4">
                <label for="palavra_passe" class="form-label" style="color: #555;">Nova Palavra-Passe</label>
                <div class="input-group">
                  <span class="input-group-text rounded-start" style="background-color: #f8f9fa;"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" class="form-control" id="palavra_passe" name="palavra_passe" maxlength="255" required>
                  <button type="button" class="btn btn-outline-secondary rounded-end" onclick="togglePassword('palavra_passe', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
              <!-- Confirmar Palavra Passe -->
              <div class="mb-4">
                <label for="confirmar_palavra_passe" class="form-label" style="color: #555;">Confirmar Palavra-Passe</label>
                <div class="input-group">
                  <span class="input-group-text rounded-start" style="background-color: #f8f9fa;"><i class="bi bi-check2-square"></i></span>
                  <input type="password" class="form-control" id="confirmar_palavra_passe" name="confirmar_palavra_passe" maxlength="255" required>
                  <button type="button" class="btn btn-outline-secondary rounded-end" onclick="togglePassword('confirmar_palavra_passe', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
              <!-- Botão Alterar -->
              <div class="text-center">
                <button class="btn btn-warning btn-lg rounded-pill px-5 fw-bold" type="submit" name="utilizador" value="palavra_passe">
                  <i class="bi bi-save-fill"></i> Alterar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Script para alternar visibilidade da senha -->
  <script>
    function togglePassword(fieldId, button) {
      const input = document.getElementById(fieldId);
      const icon = button.querySelector('i');
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      } else {
        input.type = "password";
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      }
    }
  </script>
</body>
<?php
include_once __DIR__ . '/templates/rodape.php';
?>
