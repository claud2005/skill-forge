<?php

// CARREGA MIDDLEWARE PARA GARANTIR QUE APENAS UTILIZADORES AUTENTICADOS ACESSEM ESTE SÍTIO
require_once __DIR__ . '/../src/middleware/middleware-utilizador.php';

// ACESSA DE FUNÇÕES AUXILIADORAS
@require_once __DIR__ . '/../src/auxiliadores/auxiliador.php';

// PROVENIENTE DE FUNÇÕES AUXILIADORAS. CARREGA O UTILIZADOR ATUAL
$utilizador = utilizador();

// CARREGA O CABECALHO PADRÃO COM O TÍTULO
$titulo = '- Estatísticas de Cursos';
include_once __DIR__ . '/templates/cabecalho.php';
include_once __DIR__ . '/templates/navbar.php';

// INICIALIZA O REPOSITÓRIO
require_once __DIR__ . '/../src/infraestrutura/basededados/repositorio-curso.php';

// FUNÇÃO PARA CARREGAR TODOS OS CURSOS
function lerTodosCurso($pdo, $filtroAno = null, $filtroCategoria = null)
{
    $sql = 'SELECT * FROM cursos WHERE 1=1';
    $params = [];
    if ($filtroAno) {
        $sql .= ' AND ano_de_lancamento = :ano';
        $params[':ano'] = $filtroAno;
    }
    if ($filtroCategoria) {
        $sql .= ' AND categoria = :categoria';
        $params[':categoria'] = $filtroCategoria;
    }
    $sql .= ' ORDER BY ano_de_lancamento DESC'; // Ordena pelos cursos mais recentes
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Recupera os filtros enviados via GET
$filtroAno = $_GET['filtroAno'] ?? null;
$filtroCategoria = $_GET['filtroCategoria'] ?? null;

// Carrega os cursos com base nos filtros
$cursos = lerTodosCurso($pdo, $filtroAno, $filtroCategoria);

// Atualiza o total de cursos após o filtro
$totalCursos = count($cursos);

// Verifica se há cursos após o filtro para determinar o mais recente
if ($totalCursos > 0) {
    $cursoMaisRecente = $cursos[0];
} else {
    $cursoMaisRecente = null;
}

// Calcula a contagem de categorias para o gráfico de pizza
$categoriaContagem = [];
foreach ($cursos as $curso) {
    $categoria = $curso['categoria'] ?? 'Não Categorizado';
    if (!isset($categoriaContagem[$categoria])) {
        $categoriaContagem[$categoria] = 0;
    }
    $categoriaContagem[$categoria]++;
}

// Obtém lista de categorias para o filtro
$categorias = array_keys($categoriaContagem);

// Inicializa um array para armazenar as horas por categoria
$horasPorCategoria = [];
foreach ($cursos as $curso) {
    $categoria = $curso['categoria'] ?? 'Não Categorizado';
    if (!isset($horasPorCategoria[$categoria])) {
        $horasPorCategoria[$categoria] = 0;
    }
    $horasPorCategoria[$categoria] += $curso['duracao_em_horas']; // Soma as horas por categoria
}

// Prepara os dados para o gráfico de barras
$cursosPorCategoriaLabels = array_keys($horasPorCategoria);
$cursosPorCategoriaHoras = array_values($horasPorCategoria); // São as somas das horas por categoria
?>

<body>
  <main class="main-content">
    <section class="py-5" style="background-color: #f8f9fa;">
      <div class="container">
        <h2 class="text-center mb-5 text-uppercase fw-bold" style="color: #007bff;">Estatísticas de Cursos Online</h2>

        <!-- Filtros -->  
        <div class="mb-4">
          <form method="GET" class="d-flex flex-wrap justify-content-center gap-3">
            <select name="filtroAno" class="form-select w-auto">
              <option value="">Filtrar por Ano</option>
              <?php foreach (array_unique(array_column($cursos, 'ano_de_lancamento')) as $ano): ?>
                <option value="<?= $ano ?>" <?= $filtroAno == $ano ? 'selected' : '' ?>><?= $ano ?></option>
              <?php endforeach; ?>
            </select>
            <select name="filtroCategoria" class="form-select w-auto">
              <option value="">Filtrar por Categoria</option>
              <?php foreach ($categorias as $categoria): ?>
                <option value="<?= htmlspecialchars($categoria) ?>" <?= $filtroCategoria == $categoria ? 'selected' : '' ?>>
                  <?= htmlspecialchars($categoria) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
            <a href="estatisticas.php" class="btn btn-secondary">Limpar Filtros</a> <!-- Botão para limpar filtros -->
          </form>
        </div>
        <div class="row">
          <!-- Exibe o total de cursos -->
          <div class="col-md-4 mb-4 wow fadeInUp" data-wow-duration="1s">
            <div class="card shadow-lg border-primary">
              <div class="card-body">
                <h5 class="card-title text-primary"><i class="bi bi-book-fill"></i> Total de Cursos</h5>
                <p class="card-text display-4 text-center text-primary"><?= $totalCursos ?></p>
              </div>
            </div>
          </div>

          <!-- Exibe o curso mais recente, se houver -->
          <div class="col-md-4 mb-4 wow fadeInUp" data-wow-duration="1.5s">
            <div class="card shadow-lg border-info" style="height: 135px;">
              <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title text-info"><i class="bi bi-calendar-check-fill"></i> Curso Mais Recente</h5>
                <?php if ($cursoMaisRecente): ?>
                  <p class="card-text"><strong>Nome:</strong> <?= htmlspecialchars($cursoMaisRecente['titulo']) ?></p>
                  <p class="card-text"><strong>Data de Lançamento:</strong> <?= $cursoMaisRecente['ano_de_lancamento'] ?></p>
                <?php else: ?>
                  <p class="card-text text-muted">Nenhum curso disponível.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Exibe estatísticas personalizadas (como a média de duração dos cursos) -->
<div class="col-md-4 mb-4 wow fadeInUp" data-wow-duration="2s">
  <div class="card shadow-lg border-success" style="height: 135px; margin-bottom: 20px;">
    <div class="card-body d-flex flex-column justify-content-center">
      <h5 class="card-title text-success"><i class="bi bi-clock-fill"></i> Média de Duração (em horas)</h5>
      <?php

      // Função para calcular o total de horas e média de duração
      $horasPorCategoria = [];
      $totalHoras = 0;
      foreach ($cursos as $curso) {
          $categoria = $curso['categoria'] ?? 'Não Categorizado';
          if (!isset($horasPorCategoria[$categoria])) {
              $horasPorCategoria[$categoria] = 0;
          }
          $horasPorCategoria[$categoria] += $curso['duracao_em_horas'];
          $totalHoras += $curso['duracao_em_horas'];
      }
      
      // Calcula a média de duração dos cursos
      $mediaHoras = $totalCursos > 0 ? $totalHoras / $totalCursos : 0;
      ?>
      <p class="display-4 text-center text-success"><?= number_format($mediaHoras, 2) ?> horas</p>
    </div>
  </div>
</div>

      <!-- Gráfico de Pizza: Distribuição de Categorias -->
<div class="col-md-6 mb-5"> <!-- Adiciona margem inferior -->
  <div class="card shadow-lg border-warning">
    <div class="card-body">
      <h5 class="card-title text-warning"><i class="bi bi-pie-chart-fill"></i> Distribuição de Categorias</h5>
      <div class="chart-container" style="width: 100%; height: 400px; margin: 0 auto; display: flex; justify-content: center;">
        <canvas id="categoriasChart" style="width: 70%; height: 100%;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Gráfico de Colunas: Número de Horas por Categoria -->
<div class="col-md-6 mb-5"> <!-- Adiciona margem inferior -->
  <div class="card shadow-lg border-info">
    <div class="card-body">
      <h5 class="card-title text-info"><i class="bi bi-bar-chart-fill"></i> Número de Horas por Categoria</h5>
      <div class="chart-container" style="width: 100%; height: 400px; margin: 0 auto;">
        <canvas id="horasChart" style="width: 100%; height: 100%;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Exibe a lista de cursos filtrados -->
<div class="row">
  <div class="col-12 wow fadeInUp" data-wow-duration="3s">
    <h4 class="mb-4">Cursos Filtrados</h4>
    <?php if ($totalCursos > 0): ?>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Título</th>
              <th>Categoria</th>
              <th>Ano de Lançamento</th>
              <th>Duração (em horas)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cursos as $curso): ?>
              <tr>
                <td><?= htmlspecialchars($curso['titulo']) ?></td>
                <td><?= htmlspecialchars($curso['categoria']) ?></td>
                <td><?= htmlspecialchars($curso['ano_de_lancamento']) ?></td>
                <td><?= htmlspecialchars($curso['duracao_em_horas']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted">Nenhum curso encontrado com os filtros aplicados.</p>
    <?php endif; ?>
  </div>
</div>
</div>
</section>
</main>

<!-- Inclui bibliotecas JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

  // Dados para Gráfico de Pizza
  const categoriasLabels = <?= json_encode(array_keys($categoriaContagem)) ?>;
  const categoriasData = <?= json_encode(array_values($categoriaContagem)) ?>;

  // Dados para Gráfico de Barras
  const cursosPorCategoriaLabels = <?= json_encode($cursosPorCategoriaLabels) ?>;
  const cursosPorCategoriaHoras = <?= json_encode($cursosPorCategoriaHoras) ?>;

  // Definindo as cores para os gráficos (cores combinadas corretamente)
  const categoriasColors = [
    '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#17a2b8', '#e83e8c' // Cor para a pizza
  ];
  const horasColors = [
    '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#17a2b8', '#e83e8c' // Mesmas cores para as barras
  ];

  // Inicializa o Gráfico de Pizza (Distribuição de Categorias)
  new Chart(document.getElementById('categoriasChart').getContext('2d'), {
    type: 'pie',
    data: {
      labels: categoriasLabels,
      datasets: [{
        data: categoriasData,
        backgroundColor: categoriasColors.slice(0, categoriasData.length), // Ajusta o número de cores para o número de categorias
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'bottom'
        }
      }
    }
  });

  // Inicializa o Gráfico de Colunas (Número de Horas por Categoria)
  new Chart(document.getElementById('horasChart').getContext('2d'), {
    type: 'bar',
    data: {
      labels: cursosPorCategoriaLabels,
      datasets: [{
        label: 'Horas',
        data: cursosPorCategoriaHoras,
        backgroundColor: horasColors.slice(0, cursosPorCategoriaHoras.length), // Ajusta o número de cores para o número de categorias
        borderColor: '#2D89FF',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        x: {
          ticks: {
            maxRotation: 0,
            autoSkip: false
          }
        },
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
</body>
<?php
include_once __DIR__ . '/templates/rodape.php';
?>
