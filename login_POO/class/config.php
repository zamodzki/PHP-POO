<?php
session_start();

// CONFIGURANDO BD

define('SERVIDOR','');
define('USUARIO','');
define('SENHA','');
define('BANCO','');


function cleanPost($dados){
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

