<?php

// Verifica se a função 'cursoValido' já foi definida antes de declará-la
if (!function_exists('cursoValido')) {
    function cursoValido($requisicao)
    {
        $erros = [];

        // Remover espaços extras de cada valor de $requisicao
        foreach ($requisicao as $key => $value) {
            $requisicao[$key] = trim($value);
        }

        // Validando o campo Título
        if (empty($requisicao['titulo'])) {
            $erros['titulo'] = 'O campo título é obrigatório.';
        }

        // Validando o campo Instrutor
        if (empty($requisicao['instrutor']) || strlen($requisicao['instrutor']) < 3 || strlen($requisicao['instrutor']) > 255) {
            $erros['instrutor'] = 'O campo Instrutor não pode estar vazio e deve ter entre 3 e 255 caracteres.';
        }

        // Validando o campo Categoria
        if (empty($requisicao['categoria']) || strlen($requisicao['categoria']) < 3 || strlen($requisicao['categoria']) > 255) {
            $erros['categoria'] = 'O campo Categoria não pode estar vazio e deve ter entre 3 e 255 caracteres.';
        }

        // Validando o campo Código do Curso
        if (empty($requisicao['codigo_do_curso']) || strlen($requisicao['codigo_do_curso']) < 2 || strlen($requisicao['codigo_do_curso']) > 50) {
            $erros['codigo'] = 'O campo Código do Curso não pode estar vazio e deve ter entre 2 e 50 caracteres.';
        }

        // Validando o campo Duração
        if (!filter_var($requisicao['duracao_em_horas'], FILTER_VALIDATE_INT) || $requisicao['duracao_em_horas'] <= 0) {
            $erros['duracao_em_horas'] = 'O campo Duração deve ser um número inteiro positivo.';
        }

        // Validando o campo Ano de Lançamento
        $ano_atual = date('Y');  // Ano atual

        // Garantindo que o ano de lançamento seja um número inteiro
        if (!filter_var($requisicao['ano_de_lancamento'], FILTER_VALIDATE_INT)) {
            $erros['ano_de_lancamento'] = 'O campo Ano de Lançamento deve ser um número inteiro.';
        } else {
            $ano_lancamento = intval($requisicao['ano_de_lancamento']);
            // Validando se o ano está no intervalo correto (2000 até o ano atual)
            if ($ano_lancamento < 2000 || $ano_lancamento > $ano_atual) {
                $erros['ano_de_lancamento'] = 'O campo Ano de Lançamento deve ser um número inteiro entre 2000 e ' . $ano_atual . '.';
            }
        }

        // Validando o campo Descrição
        if (empty($requisicao['descricao']) || strlen($requisicao['descricao']) < 10) {
            $erros['descricao'] = 'O campo Descrição não pode estar vazio e deve ter no mínimo 10 caracteres.';
        }

        // Retornando os erros ou os dados validados
        if (!empty($erros)) {
            return ['invalido' => $erros];
        }

        return $requisicao;
    }
}

/**
 * Função para validar perguntas e respostas
 */
if (!function_exists('validarPerguntasERespostas')) {
    function validarPerguntasERespostas($dados)
    {
        $erros = [];

        // Validando a pergunta
        if (empty($dados['pergunta']) || strlen($dados['pergunta']) < 5) {
            $erros['pergunta'] = 'A pergunta deve ter pelo menos 5 caracteres.';
        }

        // Validando a resposta
        if (empty($dados['resposta']) || strlen($dados['resposta']) < 5) {
            $erros['resposta'] = 'A resposta deve ter pelo menos 5 caracteres.';
        }

        // Verificando se a resposta correta foi fornecida corretamente
        if (!isset($dados['resposta_correta']) || !in_array($dados['resposta_correta'], range(0, count($dados['resposta']) - 1))) {
            $erros['resposta_correta'] = 'A resposta correta não foi selecionada corretamente.';
        }

        return $erros;
    }
}

?>
