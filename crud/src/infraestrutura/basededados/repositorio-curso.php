<?php
// INSERE DADOS DA CONEXÃO COM O PDO
require_once __DIR__ . '/criar-conexao.php';

/**
 * Verifica se a função 'criarCurso' já foi definida antes de declará-la.
 */
if (!function_exists('criarCurso')) {
    /**
     * FUNÇÃO RESPONSÁVEL POR CRIAR UM NOVO CURSO
     */
    function criarCurso($pdo, $dados) {
        // Verificar se o campo 'titulo' existe nos dados
        if (empty($dados['titulo'])) {
            throw new Exception('Campo obrigatório ausente: titulo');
        }
        
        // Verificar outros campos obrigatórios
        $camposObrigatorios = [
            'titulo', 
            'instrutor', 
            'categoria', 
            'codigo_do_curso', 
            'duracao_em_horas', 
            'ano_de_lancamento', 
            'imagem_do_curso', 
            'descricao'
        ];

        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                throw new Exception("Campo obrigatório ausente: $campo");
            }
        }

        // Se todos os campos obrigatórios estiverem presentes, prossegue com a inserção
        $sqlCreate = "INSERT INTO cursos (
            titulo, 
            instrutor, 
            categoria, 
            codigo_do_curso, 
            duracao_em_horas, 
            ano_de_lancamento, 
            imagem_do_curso, 
            descricao
        ) VALUES (
            :titulo, 
            :instrutor, 
            :categoria, 
            :codigo_do_curso, 
            :duracao_em_horas, 
            :ano_de_lancamento, 
            :imagem_do_curso, 
            :descricao
        )";

        try {
            $PDOStatement = $pdo->prepare($sqlCreate);
            $sucesso = $PDOStatement->execute([ 
                ':titulo' => $dados['titulo'],
                ':instrutor' => $dados['instrutor'],
                ':categoria' => $dados['categoria'],
                ':codigo_do_curso' => $dados['codigo_do_curso'],
                ':duracao_em_horas' => $dados['duracao_em_horas'],
                ':ano_de_lancamento' => $dados['ano_de_lancamento'],
                ':imagem_do_curso' => $dados['imagem_do_curso'],
                ':descricao' => $dados['descricao']
            ]);

            if ($sucesso) {
                // Recupera o ID do último curso inserido
                $dados['id'] = $pdo->lastInsertId();
            }

            return $sucesso;
        } catch (PDOException $e) {
            echo "Erro na execução do SQL: " . $e->getMessage();
            return false;
        }
    }
}

/**
 * Verifica se a função 'lerTodosCurso' já foi definida antes de declará-la.
 */
if (!function_exists('lerTodosCurso')) {
    /**
     * FUNÇÃO PARA CARREGAR TODOS OS CURSOS
     */
    function lerTodosCurso($pdo) {
        // Cria uma consulta SQL para recuperar todos os cursos
        $sql = 'SELECT * FROM cursos'; // Altere o nome da tabela conforme necessário
        $stmt = $pdo->prepare($sql);
        $stmt->execute();  // Executa a consulta no banco de dados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna todos os cursos encontrados
    }
}

/**
 * Verifica se a função 'lerCursoPorId' já foi definida antes de declará-la.
 */
if (!function_exists('lerCursoPorId')) {
    function lerCursoPorId($pdo, $id) {
        try {
            $sql = 'SELECT * FROM cursos WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro ao buscar curso: " . $e->getMessage());
        }
    }
}

/**
 * Verifica se a função 'inscreverUsuarioNoCurso' já foi definida antes de declará-la.
 */
if (!function_exists('inscreverUsuarioNoCurso')) {
    function inscreverUsuarioNoCurso($usuarioId, $cursoId) {
        global $pdo;

        $sql = "INSERT INTO inscricoes (usuario_id, curso_id) VALUES (:usuario_id, :curso_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':curso_id', $cursoId);
        return $stmt->execute();
    }

    function desinscreverUsuarioDoCurso($usuarioId, $cursoId) {
        global $pdo;
    
        $sql = "DELETE FROM inscricoes WHERE usuario_id = :usuario_id AND curso_id = :curso_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':curso_id', $cursoId);
        return $stmt->execute();
    }
}

/**
 * Função para listar cursos
 */
function listarCursos() {
    global $pdo; // Usa a conexão global com o banco de dados

    try {
        // Consulta SQL para buscar todos os cursos
        $sql = "SELECT id, titulo FROM cursos";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Retorna os cursos como array associativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao listar cursos: " . $e->getMessage());
    }
}

/**
 * Função para criar pergunta e resposta no banco de dados
 */
if (!function_exists('criarPerguntaEResposta')) {
    function criarPerguntaEResposta($pdo, $cursoId, $pergunta, $respostas, $respostaCorreta) {
        // Inserir a pergunta no banco de dados
        $sqlPergunta = "INSERT INTO perguntas (curso_id, titulo) VALUES (:curso_id, :titulo)";
        $stmtPergunta = $pdo->prepare($sqlPergunta);
        $stmtPergunta->execute([
            ':curso_id' => $cursoId,
            ':titulo' => $pergunta
        ]);

        // Pega o ID da pergunta inserida
        $perguntaId = $pdo->lastInsertId();

        // Inserir as respostas
        $sqlResposta = "INSERT INTO respostas (pergunta_id, texto, correta) VALUES (:pergunta_id, :texto, :correta)";
        $stmtResposta = $pdo->prepare($sqlResposta);

        foreach ($respostas as $index => $resposta) {
            $stmtResposta->execute([
                ':pergunta_id' => $perguntaId,
                ':texto' => $resposta,
                ':correta' => ($index == $respostaCorreta) ? 1 : 0
            ]);
        }

        return true;
    }
}

/**
 * Função para ler todas as perguntas e respostas de um curso
 */
if (!function_exists('lerPerguntasERespostas')) {
    function lerPerguntasERespostas($pdo, $cursoId) {
        $sql = "SELECT * FROM perguntas p 
                JOIN respostas r ON p.id = r.pergunta_id 
                WHERE p.curso_id = :curso_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':curso_id', $cursoId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
