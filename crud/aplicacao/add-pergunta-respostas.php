<?php
require_once '/../src/infraestruturas/basededados/criar-conexao.php'; // Arquivo que conecta ao banco de dados

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber os dados do JavaScript
    $tituloPergunta = $_POST['tituloPergunta'];
    $respostaCorreta = $_POST['respostaCorreta'];
    $respostas = json_decode($_POST['respostas']);  // Recebe as respostas em formato JSON
    $cursoId = $_POST['idCurso'];

    // Iniciar transação
    $pdo->beginTransaction();

    try {
        // Inserir a pergunta no banco
        $stmt = $pdo->prepare("INSERT INTO perguntas (curso_id, titulo_pergunta, resposta_correta) VALUES (?, ?, ?)");
        $stmt->execute([$cursoId, $tituloPergunta, $respostaCorreta]);

        // Obter o ID da pergunta recém-criada
        $perguntaId = $pdo->lastInsertId();

        // Inserir as respostas no banco
        foreach ($respostas as $index => $respostaTexto) {
            $stmt = $pdo->prepare("INSERT INTO respostas (pergunta_id, texto_resposta) VALUES (?, ?)");
            $stmt->execute([$perguntaId, $respostaTexto]);
        }

        // Confirmar transação
        $pdo->commit();

        // Retornar resposta de sucesso
        echo json_encode(['status' => 'success', 'message' => 'Pergunta e respostas salvas com sucesso!']);
    } catch (Exception $e) {
        // Reverter transação em caso de erro
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar pergunta e respostas.']);
    }
}
?>