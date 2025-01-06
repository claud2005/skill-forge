<?php

# CRIA CONEXÃO COM O PDO UTILIZANDO A BASE DE DADOS SQLITE
try {
    // Caminho para o banco de dados SQLite
    $caminhoBD = __DIR__ . '/database.sqlite';  // Atualize para o caminho correto, se necessário
    $pdo = new PDO('sqlite:' . $caminhoBD);

    // Configurações do PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilita erros
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Modo de fetch associativo

} catch (PDOException $e) {
    // Exibição de erro na conexão
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();

    // Log do erro
    file_put_contents(__DIR__ . '/PDOErrors.txt', date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    exit();
}

?>
