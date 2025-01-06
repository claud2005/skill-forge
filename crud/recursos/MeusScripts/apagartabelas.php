<?php
// INSERE DADOS DA CONEXÃO COM O PDO
require_once __DIR__ . '/criar-conexao.php';

/**
 * FUNÇÃO RESPONSÁVEL POR APAGAR UMA TABELA DO BANCO DE DADOS
 */
function apagarTabela($nomeTabela)
{
    // Verifica se o nome da tabela foi fornecido
    if (empty($nomeTabela)) {
        return false;
    }

    // Sanitiza o nome da tabela para evitar injeção de SQL
    $nomeTabela = preg_replace('/[^a-zA-Z0-9_]/', '', $nomeTabela);

    // Prepara a consulta SQL para deletar a tabela
    $sql = "DROP TABLE IF EXISTS $nomeTabela";

    // Prepara e executa a consulta
    $PDOStatement = $GLOBALS['pdo']->prepare($sql);

    // Executa a consulta e retorna o resultado
    return $PDOStatement->execute();
}

// EXEMPLO DE USO: Defina o nome da tabela que você deseja apagar
$tabelaParaApagar = 'perguntas'; // Substitua pelo nome da tabela que você quer apagar

// Chama a função para apagar a tabela
if (apagarTabela($tabelaParaApagar)) {
    echo "Tabela '$tabelaParaApagar' apagada com sucesso.";
} else {
    echo "Erro ao tentar apagar a tabela '$tabelaParaApagar'.";
}
?>
