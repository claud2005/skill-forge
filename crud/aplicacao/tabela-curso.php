<?php
# INICIALIZA O REPOSITÓRIO
require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-curso.php';  // Certifique-se de que a função lerTodosCurso está aqui

# INSERE DADOS DA CONEXÃO COM O PDO
require_once __DIR__ . '/../src/infraestrutura/basededados/criar-conexao.php';

# MIDDLEWARE PARA GARANTIR QUE APENAS ADMINISTRADORES ACESSEM ESTA PÁGINA
require_once __DIR__ . '/../src/middleware/middleware-administrador.php';

# CHECA SE A CONEXÃO FOI REALIZADA COM SUCESSO
if (!$pdo) {
    echo "Erro de conexão com o banco de dados!";
    exit;
}

# FAZ O CARREGAMENTO DE TODOS OS CURSOS PARA MOSTRAR AO ADMINISTRADOR
$cursos = lerTodosCurso($pdo); // Passando $pdo como argumento

# CARREGA O CABECALHO PADRÃO COM O TÍTULO
$titulo = ' - Painel de Administração de Cursos';
require_once __DIR__ . '/../aplicacao/templates/cabecalho.php';
require_once __DIR__ . '/../aplicacao/templates/navbar.php';
?>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso'] ?></div>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['erros'])): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($_SESSION['erros'] as $erro): ?>
                <li><?= $erro ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['erros']); ?>
<?php endif; ?>

<main class="bg-light p-5" style="flex: 1; display: flex; flex-direction: column;">
  <section class="py-4">
    <h2 class="text-center mb-4">Cursos</h2>
    
    <!-- Botões Criar e Sair Administração, agora com tamanho menor -->
    <div class="d-flex justify-content-start mb-1">
      <a href="/admin/curso.php">
        <button class="btn btn-success px-3 py-2 me-2 rounded-3 shadow-sm">Criar Curso</button>
      </a>
      <a href="/aplicacao/">
        <button class="btn btn-info px-3 py-2 rounded-3 shadow-sm">Sair</button>
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
            <th scope="col" class="text-center">Título</th>
            <th scope="col" class="text-center">Instrutor</th>
            <th scope="col" class="text-center">Categoria</th>
            <th scope="col" class="text-center">Código do Curso</th>
            <th scope="col" class="text-center">Duração</th>
            <th scope="col" class="text-center">Ano de Lançamento</th>
            <th scope="col" class="text-center">Descrição</th>
            <th scope="col" class="text-center">Gerenciar</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # VARRE TODOS OS CURSOS PARA CONSTRUÇÃO DA TABELA
          foreach ($cursos as $curso) {
          ?>
            <tr>
              <td class="text-center"><?= htmlspecialchars($curso['titulo']) ?></td>
              <td class="text-center"><?= htmlspecialchars($curso['instrutor']) ?></td>
              <td class="text-center"><?= htmlspecialchars($curso['categoria']) ?></td>
              <td class="text-center"><?= htmlspecialchars($curso['codigo_do_curso']) ?></td>
              <td class="text-center"><?= htmlspecialchars($curso['duracao_em_horas']) ?> horas</td>
              <td class="text-center"><?= htmlspecialchars($curso['ano_de_lancamento']) ?></td>
              <td class="text-center"><?= htmlspecialchars($curso['descricao']) ?></td>
              <td class="text-center">
                <div class="d-flex justify-content-center">
                  <a href="/../src/controlador/admin/controlar-curso.php?<?= 'curso=atualizar&id=' . $curso['id'] ?>" class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-pencil-square"></i> Atualizar
                  </a>

                  <button type="button" class="btn btn-outline-danger btn-sm me-2" data-bs-toggle="modal" data-bs-target="#deletarModal<?= $curso['id'] ?>">
                    <i class="bi bi-trash"></i> Deletar
                  </button>
                </div>
              </td>
            </tr>

            <!-- Modal de confirmação de deleção -->
            <div class="modal fade" id="deletarModal<?= $curso['id'] ?>" tabindex="-1" aria-labelledby="deletarModalLabel<?= $curso['id'] ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deletarModalLabel<?= $curso['id'] ?>">Excluir Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
  <p><strong>ATENÇÃO!</strong> Você está prestes a excluir o curso <strong><?= htmlspecialchars($curso['titulo']) ?></strong>.</p>
  <p>Esta ação é irreversível e todos os dados relacionados ao curso serão permanentemente apagados. Você tem certeza de que deseja continuar?</p>
</div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="/../src/controlador/admin/controlar-curso.php?<?= 'curso=deletar&id=' . $curso['id'] ?>"><button type="button" class="btn btn-danger">Confirmar</button></a>
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

<?php
// repositorio-curso.php (Arquivo que contém a função lerTodosCurso)

function lerTodosCurso($pdo) {
    $sql = "SELECT * FROM cursos";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna todos os cursos como um array associativo
}
?>
