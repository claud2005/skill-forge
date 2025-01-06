
<!-- CSS -->
<link rel="stylesheet" href="/../recursos/css/navbar.css">

<!-- Navbar com barra de pesquisa e botão de logout -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg py-3">
    <div class="container-fluid">
        <!-- Logo e nome do SkillForge -->
        <a class="navbar-brand d-flex align-items-center text-uppercase fw-bold text-warning" href="/aplicacao/index.php">
            <img src="/Image/SkillForge.png" alt="Logo" class="me-3" style="max-height: 80px;"> <!-- Imagem maior -->
            <span>SkillForge</span>
        </a>
        <!-- Botão para colapsar menu no mobile -->
        <button class="navbar-toggler border-0 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Conteúdo do menu -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Dropdown Administração -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light position-relative fs-5" href="#" id="adminDropdown" role="button" aria-expanded="false">
                        <i class="bi bi-shield-lock-fill me-2"></i>Administração
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item" href="/admin/utilizador.php">Criar Utilizador</a></li>
                        <li><a class="dropdown-item" href="/admin/index.php">Atualizar Utilizador</a></li>
                    </ul>
                </li>

                <!-- Dropdown Cursos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light position-relative fs-5" href="#" id="courseDropdown" role="button" aria-expanded="false">
                        <i class="bi bi-pencil-square me-2"></i>Cursos
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="courseDropdown">
                        <li><a class="dropdown-item" href="/admin/curso.php">Criar Curso</a></li>
                        <li><a class="dropdown-item" href="/aplicacao/tabela-curso.php">Atualizar Curso</a></li>
                    </ul>
                </li>   

                <!-- Dropdown Estatísticas -->
                <li class="nav-item">
                    <a class="nav-link text-light position-relative fs-5" href="/aplicacao/estatisticas.php">
                        <i class="bi bi-bar-chart-line me-2"></i>Estatísticas
                    </a>
                </li>
            </ul>

            <!-- Barra de Pesquisa para Cursos -->
            <form class="search-bar-container" method="GET" action="/aplicacao/index.php">
                <div class="input-group search-bar">
                    <!-- Campo de input estilizado -->
                    <input 
                        type="text" 
                        name="pesquisa" 
                        class="search-input" 
                        placeholder="Procurar curso..." 
                        value="<?= htmlspecialchars($pesquisa ?? '') ?>" 
                        aria-label="Pesquisar curso..."
                        oninput="toggleClearButton()">
                    <!-- Botão Limpar -->
                    <button 
                        type="button" 
                        class="btn-clear" 
                        id="clearButton" 
                        onclick="clearSearch()" 
                        style="display: none;">
                        <i class="bi bi-x-circle"></i>
                    </button>

                    <!-- Botão de Pesquisa (Lupa) -->
                    <button 
                        type="submit" 
                        class="btn-submit" 
                        id="searchButton">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

       <!-- Dropdown Perfil e Logout -->
<ul class="navbar-nav ms-auto mb-2 mb-lg-0"> <!-- ALTERADO: ms-auto em vez de ms-0 para alinhar à direita -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-light position-relative fs-5" href="#" id="userDropdown" role="button" aria-expanded="false" style="margin-left: 10px;">
            <i class="bi bi-person-fill"></i> <!-- Removido o margin-left extra do ícone -->
        </a>
        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="userDropdown"> <!-- ALTERADO: Adicionado dropdown-menu-end -->
            <li><a class="dropdown-item" href="/aplicacao/perfil.php">Ver Perfil</a></li>
            <!-- Link de Logout -->
            <li><a class="dropdown-item" href="/src/controlador/aplicacao/controlar-autenticacao.php?utilizador=logout">Logout</a></li>
        </ul>
    </li>
</ul>
    </div>
</nav>

<!-- JavaScript -->
<script>
    // Exibe ou esconde o botão "Limpar" dependendo do input
    function toggleClearButton() {
        const input = document.querySelector('.search-input');
        const clearButton = document.querySelector('#clearButton');

        if (input.value.trim() !== "") {
            clearButton.style.display = "block";
        } else {
            clearButton.style.display = "none";
        }
    }

    // Limpa o campo de busca
    function clearSearch() {
        const input = document.querySelector('.search-input');
        const clearButton = document.querySelector('#clearButton');
        input.value = "";
        clearButton.style.display = "none";
        input.focus();
    }
</script>

<!-- JavaScript do Bootstrap (necessário para os dropdowns funcionarem) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para o dropdown -->
<script>
    // Selecionar todos os dropdowns
    const dropdowns = document.querySelectorAll('.nav-item.dropdown');

    // Adicionar evento de clique para abrir/fechar o dropdown
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle'); // Pega no botão de toggle (a parte clicável)
        const menu = dropdown.querySelector('.dropdown-menu'); // Pega no menu dropdown

        // Evento de clique no item de menu para alternar o dropdown
        toggle.addEventListener('click', function(event) {
            event.preventDefault();
            const isVisible = menu.classList.contains('show');
            if (isVisible) {
                menu.classList.remove('show');
            } else {
                menu.classList.add('show');
            }
        });
    });

    // Fechar todos os dropdowns se o usuário clicar fora
    document.addEventListener('click', function(event) {
        dropdowns.forEach(dropdown => {
            const menu = dropdown.querySelector('.dropdown-menu');
            const toggle = dropdown.querySelector('.dropdown-toggle');

            if (!dropdown.contains(event.target)) {
                menu.classList.remove('show');
            }
        });
    });
</script>
