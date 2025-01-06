<?php

/**
 * FICHEIRO RESPONSÁVEL PARA GARANTIR QUE UMA PÁGINA SEJA ACESSÍVEL
 * APENAS POR UM UTILIZADOR NÃO REGISTADO E AUTENTICADO
 * 
 * BASICAMENTE EVITA QUE UM UTILIZADOR REGISTADO VEJA UM SITO DE
 * UM UTILIZDOR NÃO REGISTADO. EXEMPLO, PAGINA DE LOGIN
 * 
 * PARA UTILIZAR, BASTA FAZER A REQUISIÇÃO DESTE FICHEIRO NA PÁGINA
 * QUE DEVERÁ SER PROTEGIDA
 */

# CARREGA AUXILIADOR
require_once __DIR__ . '/../auxiliadores/auxiliador.php';

# SE UTILIZADOR NÃO TIVER SESSÃO INICIADA, ENVIA PARA TELA DE LOGIN
if (isset($_SESSION['id']) || isset($_COOKIE['id'])) {

    # REDIRECIONA UTILIZADOR PARA TELA INICIAL
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/aplicacao';
    header('Location: ' . $home_url);
}