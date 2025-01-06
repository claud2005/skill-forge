<?php
# INICIALIZA O REPOSITÓRIO
require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-utilizador.php';

# MIDDLEWARE PARA GARANTIR QUE APENAS ADMNISTRADORES ACESSEM ESTA PÁGINA
require_once __DIR__ . '/../src/middleware/middleware-administrador.php';

# FAZ O CARREGAMENTO DE TODOS OS UTILIZADORES PARA MOSTRAR AO ADMINISTRADOR
$utilizadores = lerTodosUtilizadores();
require_once __DIR__ . '/../aplicacao/templates/cabecalho.php';

# CARREGA O CABECALHO PADRÃO COM O TÍTULO
$titulo = ' - Painel de Administração';
require_once __DIR__ . '/../aplicacao/templates/cabecalho.php';
require_once __DIR__ . '/../aplicacao/templates/navbar.php';
?>
<main class="bg-light p-5" style="flex: 1; display: flex; flex-direction: column;">
  <section class="py-2">
    <h2 class="text-center mb-4">Utilizadores</h2>

    <!-- Juntando os botões Criar Utilizador e Sair Administração na mesma linha -->
    <div class="d-flex justify-content-start mb-3">
      <a href="/admin/utilizador.php">
        <button class="btn btn-success px-4 py-2 me-2">Criar Utilizador</button>
      </a>
      <a href="/aplicacao/">
        <button class="btn btn-info px-4 py-2">Sair</button>
      </a>
    </div>
  </section>

  <section class="mb-3">
    <?php
    # MOSTRA AS MENSAGENS DE SUCESSO E DE ERRO
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
      echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
      unset($_SESSION['erros']);
    }
    ?>
  </section>

  <section>
    <div class="table-responsive">
      <table class="table table-striped table-hover table-bordered rounded shadow-lg">
        <thead class="thead-dark bg-primary text-white">
          <tr>
            <th scope="col" class="text-center">Nome</th>
            <th scope="col" class="text-center">Apelido</th>
            <th scope="col" class="text-center">NIF</th>
            <th scope="col" class="text-center">Telemóvel</th>
            <th scope="col" class="text-center">Email</th>
            <th scope="col" class="text-center">Administrador</th>
            <th scope="col" class="text-center">Gerenciar</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # VARRE TODOS OS UTILIZADORES PARA CONSTRUÇÃO DA TABELA
          foreach ($utilizadores as $utilizador) {
          ?>
            <tr>
              <th scope="row" class="text-center"><?= htmlspecialchars($utilizador['nome']) ?></th>
              <td class="text-center"><?= htmlspecialchars($utilizador['apelido'] ?? '') ?></td>
              <td class="text-center"><?= htmlspecialchars($utilizador['nif'] ?? '') ?></td>
              <td class="text-center"><?= htmlspecialchars($utilizador['telemovel'] ?? '') ?></td>
              <td class="text-center"><?= htmlspecialchars($utilizador['email']) ?></td>
              <td class="text-center"><?= $utilizador['administrador'] == '1' ? 'Sim' : 'Não' ?></td>
              <td class="text-center">
                <div class="d-flex justify-content-center">
                  <a href="/src/controlador/admin/controlar-utilizador.php?utilizador=atualizar&id=<?= $utilizador['id'] ?>" class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-pencil-square"></i> Atualizar
                  </a>
                  <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deletarModal<?= $utilizador['id'] ?>">
                    <i class="bi bi-trash"></i> Deletar
                  </button>
                </div>
              </td>
            </tr>

            <!-- Modal de confirmação -->
            <div class="modal fade" id="deletarModal<?= $utilizador['id'] ?>" tabindex="-1" aria-labelledby="deletarModalLabel<?= $utilizador['id'] ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deletarModalLabel<?= $utilizador['id'] ?>">Deletar Utilizador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                  <p>Você está prestes a excluir o utilizador <strong><?= htmlspecialchars($utilizador['nome'] ?? '') ?> <?= htmlspecialchars($utilizador['apelido'] ?? '') ?></strong>.</p>
                    <p><strong>ATENÇÃO:</strong> Esta ação é irreversível e todos os dados do utilizador serão permanentemente apagados. Tem certeza de que deseja continuar?</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="/src/controlador/admin/controlar-utilizador.php?utilizador=deletar&id=<?= $utilizador['id'] ?>">
                      <button type="button" class="btn btn-danger">Confirmar</button>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <!-- Fim Modal -->
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </section>
</main>

<?php
# CARREGA O RODAPE PADRÃO
require_once __DIR__ . '/../aplicacao/templates/rodape.php';
?>