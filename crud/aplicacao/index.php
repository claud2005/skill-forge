<?php
# CARREGA MIDDLEWARE PARA GARANTIR QUE APENAS UTILIZADORES AUTENTICADOS ACESSEM ESTE SÍTIO
require_once __DIR__ . '/../src/middleware/middleware-utilizador.php';

# ACESSA DE FUNÇÕES AUXILIADORAS. 
@require_once __DIR__ . '/../src/auxiliadores/auxiliador.php';

# PROVENIENTE DE FUNÇÕES AUXILIADORAS. CARREGA O UTILIZADOR ATUAL
$utilizador = utilizador();

# INCLUI O ARQUIVO DE CONEXÃO COM O BANCO DE DADOS
require_once __DIR__ . '/../src/infraestrutura/basededados/criar-conexao.php';

# CARREGA O CABECALHO PADRÃO COM O TÍTULO
$titulo = '- Aplicação';
include_once __DIR__ . '/templates/cabecalho.php';
include_once __DIR__ . '/templates/navbar.php';


# INICIALIZA O REPOSITÓRIO E CARREGA TODOS OS CURSOS
require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-curso.php';

# Verificar se há um termo de pesquisa
$termoPesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

// Se o termo de pesquisa foi fornecido, filtra os cursos
if ($termoPesquisa) {
    $cursos = filtrarCursos($pdo, $termoPesquisa); // Função que filtra os cursos
} else {
    $cursos = lerTodosCurso($pdo); // Carrega todos os cursos
}
?>

<link rel="stylesheet" href="/../recursos/css/index.css">

<body class="bg-light">
    <main class="container py-5">
        <!-- Mensagens de Sucesso e Erro -->
        <section>
            <?php
            if (isset($_SESSION['sucesso'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                echo htmlspecialchars($_SESSION['sucesso']) . '<br>';
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                unset($_SESSION['sucesso']);
            }

            if (isset($_SESSION['erros'])) {
                if (is_array($_SESSION['erros']) && !empty($_SESSION['erros'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                    foreach ($_SESSION['erros'] as $erro) {
                        echo htmlspecialchars($erro) . '<br>';
                    }
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                    echo htmlspecialchars($_SESSION['erros']) . '<br>';
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
                unset($_SESSION['erros']);
            }
            ?>
        </section>

        <!-- Listagem de Cursos -->
        <section>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                if (is_array($cursos) && count($cursos) > 0) {
                    foreach ($cursos as $curso) {
                ?>
                    <div class="col">
                        <div class="card h-100 shadow-lg rounded-5 border-0 overflow-hidden" style="transition: transform 0.3s ease-in-out;">
                            <img src="/Image/ImagemDoCurso/<?= htmlspecialchars($curso['imagem_do_curso']) ?>" class="card-img-top rounded-5" alt="<?= htmlspecialchars($curso['titulo']) ?>" style="object-fit: cover; height: 250px;">
                            <div class="card-body text-center p-4">
                                <h5 class="card-title mb-3 text-truncate" style="font-size: 1.25rem;"><?= htmlspecialchars($curso['titulo']) ?></h5>
                                <a href="/aplicacao/detalhes-curso.php?id=<?= $curso['id'] ?>" class="btn btn-primary w-100 rounded-pill mt-3" style="font-size: 1rem; padding: 12px;">Ver mais</a>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                } else {
                    echo '<div class="col-12"><p class="alert alert-warning text-center">Nenhum curso encontrado.</p></div>';
                }
                ?>
            </div>
        </section>
    </main>
</body>

<?php
include_once __DIR__ . '/templates/rodape.php';
?>

<?php
// Função de filtragem
function filtrarCursos($pdo, $termo) {
    $sql = "SELECT * FROM cursos WHERE LOWER(titulo) LIKE LOWER(:termo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['termo' => "%" . $termo . "%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

