<?php
# CARREGA MIDDLEWARE PARA GARANTIR QUE APENAS UTILIZADORES AUTENTICADOS ACESSEM ESTE SÍTIO
require_once __DIR__ . '/../src/middleware/middleware-utilizador.php';
# ACESSA DE FUNÇÕES AUXILIADORAS.
require_once __DIR__ . '/../src/auxiliadores/auxiliador.php';
# CARREGA O CABECALHO PADRÃO COM O TÍTULO
$titulo = '- Detalhes do Curso';
include_once __DIR__ . '/templates/cabecalho.php';
include_once __DIR__ . '/templates/navbar.php';
# INICIALIZA O REPOSITÓRIO
require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-curso.php';

# Função para verificar se a tabela conclusoes existe e criar se necessário
function criarTabelaConclusoes($pdo) {
    $sqlVerificar = "SELECT name FROM sqlite_master WHERE type='table' AND name='conclusoes'";
    $stmt = $pdo->prepare($sqlVerificar);
    $stmt->execute();

    if ($stmt->fetch() === false) {
        $sqlCriar = "CREATE TABLE IF NOT EXISTS conclusoes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            usuario_id INTEGER NOT NULL,
            curso_id INTEGER NOT NULL,
            data_conclusao DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
            FOREIGN KEY (curso_id) REFERENCES cursos(id)
        )";
        $pdo->exec($sqlCriar);
    }
}

# Chama a função para garantir que a tabela 'conclusoes' exista
criarTabelaConclusoes($pdo);

# Função para verificar se o ID foi passado via GET
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    die("ID do curso não especificado.");
}

# CARREGA OS DETALHES DO CURSO ESPECÍFICO
$curso = lerCursoPorId($pdo, $id);
if (!$curso) {
    die("Curso não encontrado para o ID especificado.");
}

# Verifica se o usuário já está inscrito no curso
$usuarioId = $_SESSION['id'];
$inscrito = verificarInscricao($usuarioId, $curso['id']);

# Função para verificar se o usuário está inscrito no curso
function verificarInscricao($usuarioId, $cursoId) {
    global $pdo;
    $sql = "SELECT * FROM inscricoes WHERE usuario_id = :usuario_id AND curso_id = :curso_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuarioId);
    $stmt->bindParam(':curso_id', $cursoId);
    $stmt->execute();
    return $stmt->fetch() !== false; // Retorna true se inscrito, false caso contrário
}

# Função para verificar se o usuário concluiu o curso
function verificarConclusao($usuarioId, $cursoId) {
    global $pdo;
    $sql = "SELECT * FROM conclusoes WHERE usuario_id = :usuario_id AND curso_id = :curso_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuarioId);
    $stmt->bindParam(':curso_id', $cursoId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

# Chama a função para verificar a conclusão do curso
$conclusao = verificarConclusao($usuarioId, $curso['id']);
?>

<!-- Código HTML para exibir os detalhes do curso, o formulário de inscrição e a verificação do certificado -->
<main class="bg-light py-5">
  <section>
    <div class="container">
      <div class="row align-items-center">
        <!-- Coluna da Imagem -->
        <div class="col-md-5 mb-4">
          <div class="card border-0 shadow-lg rounded-3">
            <img src="/image/ImagemDoCurso/<?= htmlspecialchars($curso['imagem_do_curso']) ?>" class="card-img-top rounded-3" alt="<?= htmlspecialchars($curso['titulo']) ?>" style="object-fit: cover; height: 350px;">
          </div>
        </div>

        <!-- Coluna dos Detalhes -->
        <div class="col-md-7">
          <div class="bg-white p-4 rounded-4 shadow-lg border-0">
            <h2 class="text-primary fw-bold mb-4"><?= htmlspecialchars($curso['titulo'] ?? 'Título não disponível') ?></h2>
            
            <div class="mb-4">
              <p><strong><i class="bi bi-person-fill"></i> <span class="text-muted">Instrutor:</span></strong> <?= htmlspecialchars($curso['instrutor'] ?? 'Instrutor não disponível') ?></p>
              <p><strong><i class="bi bi-briefcase-fill"></i> <span class="text-muted">Categoria:</span></strong> <?= htmlspecialchars($curso['categoria'] ?? 'Categoria não disponível') ?></p>
              <p><strong><i class="bi bi-file-earmark-text-fill"></i> <span class="text-muted">Código do Curso:</span></strong> <?= htmlspecialchars($curso['codigo_do_curso'] ?? 'Código não disponível') ?></p>
              <p><strong><i class="bi bi-clock-fill"></i> <span class="text-muted">Duração:</span></strong> <?= htmlspecialchars($curso['duracao_em_horas'] ?? 'Duração não disponível') ?> horas</p>
              <p><strong><i class="bi bi-calendar-check-fill"></i> <span class="text-muted">Ano de Lançamento:</span></strong> <?= htmlspecialchars($curso['ano_de_lancamento'] ?? 'Ano não disponível') ?></p>
            </div>

            <div class="mb-4">
              <p><strong><i class="bi bi-info-circle-fill"></i> <span class="text-muted">Descrição:</span></strong> <?= nl2br(htmlspecialchars($curso['descricao'] ?? 'Descrição não disponível')) ?></p>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-center mt-5">
              <a href="/index.php" class="btn btn-primary rounded-pill me-2">Voltar</a>
              <?php if (!$inscrito): ?>
                <a href="/aplicacao/inscricao-curso.php?id=<?= $curso['id'] ?>" class="btn btn-success rounded-pill">Inscrever</a>
              <?php else: ?>
                <a href="/aplicacao/iniciar-curso.php?id=<?= $curso['id'] ?>" class="btn btn-success rounded-pill me-2">Iniciar</a>
                <button class="btn btn-secondary rounded-pill" disabled>Já Inscrito</button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Verificar se o curso foi concluído -->
<?php if ($conclusao): ?>
    <div class="alert alert-success mt-4">
        <h5><i class="bi bi-file-earmark-check-fill"></i> Certificado Disponível</h5>
        <p>Você concluiu o curso! <a href="/aplicacao/certificado.php?curso_id=<?= $curso['id'] ?>" class="btn btn-primary">Visualizar Certificado</a></p>
    </div>
<?php endif; ?>

<?php
include_once __DIR__ . '/templates/rodape.php';
?>