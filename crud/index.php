<?php
# FUNÇÕES AUXILIADORAS
require_once __DIR__ . '/src/middleware/middleware-nao-autenticado.php';
include_once __DIR__ . '/aplicacao/templates/cabecalho.php';
?>
<body class="bg-dark text-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<!-- Container principal com card maior -->
    <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <div class="container p-5 bg-secondary text-light rounded shadow-lg text-center" style="max-width: 900px;"> <!-- Aumentei o max-width aqui -->
        <div class="mb-4">
        <h1 class="display-4 text-info">Bem-vindo ao <span class="text-warning">SkillForge</span>!</h1>
        <p class="lead text-light">Explore e aprenda novas habilidades para sua carreira e vida pessoal. Aqui, o aprendizado é leve e divertido!</p>
    </div>
    </head>
    <!-- Botões de Login e Registro -->
    <div class="d-flex justify-content-center gap-3 mb-5">
        <a href="/aplicacao/login.php" class="btn btn-info btn-lg text-dark">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </a>
        <a href="/aplicacao/registo.php" class="btn btn-outline-light btn-lg">
            <i class="fas fa-user-plus me-2"></i> Registrar
        </a>
    </div>

    <!-- Seções de Recursos -->
    <div class="row text-start">
        <div class="col-md-4 mb-3">
            <div class="p-4 bg-dark text-light rounded">
                <h3 class="text-info">📚 Cursos Diversos</h3>
                <p>Desenvolva-se em várias áreas com cursos práticos e acessíveis.</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="p-4 bg-dark text-light rounded">
                <h3 class="text-info">👥 Comunidade Colaborativa</h3>
                <p>Participe de uma comunidade onde todos aprendem juntos.</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="p-4 bg-dark text-light rounded">
                <h3 class="text-info">🎓 Certificado ao Concluir</h3>
                <p>Adicione certificações ao seu portfólio e destaque-se.</p>
            </div>
        </div>
    </div>
</div>
    <!-- Link para o Bootstrap JS (opcional para componentes interativos) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

