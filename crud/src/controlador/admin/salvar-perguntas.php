<?php
session_start();
require_once __DIR__ . '/../../infraestrutura/basededados/criar-conexao.php';

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.']);
    exit();
}

try {
    // Recebe os dados enviados via POST
    $tituloPergunta = $_POST['tituloPergunta'] ?? null;
    $respostaA = $_POST['respostaA'] ?? null;
    $respostaB = $_POST['respostaB'] ?? null;
    $respostaC = $_POST['respostaC'] ?? null;
    $respostaD = $_POST['respostaD'] ?? null;
    $respostaCorreta = $_POST['respostaCorreta'] ?? null;
    $idCurso = $_POST['idCurso'] ?? null;

    // Validações básicas
    if (!$tituloPergunta || !$respostaA || !$respostaB || !$respostaC || !$respostaD || !$respostaCorreta || !$idCurso) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios.']);
        exit();
    }

    // Inicia a transação
    $pdo->beginTransaction();

    // Inserir a pergunta no banco de dados
    $sqlPergunta = "INSERT INTO perguntas (titulo, idCurso) VALUES (:titulo, :idCurso)";
    $stmtPergunta = $pdo->prepare($sqlPergunta);
    $stmtPergunta->execute([
        ':titulo' => $tituloPergunta,
        ':idCurso' => $idCurso,
    ]);

    // Obter o ID da pergunta recém-criada
    $idPergunta = $pdo->lastInsertId();

    // Inserir as respostas no banco de dados
    $respostas = [
        'A' => $respostaA,
        'B' => $respostaB,
        'C' => $respostaC,
        'D' => $respostaD,
    ];

    foreach ($respostas as $letra => $texto) {
        $sqlResposta = "INSERT INTO respostas (pergunta_id, letra, texto_resposta) VALUES (:pergunta_id, :letra, :texto_resposta)";
        $stmtResposta = $pdo->prepare($sqlResposta);
        $stmtResposta->execute([
            ':pergunta_id' => $idPergunta,
            ':letra' => $letra,
            ':texto_resposta' => $texto,
        ]);
    }

    // Atualizar a resposta correta na tabela de perguntas
    $sqlCorreta = "UPDATE perguntas SET respostaCorreta = :respostaCorreta WHERE id = :idPergunta";
    $stmtCorreta = $pdo->prepare($sqlCorreta);
    $stmtCorreta->execute([
        ':respostaCorreta' => $respostaCorreta,
        ':idPergunta' => $idPergunta,
    ]);

    // Confirma a transação
    $pdo->commit();

    echo json_encode(['sucesso' => true, 'mensagem' => 'Pergunta e respostas salvas com sucesso!']);
} catch (Exception $e) {
    // Reverte a transação em caso de erro
    $pdo->rollBack();
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar a pergunta: ' . $e->getMessage()]);
}
?>
