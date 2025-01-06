<!-- Site footer -->
<footer class="site-footer bg-dark text-white py-5">
    <div class="container">
        <div class="row text-center justify-content-center">
            <!-- Informações do Curso -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">Sobre o Curso</h5>
                <p>Professores: <strong>Maria Estrela</strong> e <strong>Wenderson Wanzeller</strong></p>
                <p>IPVC-ESTG | Unidade Curricular: <i>Projeto de Sistemas de Informação</i></p>
            </div>
            
            <!-- Desenvolvedores -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">Desenvolvido por</h5>
                <p><strong>Paulo Barros</strong> e <strong>Claudio Pereira</strong></p>
                <p>&copy; 2024 | Todos os direitos reservados</p>
            </div>
            
            <!-- Links Rápidos -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">Navegação Rápida</h5>
                <ul class="list-unstyled">
                    <li><a href="/aplicacao/sobre.php" class="text-white text-decoration-none">Sobre a Plataforma</a></li>
                    <li><a href="/aplicacao/suporte.php" class="text-white text-decoration-none">Suporte</a></li>
                </ul>
            </div>
        </div>

        <hr class="border-light my-4">
    </div>
</footer>

<!-- Inclua o Bootstrap Icons se ainda não estiver -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

<!-- Estilos adicionais para o rodapé -->
<style>
    .site-footer {
        background-color: #17a2b8; /* Cor de fundo azul claro */
        color: #fff;
        padding: 50px 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombras sutis para dar profundidade */
    }

    .site-footer .fw-bold {
        font-weight: 600;
        letter-spacing: 1px; /* Espaçamento das letras para dar mais elegância */
    }

    .site-footer h5 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        text-transform: uppercase; /* Maiúsculas para destacar as seções */
    }

    .site-footer p, .site-footer ul {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .site-footer ul {
        padding-left: 0;
        list-style: none;
    }

    .site-footer a {
        color: #fff;
        text-decoration: none;
        font-size: 1.1rem;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .site-footer a:hover {
        color: #ffd700; /* Efeito de hover para um amarelo dourado */
        transform: scale(1.05); /* Efeito de aumento ao passar o mouse */
    }

    .site-footer .bi {
        font-size: 2rem;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .site-footer .bi:hover {
        color: #ffd700; /* Efeito de hover nos ícones */
        transform: scale(1.1); /* Aumento no tamanho do ícone ao passar o mouse */
    }

    .site-footer hr {
        border-color: rgba(255, 255, 255, 0.2);
    }

    .site-footer .row {
        margin-bottom: 0;
    }

    .site-footer .col-md-4 {
        margin-bottom: 2rem;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .site-footer {
            padding: 40px 0;
        }

        .site-footer h5 {
            font-size: 1.3rem;
        }

        .site-footer .bi {
            font-size: 1.8rem;
        }

        .site-footer .row {
            text-align: center;
        }
    }
</style>
