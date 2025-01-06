<?php
session_start();
require_once __DIR__ . '/../src/middleware/middleware-administrador.php';

$titulo = ' - Curso';
require_once __DIR__ . '/../aplicacao/templates/cabecalho.php';
require_once __DIR__ . '/../aplicacao/templates/navbar.php';

require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-curso.php'; // Conexão com o banco

// Verificando se o ID do curso foi passado (no caso de edição)
$idCurso = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
?>

<link rel="stylesheet" href="/../recursos/css/curso.css">
<!-- Incluindo o jQuery (necessário para o Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<!-- Incluindo o Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Incluindo o seu arquivo de scripts (quizes.js) -->
<script src="/../recursos/MeusScripts/quizes.js"></script>

<main class="bg-light py-5">
    <div class="container">
        <!-- Exibição de Mensagens de Sucesso -->
        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sucesso!</strong> <?= $_SESSION['sucesso'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>

        <!-- Exibição de Mensagens de Erro -->
        <?php if (isset($_SESSION['erros'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro!</strong>
                <?php 
                if (is_array($_SESSION['erros'])) {
                    foreach ($_SESSION['erros'] as $erro) {
                        echo htmlspecialchars($erro) . '<br>';
                    }
                } else {
                    echo htmlspecialchars($_SESSION['erros']);
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['erros']); ?>
        <?php endif; ?>

        <!-- Formulário de Cadastro de Curso -->
        <form enctype="multipart/form-data" action="/src/controlador/admin/controlar-curso.php" method="post" class="form-control p-4 shadow-sm rounded">
            <h2 class="text-center fw-bold mb-4">Curso</h2>
            <div class="row g-3">
                <!-- Campos do Formulário -->
                <div class="col-md-6">
                    <label for="inputTitulo" class="form-label fw-bold">Título</label>
                    <input type="text" class="form-control" id="inputTitulo" name="titulo" maxlength="255" placeholder="Digite o título do curso" value="<?= isset($_REQUEST['titulo']) ? $_REQUEST['titulo'] : '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="inputInstrutor" class="form-label fw-bold">Instrutor</label>
                    <input type="text" class="form-control" id="inputInstrutor" name="instrutor" maxlength="100" placeholder="Digite o nome do instrutor" value="<?= isset($_REQUEST['instrutor']) ? $_REQUEST['instrutor'] : '' ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="inputCategoria" class="form-label fw-bold">Categoria</label>
                    <input type="text" class="form-control" id="inputCategoria" name="categoria" maxlength="50" placeholder="Digite a categoria" value="<?= isset($_REQUEST['categoria']) ? $_REQUEST['categoria'] : '' ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="inputCodigo" class="form-label fw-bold">Código do Curso</label>
                    <input type="text" class="form-control" id="inputCodigo" name="codigo_do_curso" maxlength="20" placeholder="Digite o código do curso" value="<?= isset($_REQUEST['codigo_do_curso']) ? $_REQUEST['codigo_do_curso'] : '' ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="inputDuracao" class="form-label fw-bold">Duração (em horas)</label>
                    <input type="text" class="form-control" id="inputDuracao" name="duracao_em_horas" placeholder="Ex: 40" value="<?= isset($_REQUEST['duracao_em_horas']) ? $_REQUEST['duracao_em_horas'] : '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="inputAno" class="form-label fw-bold">Ano de Lançamento</label>
                    <input type="text" class="form-control" id="inputAno" name="ano_de_lancamento" placeholder="Ex: 2023" min="2000" max="<?= date('Y') ?>" value="<?= isset($_REQUEST['ano_de_lancamento']) ? $_REQUEST['ano_de_lancamento'] : '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="inputImagem" class="form-label fw-bold">Imagem do Curso</label>
                    <input accept="image/*" type="file" class="form-control" id="inputImagem" name="imagem_do_curso">
                </div>

                <div class="col-12">
                    <label for="inputDescricao" class="form-label fw-bold">Descrição</label>
                    <textarea class="form-control" id="inputDescricao" name="descricao" rows="6" placeholder="Digite a descrição do curso" required><?= isset($_REQUEST['descricao']) ? $_REQUEST['descricao'] : '' ?></textarea>
                </div>

                <div class="col-12 d-flex justify-content-between align-items-center">
                    <input type="hidden" name="id" value="<?= isset($_REQUEST['id']) ? $_REQUEST['id'] : '' ?>">
                    <input type="hidden" name="imagem_do_curso" value="<?= isset($_REQUEST['imagem_do_curso']) ? $_REQUEST['imagem_do_curso'] : '' ?>">
                    <button type="submit" class="btn btn-success btn-lg" name="curso" value="<?= isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'atualizar' ? 'atualizar' : 'criar' ?>">Enviar</button>
                    <a href="/../aplicacao/tabela-curso.php" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left"></i> Voltar</a>
                </div>
            </div>
        </form>

<!-- Modal Pergunta -->
<div class="modal fade" id="modalPerguntas" tabindex="-1" aria-labelledby="modalPerguntasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #6c757d; color: white;">
        <h5 class="modal-title" id="modalPerguntasLabel">Adicionar Pergunta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formPergunta">
          <div class="mb-3">
            <label for="tituloPergunta" class="form-label">Título da Pergunta</label>
            <input type="text" class="form-control" id="tituloPergunta" placeholder="Digite a pergunta" required>
          </div>

          <div class="mb-3">
            <label for="respostaA_texto" class="form-label">Resposta A</label>
            <input type="text" class="form-control" id="respostaA_texto" placeholder="Digite a resposta A" required>
          </div>

          <div class="mb-3">
            <label for="respostaB_texto" class="form-label">Resposta B</label>
            <input type="text" class="form-control" id="respostaB_texto" placeholder="Digite a resposta B" required>
          </div>

          <div class="mb-3">
            <label for="respostaC_texto" class="form-label">Resposta C</label>
            <input type="text" class="form-control" id="respostaC_texto" placeholder="Digite a resposta C" required>
          </div>

          <div class="mb-3">
            <label for="respostaD_texto" class="form-label">Resposta D</label>
            <input type="text" class="form-control" id="respostaD_texto" placeholder="Digite a resposta D" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Selecione a Resposta Correta</label><br>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="resposta" id="respostaA" value="0">
              <label class="form-check-label" for="respostaA">Resposta A</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="resposta" id="respostaB" value="1">
              <label class="form-check-label" for="respostaB">Resposta B</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="resposta" id="respostaC" value="2">
              <label class="form-check-label" for="respostaC">Resposta C</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="resposta" id="respostaD" value="3">
              <label class="form-check-label" for="respostaD">Resposta D</label>
            </div>
          </div>

          <!-- Campo oculto para o ID do Curso -->
          <input type="hidden" id="idCurso" value="1">

          <button type="button" id="salvarPergunta" class="btn btn-success btn-lg w-100">Salvar Pergunta</button>
        </form>
      </div>
    </div>
  </div>
</div>

        <!-- Botão Flutuante -->
        <div class="floating-button" data-bs-toggle="modal" data-bs-target="#modalPerguntas">
            <i class="bi bi-plus"></i>
        </div>

        <!-- Tooltip -->
        <div class="tooltip-message">Criar Perguntas/Respostas</div>
    </div>
</main>

<?php
require_once __DIR__ . '/../aplicacao/templates/rodape.php';
?>