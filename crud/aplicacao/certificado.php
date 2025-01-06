<?php
require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-curso.php';

// Verificar se o ID do curso foi fornecido
if (!isset($_GET['curso_id'])) {
    die("ID do curso não especificado.");
}

session_start(); // Certifique-se de que a sessão está iniciada
if (!isset($_SESSION['id'])) {
    die("Usuário não autenticado.");
}

$cursoId = $_GET['curso_id'];
$usuarioId = $_SESSION['id'];

// Função para verificar a conclusão do curso
function verificarConclusao($usuarioId, $cursoId) {
    global $pdo;
    $sql = "SELECT * FROM conclusoes WHERE usuario_id = :usuario_id AND curso_id = :curso_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
    $stmt->bindParam(':curso_id', $cursoId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$conclusao = verificarConclusao($usuarioId, $cursoId);
if (!$conclusao) {
    die("Você não concluiu este curso.");
}

// Obter detalhes do curso
$curso = lerCursoPorId($pdo, $cursoId);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Conclusão</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8f9fa;
            padding: 50px;
        }
        .certificado-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            background: url('/Image/Certificado.png') no-repeat center;
            background-size: cover;
            padding: 50px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 600px;
            color: #333;
        }
        .certificado-texto {
            position: relative;
            top: 150px;
        }
        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2rem;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="certificado-container">
        <div class="certificado-texto">
            <h2>Certificado de Conclusão</h2>
            <p>Certificamos que o aluno concluiu com êxito o curso:</p>
            <p><strong>Curso:</strong> <?= htmlspecialchars($curso['titulo']) ?></p>
            <p><strong>Instrutor:</strong> <?= htmlspecialchars($curso['instrutor']) ?></p>
            <p><strong>Data de Conclusão:</strong> <?= htmlspecialchars($conclusao['data_conclusao']) ?></p>
        </div>
    </div>
</body>
</html>
