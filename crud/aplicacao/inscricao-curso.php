<?php
session_start(); // Certifique-se de que a sessão esteja iniciada

require_once __DIR__ . '/../src/middleware/middleware-utilizador.php';
require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-curso.php';

$mensagemErro = ''; // Variável para armazenar mensagens de erro

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscrever'])) {
    if (isset($_SESSION['id'])) {
        $cursoId = $_POST['curso_id']; // ID do curso a ser inscrito
        $usuarioId = $_SESSION['id']; // ID do usuário logado

        // Função para inscrever o usuário no curso
        if (inscreverUsuarioNoCurso($usuarioId, $cursoId)) {
            // Redirecionar para a página de perfil com sucesso
            header('Location: /aplicacao/perfil.php');
            exit();
        } else {
            $mensagemErro = 'Houve um erro ao tentar se inscrever. Tente novamente mais tarde.';
        }
    } else {
        $mensagemErro = 'Você precisa estar logado para se inscrever.';
    }
}

// Aqui você deve carregar os detalhes do curso que está sendo visualizado
$cursoId = $_GET['id'] ?? null; // Garantir que o ID seja válido
if ($cursoId) {
    $curso = lerCursoPorId($pdo, $cursoId);
} else {
    $mensagemErro = 'Curso não encontrado.';
    // Exibir uma página de erro ou redirecionar para outra página
    header('Location: /aplicacao/cursos.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrição no Curso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            font-size: 2em;
        }
        p {
            color: #666;
            font-size: 1.2em;
            line-height: 1.5;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            cursor: pointer;
            background-color: #28a745;
            color: white;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        .btn:active {
            transform: scale(1);
        }
        .error {
            color: #dc3545;
            font-size: 1.2em;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #dc3545;
            border-radius: 5px;
            background-color: #f8d7da;
            display: flex;
            align-items: center;
        }
        .error i {
            margin-right: 10px;
        }
        .success {
            color: #28a745;
            font-size: 1.2em;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #28a745;
            border-radius: 5px;
            background-color: #d4edda;
            display: flex;
            align-items: center;
        }
        .success i {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><?= htmlspecialchars($curso['titulo']) ?></h1>
    <p><?= htmlspecialchars($curso['descricao']) ?></p>

    <?php if ($mensagemErro): ?>
        <div class="error">
            <i class="fas fa-exclamation-triangle"></i>
            <?= htmlspecialchars($mensagemErro) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="curso_id" value="<?= htmlspecialchars($cursoId) ?>">
        <button type="submit" name="inscrever" class="btn">Inscrever-se <i class="fas fa-check"></i></button>
    </form>
</div>

</body>
</html>
