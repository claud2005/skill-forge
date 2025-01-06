<?php
# TRATA-SE DE UMA FORMA RÁPIDA PARA REINICIAR O BANCO DE DADOS EM AMBIENTE DE DESENVOLVIMENTO
# ESTE FICHEIRO NÃO DEVE ESTAR DISPONÍVEL EM PRODUÇÃO

# INSERE DADOS DA CONEXÃO COM O PDO UTILIZANDO SQLITE

require __DIR__ . '/criar-conexao.php';

# APAGA TABELA SE ELA EXISTIR
$pdo->exec('DROP TABLE IF EXISTS cursos;');
echo 'Tabela cursos apagada!' . PHP_EOL;

# CRIA A TABELA CURSOS COM NOMES DE COLUNAS VÁLIDOS
$pdo->exec(
    'CREATE TABLE cursos (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        titulo TEXT NOT NULL, 
        instrutor TEXT NOT NULL, 
        categoria TEXT NOT NULL, 
        codigo_do_curso TEXT NOT NULL, 
        duracao_em_horas INTEGER NOT NULL, 
        ano_de_lancamento INTEGER NOT NULL, 
        imagem_do_curso TEXT, 
        descricao TEXT NOT NULL
    );'
);
echo 'Tabela cursos criada!' . PHP_EOL;

$pdo->exec(
    'CREATE TABLE perguntas_respostas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        curso_id INTEGER NOT NULL,
        pergunta TEXT NOT NULL,
        resposta TEXT NOT NULL,
        FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
    );'
);
echo 'Tabela perguntas_respostas criada!' . PHP_EOL;

# ABAIXO UM ARRAY SIMULANDO OS DADOS DE UM CURSO
$curso = [
    'titulo' => 'One Piece',
    'instrutor' => 'Eiichiro Oda',
    'categoria' => 'Ação',
    'codigo_do_curso' => '1234567',
    'duracao_em_horas' => 200,
    'ano_de_lancamento' => 1996,
    'imagem_do_curso' => null,
    'descricao' => 'Curso sobre Ação e Aventura'
];

# INSERE CURSOS
$sqlCreate = "INSERT INTO 
    cursos (
        titulo, 
        instrutor, 
        categoria, 
        codigo_do_curso, 
        duracao_em_horas, 
        ano_de_lancamento, 
        imagem_do_curso, 
        descricao
    ) 
    VALUES (
        :titulo, 
        :instrutor, 
        :categoria, 
        :codigo_do_curso, 
        :duracao_em_horas, 
        :ano_de_lancamento, 
        :imagem_do_curso, 
        :descricao
    )";

# PREPARA A QUERY
$PDOStatement = $pdo->prepare($sqlCreate);

# EXECUTA A QUERY RETORNANDO VERDADEIRO SE A CRIAÇÃO FOI FEITA
$sucesso = $PDOStatement->execute([
    ':titulo' => $curso['titulo'],
    ':instrutor' => $curso['instrutor'],
    ':categoria' => $curso['categoria'],
    ':codigo_do_curso' => $curso['codigo_do_curso'],
    ':duracao_em_horas' => $curso['duracao_em_horas'],
    ':ano_de_lancamento' => $curso['ano_de_lancamento'],
    ':imagem_do_curso' => $curso['imagem_do_curso'],
    ':descricao' => $curso['descricao']
]);

if ($sucesso) {
    echo 'Curso padrão criado com sucesso!';
} else {
    echo 'Erro ao criar curso!';
}
?>