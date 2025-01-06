<?php
// iniciar-curso.php

// Iniciar a sessão e incluir os arquivos essenciais
session_start();
require_once __DIR__ . '/../src/infraestrutura/basededados/criar-conexao.php';
require_once __DIR__ . '/../src/middleware/middleware-autenticado.php';
include __DIR__ . '/../aplicacao/templates/cabecalho.php';
include __DIR__ . '/../aplicacao/templates/navbar.php';

// Verificar se o ID do curso foi fornecido
if (!isset($_GET['curso_id'])) {
    echo "<div class='content-wrapper'><p>Curso não encontrado. Certifique-se de que o ID do curso foi fornecido.</p></div>";
    include __DIR__ . '/../aplicacao/templates/rodape.php';
    exit();
}

$curso_id = intval($_GET['curso_id']);
$conexao = criarConexao();

// Consultar informações do curso
$query = $conexao->prepare('SELECT * FROM cursos WHERE id = :id');
$query->bindParam(':id', $curso_id, PDO::PARAM_INT);
$query->execute();
$curso = $query->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    echo "<div class='content-wrapper'><p>Curso não encontrado ou indisponível.</p></div>";
    include __DIR__ . '/../aplicacao/templates/rodape.php';
    exit();
}

// Registrar conclusão do curso no banco de dados
try {
    $usuario_id = $_SESSION['id'];
    
    // Verificar se o curso já foi concluído
    $verificarQuery = $conexao->prepare(
        'SELECT * FROM conclusoes WHERE usuario_id = :usuario_id AND curso_id = :curso_id'
    );
    $verificarQuery->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $verificarQuery->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
    $verificarQuery->execute();
    $conclusao = $verificarQuery->fetch(PDO::FETCH_ASSOC);

    if (!$conclusao) {
        // Inserir a conclusão no banco de dados
        $inserirQuery = $conexao->prepare(
            'INSERT INTO conclusoes (usuario_id, curso_id, data_conclusao) VALUES (:usuario_id, :curso_id, CURRENT_TIMESTAMP)'
        );
        $inserirQuery->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $inserirQuery->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
        $inserirQuery->execute();
    }

    // Redirecionar para a página de perfil com mensagem de sucesso
    $_SESSION['sucesso'] = "Curso '{$curso['nome']}' concluído com sucesso!";
    header('Location: /aplicacao/perfil.php');
    exit();
} catch (Exception $e) {
    $_SESSION['erro'] = "Erro ao registrar conclusão do curso: " . $e->getMessage();
    header('Location: /aplicacao/perfil.php');
    exit();
}

?>

<div class="content-wrapper">
    <h1>Iniciar Curso: <?= htmlspecialchars($curso['nome'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p><?= htmlspecialchars($curso['descricao'], ENT_QUOTES, 'UTF-8') ?></p>
</div>

<?php
// Rodapé da página
include __DIR__ . '/../aplicacao/templates/rodape.php';
?>
<link rel="stylesheet" href="/../recursos/css/iniciar-curso.css">
