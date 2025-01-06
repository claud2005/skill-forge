<?php
# CARREGA MIDDLEWARE PARA GARANTIR QUE APENAS UTILIZADORES AUTENTICADOS ACESSEM ESTE SÍTIO
require_once __DIR__ . '/../src/middleware/middleware-utilizador.php';

# CARREGA O CABECALHO PADRÃO COM O TÍTULO
$titulo = ' - Perfil';
include_once __DIR__ . '/templates/cabecalho.php';
include_once __DIR__ . '/templates/navbar.php';

# ACESSA FUNÇÕES AUXILIARES.
@require_once __DIR__ . '/../src/auxiliadores/auxiliador.php';
$utilizador = utilizador();

# FUNÇÃO PARA BUSCAR CURSOS INSCRITOS
function buscarCursosInscritos($usuarioId) {
    global $pdo;

    $sql = "SELECT cursos.* FROM cursos
            JOIN inscricoes ON cursos.id = inscricoes.curso_id
            WHERE inscricoes.usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuarioId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

# FUNÇÃO PARA VERIFICAR CONCLUSÃO DE UM CURSO
function verificarConclusao($usuarioId, $cursoId) {
    global $pdo;
    $sql = "SELECT * FROM conclusoes WHERE usuario_id = :usuario_id AND curso_id = :curso_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuarioId);
    $stmt->bindParam(':curso_id', $cursoId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$cursosInscritos = buscarCursosInscritos($utilizador['id']);
?>

<main class="container-xl mt-5 px-4">
    <!-- Estilos personalizados -->
    <style>
        .list-group-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .list-group-item strong {
            flex: 1;
            text-align: left;
        }

        .list-group-item form,
        .list-group-item a {
            flex: 0 0 auto;
        }
    </style>

    <!-- Seção de mensagens -->
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
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            unset($_SESSION['erros']);
        }
        ?>
    </section>

    <!-- Perfil principal -->
    <div class="row g-5">
        <!-- Coluna Esquerda (Foto de Perfil) -->
        <aside class="col-lg-4">
            <div class="card border-0 shadow-sm" style="background: #f8f9fa; border-radius: 15px;">
                <div class="card-body text-center p-5">
                    <div class="profile-photo mb-4">
                        <?php if (isset($utilizador['foto']) && !empty($utilizador['foto'])): ?>
                            <img class="rounded-circle img-fluid" src="/Image/ImageDoUtilizador/<?= $utilizador['foto'] ?>" alt="Perfil" style="height: 150px; width: 150px; object-fit: cover;" />
                        <?php else: ?>
                            <img class="rounded-circle img-fluid" src="/Image/perfil-de-usuario.png" alt="Perfil" style="height: 150px; width: 150px; object-fit: cover;" />
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: #333;"> <?= $utilizador['nome'] . " " . $utilizador['apelido'] ?> </h5>
                    <p class="text-muted mb-3"> <?= $utilizador['email'] ?> </p>
                    <a href="/aplicacao/palavra-passe.php" class="btn btn-outline-warning rounded-pill px-4">Alterar Palavra Passe</a>
                </div>
            </div>
        </aside>

        <!-- Coluna Direita (Formulário de Perfil) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="background: #fff; border-radius: 15px;">
                <div class="card-header text-center" style="background: #fff; border-bottom: none;">
                    <h5 class="fw-bold" style="color: #333;">Editar Perfil</h5>
                </div>
                <div class="card-body p-5">
                    <form enctype="multipart/form-data" action="/src/controlador/admin/controlar-utilizador.php" method="post">
                        <!-- Nome e Apelido na mesma linha -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="nome" class="form-label" style="color: #555;">Nome</label>
                                <input type="text" class="form-control rounded-pill" name="nome" placeholder="Nome" maxlength="100" value="<?= isset($_REQUEST['nome']) ? $_REQUEST['nome'] : $utilizador['nome'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="apelido" class="form-label" style="color: #555;">Apelido</label>
                                <input type="text" class="form-control rounded-pill" name="apelido" maxlength="100" value="<?= isset($_REQUEST['apelido']) ? $_REQUEST['apelido'] : $utilizador['apelido'] ?>" required>
                            </div>
                        </div>
                        <!-- NIF e Telemóvel na mesma linha -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="nif" class="form-label" style="color: #555;">NIF</label>
                                <input type="tel" class="form-control rounded-pill" name="nif" maxlength="9" value="<?= isset($_REQUEST['nif']) ? $_REQUEST['nif'] : $utilizador['nif'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="telemovel" class="form-label" style="color: #555;">Nº Telemóvel</label>
                                <input type="tel" class="form-control rounded-pill" name="telemovel" maxlength="9" value="<?= isset($_REQUEST['telemovel']) ? $_REQUEST['telemovel'] : $utilizador['telemovel'] ?>" required>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label" style="color: #555;">Email</label>
                            <input type="email" class="form-control rounded-pill" name="email" maxlength="255" value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : $utilizador['email'] ?>" required>
                        </div>
                        <!-- Foto de Perfil -->
                        <div class="mb-4">
                            <label for="inputGroupFile01" class="form-label" style="color: #555;">Alterar Foto de Perfil</label>
                            <input accept="image/*" type="file" class="form-control rounded-pill" id="inputGroupFile01" name="foto">
                        </div>
                        <!-- Botão Salvar -->
                        <div class="text-center">
                            <button class="btn btn-warning btn-lg rounded-pill px-5" type="submit" name="utilizador" value="perfil">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Cursos Inscritos -->
    <div class="row g-5 mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: #fff; border-radius: 15px;">
                <div class="card-header text-center" style="background: #fff; border-bottom: none;">
                    <h5 class="fw-bold" style="color: #333;">Cursos Inscritos</h5>
                </div>
                <div class="card-body p-5">
                    <?php if (count($cursosInscritos) > 0): ?>
                        <ul class="list-group">
                            <?php foreach ($cursosInscritos as $curso): ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($curso['titulo']) ?></strong>
                                    <span>- <?= htmlspecialchars($curso['categoria']) ?></span>
                                    <form action="/src/controlador/admin/controlar-curso.php" method="post">
                                        <input type="hidden" name="curso_id" value="<?= $curso['id'] ?>">
                                        <input type="hidden" name="usuario_id" value="<?= $utilizador['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="acao" value="desinscrever">Desinscrever</button>
                                    </form>
                                    <a href="/aplicacao/detalhes-curso.php?id=<?= $curso['id'] ?>" class="btn btn-link">Ver Detalhes</a>
                                    <?php
                                    $conclusao = verificarConclusao($utilizador['id'], $curso['id']);
                                    if ($conclusao): ?>
                                        <a href="/aplicacao/certificado.php?curso_id=<?= $curso['id'] ?>" class="btn btn-primary btn-sm">Certificado</a>
                                        <img src="/Image/Certificado.png" alt="Certificado" style="width: 100px; height: auto; margin-left: 10px;">
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Você ainda não está inscrito em nenhum curso.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once __DIR__ . '/templates/rodape.php';
?>
