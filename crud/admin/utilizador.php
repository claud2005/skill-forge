<?php
require_once __DIR__ . '/../src/middleware/middleware-administrador.php';
require_once __DIR__ . '/../aplicacao/templates/cabecalho.php';
require_once __DIR__ . '/../aplicacao/templates/navbar.php';

$titulo = ' - Utilizador';
?>

<link rel="stylesheet" href="/../recursos/css/utilizador.css">

<body class="d-flex flex-column" style="min-height: 100vh;">
    <main class="main-container flex-grow-1">
        <!-- Exibição de mensagens de sucesso ou erro -->
        <?php
        if (isset($_SESSION['sucesso'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($_SESSION['sucesso']) . '<br>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['sucesso']);
        }
        if (isset($_SESSION['erros'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            foreach ($_SESSION['erros'] as $erro) {
                echo htmlspecialchars($erro) . '<br>';
            }
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['erros']);
        }
        ?>

        <!-- Formulário -->
        <section class="form-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="form-container">
                            <a href="/admin/" class="btn btn-secondary mb-4">Voltar</a>
                            <form enctype="multipart/form-data" action="/src/controlador/admin/controlar-utilizador.php" method="post" class="form-control p-4 shadow-sm rounded">
                            <h2 class="text-center fw-bold mb-4">Utilizador</h2>
                                <!-- Campos do formulário -->
                                <div class="mb-3">
                                    <label for="inputNome" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="inputNome" name="nome" maxlength="100" value="<?= htmlspecialchars($_REQUEST['nome'] ?? '') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="inputApelido" class="form-label">Apelido</label>
                                    <input type="text" class="form-control" id="inputApelido" name="apelido" maxlength="100" value="<?= htmlspecialchars($_REQUEST['apelido'] ?? '') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="inputNIF" class="form-label">NIF</label>
                                    <input type="tel" class="form-control" id="inputNIF" name="nif" maxlength="9" value="<?= htmlspecialchars($_REQUEST['nif'] ?? '') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="inputTelemovel" class="form-label">Telemóvel</label>
                                    <input type="tel" class="form-control" id="inputTelemovel" name="telemovel" maxlength="9" value="<?= htmlspecialchars($_REQUEST['telemovel'] ?? '') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="inputEmail" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="inputEmail" name="email" maxlength="255" value="<?= htmlspecialchars($_REQUEST['email'] ?? '') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="inputFoto" class="form-label">Foto de Perfil</label>
                                    <input accept="image/*" type="file" class="form-control" id="inputFoto" name="foto">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tipo de Utilizador</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="checkAdminSim" name="administrador" value="1" <?= isset($_REQUEST['administrador']) && $_REQUEST['administrador'] == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="checkAdminSim">Administrador</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="checkAdminNao" name="administrador" value="0" <?= isset($_REQUEST['administrador']) && $_REQUEST['administrador'] == 0 ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="checkAdminNao">Utilizador Comum</label>
                                    </div>
                                </div>

                                <!-- Campos ocultos -->
                                <input type="hidden" name="id" value="<?= htmlspecialchars($_REQUEST['id'] ?? '') ?>">
                                <input type="hidden" name="foto" value="<?= htmlspecialchars($_REQUEST['foto'] ?? '') ?>">

                                <!-- Botão Enviar -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success" name="utilizador" value="<?= isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'atualizar' ? 'atualizar' : 'criar' ?>">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../aplicacao/templates/rodape.php'; ?>
</body>
