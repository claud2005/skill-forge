<?php
// Requisição do middleware para garantir acesso restrito
require_once __DIR__ . '/../src/middleware/middleware-administrador.php';

// Inicialização do repositório de cursos
require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-curso.php';

// Carregamento de todos os cursos
$cursos = lerTodosCurso($pdo);

// Carregamento do cabeçalho padrão com o título
$titulo = ' - Administração de Cursos';
require_once __DIR__ . '/../aplicacao/templates/cabecalho.php';
?>
   <a href="/admin/" class="btn btn-info"><i class="bi bi-arrow-left"></i> Voltar ao Painel</a>
            </div>
            <div class="col text-center">
                <h1>Administração de Cursos</h1>
            </div>
        </div>

        <?php
        // Exibição das mensagens de sucesso e erro vindas do controlador-curso
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

        <section>
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">Título</th>
                            <th scope="col">Instrutor</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Código do Curso</th>
                            <th scope="col">Duração (em horas)</th>
                            <th scope="col">Ano de Lançamento</th>
                            <th scope="col">Imagem do Curso</th>
                            <th scope="col">Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos as $curso) : ?>
                            <tr>
                                <td><?= $curso['titulo'] ?></td>
                                <td><?= $curso['instrutor'] ?></td>
                                <td><?= $curso['categoria'] ?></td>
                                <td><?= $curso['Código do Curso'] ?></td>
                                <td><?= $curso['Ano de lançamento'] ?></td>
                                <td><?= $curso['Duração (em horas)'] ?></td>
                                <td>
                                    <?php if (!empty($curso['imagem_do_curso'])) : ?>
                                        <img src="/../image/capas<?= $curso['imagem_do_curso'] ?>" alt="<?= $curso['titulo'] ?>" class="img-thumbnail" style="max-width: 100px;">
                                    <?php else : ?>
                                        <span class="text-muted">Sem imagem</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $curso['descricao'] ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin/curso.php?id=<?= $curso['id'] ?>" class="btn btn-primary">Editar</a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletar<?= $manga['id'] ?>">Deletar</button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de Confirmação de Deleção -->
                            <div class="modal fade" id="deletar<?= $curso['id'] ?>" tabindex="-1" aria-labelledby="deletarModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deletarModalLabel">Confirmar Deleção</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja deletar o curso <?= $curso['titulo'] ?>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <a href="/../src/controlador/admin/controlar-curso.php?curso=deletar&id=<?= $curso['id'] ?>" class="btn btn-danger">Confirmar Deleção</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fim Modal -->
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<?php
// Carregamento do rodapé padrão
require_once __DIR__ . '/../aplicacao/templates/rodape.php';
?>
